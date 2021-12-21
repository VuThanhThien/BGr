<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Affiliate;
use App\Models\Shop;
use App\Models\Translate;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $credentials = $request->only('email', 'password');
        // dd($credentials);
        if (auth('web')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('Errors', 'The email address or password is incorrect.');
        }
    }

    public function register()
    {
        return view('admin.register');
    }

    public function createAcount(Request $request)
    {
        //   $validator = Validator::make($request->all(),[
        //         'name' => 'required|max:255',
        //         'email' => 'required|email|unique:admins',
        //         'password' => 'required|min:6',
        //     ]);
        //     if($validator->fails())
        //     {
        //        return redirect()->back()->withErrors($validator);
        //     }
        //     $data = $request->all();
        //     $this->create($data);
        //     return redirect()->route('admin.login')->with('success','Created!');
        return response()->json(['message' => 'not found'], 404);
    }

    protected function create($data)
    {
        Admin::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['name']
        ]);
    }

    public function logout()
    {
        Session::flush();
        auth('web')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function postTranslation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'text' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = $request->all();
        Translate::updateOrCreate(
            [
                'key' => $data['key'],
                'locale' => 'en',
                'shop' => 'default'
            ],
            [
                'key' => $data['key'],
                'locale' => 'en',
                'text' => $data['text'],
                'shop' => 'default'
            ]
        );
        $arrLanguage = ['es', 'zh', 'de', 'fr', 'pt', 'it'];
        try {
            foreach ($arrLanguage as $language) {
                $content = GoogleTranslate::trans($data['text'], $language, 'en');
                Translate::updateOrCreate(
                    [
                        'key' => $data['key'],
                        'locale' => $language,
                        'shop' => 'default'
                    ],
                    [
                        'key' => $data['key'],
                        'locale' => $language,
                        'text' => $content,
                        'shop' => 'default'
                    ]
                );
            }
            return view('admin.dashboard', ['success' => 'Dịch thành công key: '.$data['key']]);
        } catch (\Exception $ex) {
            return view('admin.dashboard', ['error' => 'Vui lòng thử lại sau khoảng 2 giờ hoặc hôm sau ...']);
        }
    }

    public function getTranslationsOne()
    {
        return view('admin.partials.translation_one');
    }

    public function postTranslationOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'text' => 'required',
            'locale' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = $request->all();
        Translate::updateOrCreate(
            [
                'key' => $data['key'],
                'locale' => $data['locale'],
                'shop' => 'default'
            ],
            [
                'key' => $data['key'],
                'locale' => $data['locale'],
                'shop' => 'default',
                'text' => $data['text']
            ]
        );
        return view('admin.partials.translation_one',['success'=>'Thêm thành công key: '.$data['key']]);
    }

    public function loginAsMerchant()
    {
        return view('admin.partials.login_as');
    }

    public function postLoginAs(Request $request)
    {
        $shopRequest = $request->shop;
        if(strpos($request->shop,'.myshopify.com')===false)
        {
            $shopRequest = $shopRequest.'.myshopify.com';
        }
        $shop = Shop::where('shop',$shopRequest)->whereNotNull('access_token')->first();
        if($shop)
        {
            $user = User::where('shop_id',$shop->id)->first();
            
            $token = auth('api')->login($user);
            // dd($user);
            return response()->json([
                'status' => true,
                'data' => $token]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'data' => 'Not found'
            ]);
        }

    }

    public function createNotification()
    {
        return view('admin.partials.notifications');
    }

    public function postNotification(Request $request)
    {
        $request->validate([
            'header' => 'required',
            'content' => 'required'
        ]);
        $user = User::whereHas('shop',function($query){
            $query->whereNotNull('access_token');
        });
        $user->chunk(200, function($user) use($request){
            Notification::send($user, new SendMessageNotification($request->all()));
        });
        return view('admin.partials.notifications',['success' => 'Added']);
    }
}
