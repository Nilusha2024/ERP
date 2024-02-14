<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\location;
use App\Models\warehouse_type;
use DB;

class LocationController extends Controller
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
        $locátion = location::with(['type'])->get();
        $type = warehouse_type::get();
        return  view('location')->with(['locátion' => $locátion, 'type' => $type]);
    }
    public function locationtore(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'warehouse_type_id'  => 'required',
            'code' => 'required|unique:tbl_location',
            'location' => 'required'
        ]);

        location::create($input);
        return back()->with('success', 'Successfully registered a new location!');
    }

    public function editindex(Request $request)
    {
        $locátion = location::with(['type'])->where('id', $request->id)->get();
        $type = warehouse_type::get();
        return  view('locationEdit')->with(['locátion' => $locátion, 'type' => $type]);
    }

    public function locationupdatestore(Request $request)
    {
        DB::table('tbl_location')
            ->where('id', $request->id)
            ->update([
                'code' => $request->code,
                'location' => $request->location,
                'warehouse_type_id' => $request->warehouse_type_id
            ]);
        return back()->with('success', 'Successfully Update!');
    }

    public function search()
    {

    }
}
