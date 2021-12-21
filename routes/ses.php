<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Merchants\MailhookController;

Route::post('/mail_log',[MailhookController::class,'updateMailOutbox']);

// Route::post('/mail_log',  function (Request $request) {
//     $a = json_decode($request->getContent(), true, 4);
//     info($a);
//     info($request->all());
//     return response()->json([
//         'status'=> 'Success',
//     ], 200);
// });
