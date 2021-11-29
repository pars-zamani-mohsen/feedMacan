<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @return string
     */
    public function clear()
    {
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        return 'completed...';
    }
}
