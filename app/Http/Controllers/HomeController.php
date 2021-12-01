<?php

namespace App\Http\Controllers;


use App\Telegram;
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
        return view('home', array(
            'data' => Telegram::orderBy('id', 'DESC')->paginate(25)
        ));
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
