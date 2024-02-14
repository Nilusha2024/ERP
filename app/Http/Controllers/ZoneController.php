<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use App\Models\User;
use App\Models\StockTransfer;
use App\Models\ZoneCheck;
use App\Models\Stock;
use App\Models\Item;
use App\Models\ItemReturnDetails;
use App\Models\ItemReturn;
use Exception;

class ZoneController extends Controller
{
    public function showCheckZoneForm(Request $request)
    {
        $location = $request['location'];
        $itemlist = Item::get();
        $locationlist = Location::get();
        $zoneManagerList = User::where('role_id', 14)->get();
        $centerManagerList = User::where('role_id', 3)->get();
        $item = $request['item'];
        $user = Auth::user();

        $pendingODSCount = 0;

        if ($user['userrole']['id'] == 14) {
            $pendingODSQuery = StockTransfer::with(['stdetails', 'mr', 'to', 'from'])
                ->where('status', '!=', '4')
                ->orderBy('id', 'desc');

            if ($location != '') {
                $pendingODSQuery->where('to_location_id', $location);
            }

            $pendingODSCount = $pendingODSQuery->count();
            $ods = $pendingODSQuery->get();
        } else {
            $pendingODSQuery = StockTransfer::with(['stdetails', 'mr', 'to', 'from'])
                ->where('status', '!=', '4')
                ->orderBy('id', 'desc');

            if ($location != '') {
                $pendingODSQuery->where('to_location_id', $location);
            }

            $pendingODSCount = $pendingODSQuery->count();
            $ods = $pendingODSQuery->get();
        }

        // Fetch checked zones information
        $checkedZones = ZoneCheck::get();

        if($user->role_id == 14){
            $stocklist = Stock::with(['item', 'location', 'serials']);
            if($item != ''){
                $stocklist =  $stocklist->where('tbl_stock.item_id',$item);
            }
            if($location != ''){
                $stocklist =  $stocklist->where('tbl_stock.location_id',$location);
            }
            $stocklist = $stocklist->groupBy('tbl_stock.location_id','tbl_stock.item_id')
            ->orderBy('item_id', 'Asc')
            ->get();

        }else{
            $stocklist = Stock::with(['item', 'location', 'serials'])->where('location_id', $user->location_id);
            if($item != ''){
                $stocklist =  $stocklist->where('tbl_stock.item_id',$item);
            }
            if($location != ''){
                $stocklist =  $stocklist->where('tbl_stock.location_id',$location);
            }
            $stocklist = $stocklist->groupBy('tbl_stock.location_id','tbl_stock.item_id')
            ->orderBy('item_id', 'Asc')
            ->get();
            
        }


        return view('checkzone')->with([
            'locationlist' => $locationlist,
            'zoneManagerList' => $zoneManagerList,
            'centerManagerList' => $centerManagerList,
            'ods' => $ods,
            'user' => $user,
            'pendingODSCount' => $pendingODSCount,
            'location_id' => $location,
            'item_id' => $item,
            'checkedZones' => $checkedZones,
            'itemlist' => $itemlist, 
            'locationlist' => $locationlist,
            'stocklist' => $stocklist,
        ]);
    }

    public function storeZoneCheck(Request $request)
    {
        try {
            // dd($request->all());
            // Validate the incoming request data
            $validatedData = $request->validate([
                'location' => 'required|string',
                'comments' => 'nullable|string',
                'checkZoneManager' => 'nullable|string',
                'zoneuser' => 'nullable|string',
                'centeruser' => 'nullable|string',
            ]);

            // Create a new ZoneCheck instance
            $zoneCheck = new ZoneCheck();

            // Assign values from the request to the model properties
            $zoneCheck->location_id = $validatedData['location'];
            $zoneCheck->comments = $validatedData['comments'];
            $zoneCheck->check_zone_manager = $validatedData['checkZoneManager'];
            $zoneCheck->zone_user_id = $validatedData['zoneuser'];
            $zoneCheck->center_user_id = $validatedData['centeruser'];
            // Save the model to the database
            $zoneCheck->save();
            // return response()->json(['status' => 200, 'message' => 'Insert success']);
            return back()->with('success', 'Insert success');
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Insert failed',  'error' => $e->getMessage()]);
        }
    }
}
