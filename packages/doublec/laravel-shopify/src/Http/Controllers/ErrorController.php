<?php

namespace DoubleC\LaravelShopify\Http\Controllers;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ErrorController extends Controller
{
    /**
     * Show errors message
     * @param array|string $messages
     * @return Application|Factory|View
     */
    public function displayErrorMessage(array|string $messages): Factory|View|Application
    {
        return view('laravel-shopify::pages.errors', is_array($messages) ? $messages : [
            "messages" => [$messages]
        ]);
    }
}
