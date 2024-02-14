<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use DB;

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

    public function general(){
        return view('general');
    }

    public function firstdashoard(){
        return view('firstdashoard');
    }

    public function operationreport(){
        $order = DB::select('SELECT l.location,COUNT(o.id) as lcount FROM `tbl_stock_transfer` as o JOIN `tbl_location` as l ON o.to_location_id  = l.id WHERE o.status = 3 GROUP BY l.id');
        return view('operationreport')->with(['order' => $order]);
    }

    
}
