<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Uom;
use App\Models\CategoryCode;
use App\Models\po;
use App\Models\PoDetails;
use App\Models\Stock;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\OrderDetails;
use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\Auth;
use Response;

class MrController extends Controller
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

        $user = Auth::user();
        if($user->role_id == 9 || $user->role_id == 12){
            $itemlist = Item::get();
        }else{
            $itemlist = Item::where('mr_status',1)->get();
        }

        return view('mr')->with(['user' => $user, 'itemlist' => $itemlist]);
    }

    public function create()
    {
        $user = Auth::user();

        $items = request('items');
        $qtys = request('qtys');

        try {

            $lastorder = Order::all()->last();

            if(isset($lastorder)){
               
                $lastorder = Order::all()->last();
               
                $lastID =  $lastorder['id'] + 1;
                $nextid = "MR".$lastID;
            }else{
                $nextid = "MR1";
            }
          

            // creating order
            $or = Order::create([           
                'orderno' => $nextid,
                'center_status' => 1,
                'location_id' => $user->location_id,
                'zonemanager_id' => 0,
                'zonemanager_status' => 0,
                'store_status' => 0                
            ]);
            
            // create order details for order
            for ($i = 0; $i < count($items); $i++) {
                $ord = OrderDetails::create([
                    'item_id' => $items[$i],
                    'order_id' => $or->id,
                    'center_request_qty' => $qtys[$i]
                ]);
            }
            return response()->json(['status' => 200, 'message' => 'MR created successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'MR creation failed', 'error' => $e->getMessage()]);
        }
    }

    public function MrView()
    {
        $user = Auth::user();

        if($user['userrole']['id'] != 4 &&  $user['userrole']['id'] != 9 && $user['userrole']['id'] != 11){
            $ods = Order::where('location_id', $user['location_id'])->orderBy('id','desc')->get();
        }else{
            $ods = Order::orderBy('id','desc')->get();
            
        }
        
        $location = Location::get();
        return view('mrview')->with(['ods' => $ods, 'location' => $location, 'user' => $user]);
    }

    public function MrDetail(){

        $od = request('od');
        $oddetails = OrderDetails::with(['order','item'])->where('order_id', $od) -> get();
        return view('MrdetailView') -> with(['oddetails' => $oddetails]);
    }


    public function mr_status_update(){

        try{
            $id = request('id');
            $status = request('status');
            $orderhistory = Order::where('id', $id)->first();
                            $orderhistory->status = $status;
                            $orderhistory->save();

        return response()->json(['status' => 200, 'message' => 'MR Status changed successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'MR Status changed failed', 'error' => $e->getMessage()]);
        }
    }

    public function mr_order_received()
    {
        $user = Auth::user();

        if($user['userrole']['id'] != 4 &&  $user['userrole']['id'] != 9 && $user['userrole']['id'] != 11){
            $ods = StockTransfer::with(['stdetails','mr','to','from'])->where('to_location_id', $user['location_id'])->orderBy('id','desc')->get();
        }else{
            $ods = StockTransfer::with(['stdetails','mr','to','from'])->orderBy('id','desc')->get();           
        }
        
        $location = Location::get();
        return view('mr_tr_view')->with(['ods' => $ods, 'location' => $location, 'user' => $user]);
    }
}
