<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\WebhookOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Merchants\ShopifyGdprWebhookController;

Route::post('/order_paid', [WebhookOrderController::class, 'orderPaid'])->name('webhook.order_paid');
Route::post('/order_created', [WebhookOrderController::class, 'orderCreated'])->name('webhook.order_created');
Route::post('/order_refund', [WebhookOrderController::class, 'orderRefund'])->name('webhook.order_refund');
Route::post('/customers/data_request', [ShopifyGdprWebhookController::class, 'dataRequest']);
Route::post('/customers/redact', [ShopifyGdprWebhookController::class, 'customerRedact']);
Route::post('/shop/redact', [ShopifyGdprWebhookController::class, 'shopRedact']);