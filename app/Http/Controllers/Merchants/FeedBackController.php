<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedBack;

class FeedBackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $feedback = new FeedBack;
        $feedback->rating = $data['rating'];
        $feedback->comment = $data['comment'];
        $feedback->shop_id = auth()->user()->shop_id;
        $feedback->save();
    }
}
