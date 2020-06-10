<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function setData(){
        $user1 = Redis::set('name', 'Bach dz');
    }

    public function getData()
    {
        // hàm get() gồm 1 giá trị truyền vào đó là key
        $user = Redis::get('name');
        
        // dd() thử xem có đúng không nhé
        dd($user);
    }
}
