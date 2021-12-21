<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService=$categoryService;
    }
    public function search(Request $request)
    {
        $data=$this->categoryService->search($request->all());
        return response()->json($data);
    }
    public function uploadFile(Request $request)
    {
        $pathLogo=$this->categoryService->uploadFile($request->image,$request->file('image'));
        return $this->successResponse($pathLogo,'success',200);
    }
    public function createCreative(Request $request)
    {
       return $this->categoryService->createCreative($request->image,$request->file('image'),$request->all());
    }
    public function getCategory()
    {
        $result = $this->categoryService->getCategory();
        return $this->successResponse( $result,'success',200);
    }
    public function getFileCategory(Request $request,$id)
    {
        $result = $this->categoryService->getFileCategory($request->all(),$id);
        return $this->successResponse( $result,'success',200);
    }

    public function deleteFile($id)
    {
        $result = $this->categoryService->deleteFile($id);
        return $this->successResponse( $result,'success',200);
    }
    public function updateFile(Request $request,$id)
    {
        $result = $this->categoryService->updateFile($request->all(),$id);
        return $this->successResponse( $result,'success',200);
    }
    public function getAllFile()
    {
        $result = $this->categoryService->getAllFile();
        return $this->successResponse( $result,'success',200);
    }
    public function fileDownLoad($id)
    {
        $file=$this->categoryService->fileDownLoad($id);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" .$file->file_name);
        return readfile($file->image);
    }
    public function socialCreative($id,$code,$groupId)
    {
        $result = $this->categoryService->socialCreative($id,$code,$groupId);
        return view('share_social',['data'=>$result]);
    }
    public function deleteCategory($removeAllFile,$id)
    {
        $result=$this->categoryService->deleteCategory($removeAllFile,$id);
        return $this->successResponse($result, 'success', 200);    
    }

    public function bulkUpload(Request $request)
    {
        if($request->hasFile('files'))
        {
          return $this->categoryService->bulkUpload($request->file('files'),$request->all());          
        }
        else
        {
            return $this->errorResponse('Image is required', 400);
        }
    }

    public function uploadImageEditor(Request $request)
    {
        $path = $this->categoryService->uploadImageEditor($request->file('image'));
        return $this->successResponse($path,'success',200);
    }
}