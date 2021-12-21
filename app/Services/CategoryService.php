<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Category;
use App\Models\File;
use App\Models\Shop;
use App\Models\ShopSetting;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    use ApiResponser;
    public function getCategory()
    {
        $shopId = auth()->user()->shop_id;
        $category = Category::where('shop_id', $shopId);
        $category = $category->paginate(10);
        return $category;
    }
    public function search($data)
    {
        $shopId = auth()->user()->shop_id;
        $query = addslashes($data["query"]);
        $category = Category::where('shop_id', $shopId)->where(function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })->get();

        return $category;
    }
    public function uploadFile($data, $file)
    {
        $fileNameOrigin = $data->getClientOriginalName();
        $fileNameHash = time() . '_' . generateRandomString(5);
        // $date=date("Y-m-d",time());
        // $date =DateTime::createFromFormat("Y-m-d",$date);
        $filePath = $file->storePubliclyAs('creatives', $fileNameHash, 's3', 'public');

        // $filePathDB ='/'.$filePath;
        $extension = $file->extension();
        return [
            'path_name' => $filePath,
            'path' => $filePath,
            'extension' => $extension,
            'file_name' => $fileNameOrigin
        ];
    }
    public function createCreative($image, $file, $data)
    {
        $shopId = auth()->user()->shop_id;
        if($data['type']==1)
        {
            $validator = Validator::make($data,[
                'image' => 'mimes:jpg,jpeg,png,gif,svg|max:5000',
            ]);
            $validator->validate();
        }
        if($data['type']==2)
        {
            $validator = Validator::make($data,[
                'image' => 'mimes:zip,rar,pdf,txt,xlsx,xlsm,xltx,xltm,xlsb,xlam,csv,doc,docx,docm,dotx,dotm,pptx,pptm,potx,potm,ppam,ppsx,ppsm,sldx,sldm,thmx|max:5000',
            ]);
            $validator->validate();
        }
        $file_size = File::where('shop_id',$shopId)->sum('file_size');
        if (isset($image) && isset($file)) {
            $fileNameOrigin = $image->getClientOriginalName();
            $fileNameHash = time() . '_' . generateRandomString(5);
            $data['file_size'] = number_format($file->getSize()/1024/1024,2);
            $file_size += $data['file_size'];
            if( $file_size > 100 )
            {
                return $this->errorResponse('The total size of your files exceeds the limit permitted - 100MB', 400);
            }
            $filePath = $file->storePubliclyAs('creatives', $fileNameHash, 's3', 'public');
            $extension = $file->extension();
            $data['path_name'] = $filePath;
            $data['path'] = $filePath;
            $data['extension'] = $extension;
            $data['file_name'] = $fileNameOrigin;
        }
        $arr = DB::transaction(function () use ($shopId, $data) {
            $file = new File();
            $file->shop_id = $shopId;
            $file->name = $data['name'];
            if (isset($data['description'])) {
                $file->description = $data['description'];
            }
            if (isset($data['link'])) {
                $file->link = $data['link'];
            }
            $file->group_id = $data['groupSelected'];

            $file->path_name = isset($data['path_name']) ? $data['path_name'] : null;

            if (isset($data['extension'])) {
                $file->extension = $data['extension'];
            }
            if (isset($data['file_size'])) {
                $file->file_size = $data['file_size'];
            }
            if (isset($data['file_name'])) {
                $file->file_name = $data['file_name'];
            }
            $file->type = $data['type'];
            if ($data['categorySelected']) {
                $data['categorySelected'] = json_decode($data['categorySelected'], true);
                if (!$data['categorySelected']['id']) {
                    $category = new Category();
                    $category->shop_id = $shopId;
                    $category->name = $data['categorySelected']['name'];
                    $category->save();
                    $file->category_id = $category->id;
                    $file->save();
                    return $file;
                } else {
                    $file->category_id = $data['categorySelected']['id'];
                    $file->save();
                    return $file;
                }
            } else {
                $file->category_id = 0;
                $file->save();
                return $file;
            }
        }, 2);
        return $this->successResponse(true,'success',200);
    }

    public function getFileCategory($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        $conditions = [['shop_id', $shopId]];
        if (isset($data['group_id'])) {
            $conditions[] = ['group_id', $data['group_id']];
        }
        if (isset($data['type'])) {
            $conditions[] = ['type', $data['type']];
        }
        $files = File::where($conditions);
        if (isset($data['name']) && !empty($data['name'])) {
            $files = $files->where('name', 'like', '%' . $data['name'] . '%');
        }
        if ($id == 0) {
            $files = $files->paginate(9);
            return $files;
        }
        $files = $files->where('category_id', $id)->paginate(9);
        return $files;
    }
    public function deleteFile($id)
    {
        $file = File::find($id);
        if (!auth()->user()->can('delete', $file)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        if ($file->path_name) {
            \Storage::disk('s3')->delete($file->path_name);
        }
        $file->delete();
        return true;
    }
    public function updateFile($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        $file = File::where('id',$id)->where('shop_id',$shopId)->first();
        $arr = DB::transaction(function () use ($file, $shopId, $data) {
            $file->shop_id = $shopId;
            $file->name = $data['name'];
            if (isset($data['description'])) {
                $file->description = $data['description'];
            }
            if (isset($data['link'])) {
                $file->link = $data['link'];
            }
            $file->group_id = $data['group_id'];
            $file->type = $data['type'];
            $file->save();
            if ($data['categorySelected']) {
                if (!$data['categorySelected']['id']) {
                    $category = new Category;
                    $category->shop_id = $shopId;
                    $category->name = $data['categorySelected']['name'];
                    $category->save();
                    $file->shop_id = $shopId;
                    $file->category_id = $category->id;
                    $file->save();
                    return $file;
                } else {
                    $file->category_id = $data['categorySelected']['id'];
                    $file->save();
                    return $file;
                }
            } else {          
                $file->save();
                return $file;
            }
        }, 2);
        return true;
    }
    public function getAllFile()
    {
        $shopId = auth()->user()->shop_id;
        $count_files = File::where('shop_id', $shopId)->count();
        return $count_files;
    }
    public function fileDownLoad($id)
    {
        $file = File::find($id);
        return $file;
    }
    public function socialCreative($id, $code, $groupId)
    {
        $file = File::where('id', $id)->select('link', 'shop_id', 'path_name')->first();
        $shop = Shop::where('id', $file->shop_id)->select('shop')->first();
        $shopSettings = ShopSetting::where('shop_id', $file->shop_id)->select('brand_name')->first();
        return [
            'file' => $file,
            'shop' => $shop,
            'brand_name' => $shopSettings->brand_name,
            'code' => $code,
            'groupId' => $groupId
        ];
    }
    public function uploadFileExcel($file)
    {
        $path = $file->store('fileExcel');
        $pathExcel = Storage::disk('local')->path($path);
        return [
            'path' => $path,
            'pathExcel' => $pathExcel
        ];
    }
    public function deleteCategory($removeAllFile, $id)
    {
        $category = Category::find($id);
        if (!auth()->user()->can('delete', $category)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        if ($removeAllFile) {
            $category->files()->delete();
            $category->delete();
        } else {
            DB::table('files')
                ->where('category_id', $id)
                ->update(['category_id' => 0]);
            $category->delete();
        }
        return true;
    }

    public function bulkUpload( $files, $data)
    {
        $shopId = auth()->user()->shop_id;
        $file_size = File::where('shop_id',$shopId)->sum('file_size');
        if( $file_size > 100 )
        {
            return $this->errorResponse('The total size of your files exceeds the limit permitted - 100MB', 400);
        }
        $categoryId = 0;
        if ($data['categorySelected']) {
            $data['categorySelected'] = json_decode($data['categorySelected'], true);
            if (!$data['categorySelected']['id']) {
                $category = new Category();
                $category->shop_id = $shopId;
                $category->name = $data['categorySelected']['name'];
                $category->save();
                $categoryId = $category->id;
            } else {
                $categoryId = $data['categorySelected']['id'];
            }
        } 
        foreach($files as $file)
        {
            $fileNameHash = time() . '_' . generateRandomString(5);
            $data['file_size'] = number_format($file->getSize()/1024/1024,2);
            $file_size += $data['file_size'];
            if( $file_size > 100 )
            {
                return $this->errorResponse('The total size of your files exceeds the limit permitted - 100MB', 400);
            }
            $filePath = $file->storePubliclyAs('creatives', $fileNameHash, 's3', 'public');
            $extension = $file->extension();
            $data['path_name'] = $filePath;
            $data['path'] = $filePath;
            $data['extension'] = $extension;
            $data['file_name'] = $file->getClientOriginalName();
            $fileModel = new File();
            $fileModel->shop_id = $shopId;
            $fileModel->name = $file->getClientOriginalName();
            $fileModel->group_id = $data['groupSelected'];
            $fileModel->path_name = isset($data['path_name']) ? $data['path_name'] : null;
            if (isset($data['extension'])) {
                $fileModel->extension = $data['extension'];
            }
            if (isset($data['file_size'])) {
                $fileModel->file_size = $data['file_size'];
            }
            if (isset($data['file_name'])) {
                $fileModel->file_name = $data['file_name'];
            }
            $fileModel->type = $data['type'];
            $fileModel->category_id = $categoryId;
            $fileModel->save();
        }
        return $this->successResponse(true,'success',200);
    }

    public function uploadImageEditor($file)
    {
        $fileNameHash = time() . '_' . generateRandomString(5);
        $filePath = $file->storePubliclyAs('uploads', $fileNameHash,'s3','public');
        return [
            "location" => "https://d2xrtfsb9f45pw.cloudfront.net/".$filePath
        ];

    }
}
