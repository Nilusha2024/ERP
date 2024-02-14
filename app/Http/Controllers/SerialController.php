<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Stock;
use App\Models\Serial;


class SerialController extends Controller
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
        $stock = Stock::get();

        $seriallist = Serial::with(['location','item'])->where('serial_no','!=','N/A')->where('serial_no','!=','')->orderby('id','DESC')->get();
        return view('serial')->with(['stock' => $stock,  'seriallist' => $seriallist]);
    }

    public function getSerialsForStock()
    {
        $stock = request('stock');
        $item = request('item');
        $fromlocation = request('fromlocation');

        $serialListForCurrentItem = Serial::where('item_id', $item)->get('serial_no');
        $serialListForCurrentStock = Serial::where('location_id', $fromlocation)->get('serial_no');
        $serialListForAll = Serial::all();

        return response()->json(['status' => 200, 'serialListForAll' => $serialListForAll, 'serialListForCurrentItem' => $serialListForCurrentItem, 'serialListForCurrentStock' => $serialListForCurrentStock]);
    }

    public function getLocationForSerial()
    {
        $serial = request('serial');
        $fromlocation = request('fromlocation');

        $serialRecord = Serial::where('serial_no', $serial)->first();
        $locationIdForStock = $serialRecord['location_id'];
        if($fromlocation = $locationIdForStock){
            $location_id = '';
        }else{
            $location_id = $locationIdForStock;
        }
        $locationForSerial = Location::where('id', $locationIdForStock)->value('code');

        return response()->json(['status' => 200, 'locationForSerial' => $locationForSerial]);
    }

    public function getItemForSerial()
    {
        $serial = request('serial');

        $serialRecord = Serial::where('serial_no', $serial)->first();
        $itemForSerial = $serialRecord['item']['item_no'];

        return response()->json(['status' => 200, 'itemForSerial' => $itemForSerial]);
    }

    public function seraileditview()
    {
        $serial = Serial::with('location')->get();
        $location = Location::pluck('location', 'id');

        return view('serialedit', compact('serial', 'location'));
    }

    // public function serailedit(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'new_serial' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $serial = Serial::find($request->input('serial_no'));
    //     if (!$serial) {
    //         return response()->json(['error' => 'Serial not found.'], 404);
    //     }

    //     $newSerial = $request->input('new_serial');

    //     // Check if the new serial number already exists in the database
    //     $existingSerial = Serial::where('serial_no', $newSerial)->first();
    //     if ($existingSerial && $existingSerial->id !== $serial->id) {
    //         return response()->json(['error' => 'Serial number already exists.'], 422);
    //     }

    //     $serial->serial_no = $newSerial;
    //     $serial->save();

    //     return redirect()->back()->with('success', 'Serial number updated successfully.');
    // }

    public function serailedit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_serial' => 'required|string|max:255',
            'edit_location' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $serial = Serial::find($request->input('serial_no'));

        if (!$serial) {
            return response()->json(['error' => 'Serial not found.'], 404);
        }

        $newSerial = $request->input('new_serial');
        $newLocationId = $request->input('edit_location');

        // Check if the new serial number already exists in the database
        $existingSerial = Serial::where('serial_no', $newSerial)->first();

        if ($existingSerial && $existingSerial->id !== $serial->id) {
            // return redirect()->back()->with('error', 'Serial number already exists.');
            return response()->json(['error' => 'Serial number already exists.'], 422);
        }

        $serial->serial_no = $newSerial;
        $serial->location_id = $newLocationId;
        $serial->save();

        return redirect()->back()->with('success', 'Serial number and location updated successfully.');
    }

    
}
