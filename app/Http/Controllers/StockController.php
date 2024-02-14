<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\StockTransferSerial;
use App\Models\StockMovementHistory;
use App\Models\SerialMevementHistory;
use App\Models\Item;
use App\Models\Serial;
use App\Models\Order;
use App\Models\Location;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
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
    public function index(Request $request)
    {
        $user = Auth::user();
        $itemlist = Item::get();
        $locationlist = Location::get();
        // $from = $request['from'];
        // $to = $request['to'];
        $item = $request['item'];
        $location = $request['location'];

        if($user->role_id == 4 || $user->role_id == 9 || $user->role_id == 14){
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
     
        

        return view('stock')->with([
            'itemlist' => $itemlist, 
            'locationlist' => $locationlist,
            'stocklist' => $stocklist,
            // 'to' => $to,
            // 'from' => $from,
            'location_id' => $location,
            'item_id' => $item
        ]);
    }

    public function stock_ledger()
    {
        // If this won't work, check if the procedure is there in the database
        $stock_ledger = DB::select('CALL get_stock_ledger_data()');
        $locations = Location::get();

        return view('stock-ledger')->with(compact('stock_ledger'));
    }

    public function stock_transfer_ledger(Request $request)
    {
        $from_date = '1970-01-01';
        $to_date = date('Y-m-d');

        $stock_transfer_ledger = DB::select('CALL get_stock_transfer_ledger_data(?,?)', [$from_date, $to_date]);

        return view('stock-transfer-ledger')->with(compact('stock_transfer_ledger', 'from_date', 'to_date'));
    }

    public function stock_transfer_ledger_filtered(Request $request)
    {
        $from_date = $request->input('from_date') ?? '1970-01-01';
        $to_date = $request->input('to_date') ?? date('Y-m-d');

        $stock_transfer_ledger = DB::select('CALL get_stock_transfer_ledger_data(?,?)', [$from_date, $to_date]);

        return view('stock-transfer-ledger')->with(compact('stock_transfer_ledger', 'from_date', 'to_date'));
    }


    public function createMRItemStock()
    {
        $user = Auth::user();
        $items = request('items');
        $qtys = request('qtys');
        $usedqtys = request('usedqtys');

        try {
            DB::beginTransaction();
           
            for ($i = 0; $i < count($items); $i++) {
                $sourceStock = Stock::where('item_id', $items[$i])->where('location_id', $user->location_id)->first();
                if($sourceStock == null){
                    if($qtys[$i] > 0){

                        Stock::create([
                            'location_id' => $user->location_id,
                            'item_id' => $items[$i],
                            'qty' => $qtys[$i],
                            'balance' => 0,
                            'status' => 1,
                        ]);
    
                         ///stock movement to location history
                        StockMovementHistory::create([
                            'location_id' => $user->location_id,
                            'item_id' => $items[$i],
                            'qty' => $qtys[$i],
                            'balance' => 0,
                            'status' => 1,
                            'user_id' => $user->id,
                            'type' => 'MR'
                        ]);

                    }
                }
              

                if(isset($usedqtys[$i])){
                    
                    if($usedqtys[$i] > 0){
                        $sourceStock->qty = $sourceStock->qty - $usedqtys[$i];
                        $sourceStock->save();

                        StockMovementHistory::create([
                            'location_id' => $user->location_id,
                            'item_id' => $items[$i],
                            'qty' => -($usedqtys[$i]),
                            'balance' => 0,
                            'status' => 1,
                            'user_id' => $user->id,
                            'type' => 'USED MR'
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'MR stocks submitted successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'MR stock submission failed', 'error' => $e->getMessage()]);
        }
    }


    public function getStockForLocation()
    {

        $item = request('item');
        $location = request('location');

        $stock = Stock::where('location_id', $location)->where('item_id', $item)->first();

        if ($stock == null) {
            $stock_id = 0;
            $stock_qty = 0;
        } else {
            $stock_id = $stock->id;
            $stock_qty = $stock->qty;
        }

        return response()->json(array('stock_id' => $stock_id, 'stock_qty' => $stock_qty), 200);
    }

    public function stock_movement_details(){

        $item = request('item_id');
        $location = request('location_id');

        $stock_history = StockMovementHistory::where('location_id', $location)->where('item_id', $item)->get();
        return response()->json(array('stock_history' => $stock_history), 200);
    }

    public function createfixitemstock(Request $request){

        $user = Auth::user();
        try {

            $from = request('from');
            $items = request('items');
            $qtys = request('qtys');
            $serials = request('serials');
            $location = request('location');

            for ($i = 0; $i < count($items); $i++) {

                if ($qtys[$i] != 0) {
                    $sourceStock = Stock::where('item_id', $items[$i])->where('location_id', $location)->first();

                            if ($sourceStock == null) {
                                $sourceStock = Stock::create([
                                    'location_id' => $location,
                                    'item_id' => $items[$i],
                                    'qty' => $qtys[$i],
                                    'balance' => 0,
                                    'status' => 1,
                                ]);
                            }else{
                                $sourceStock->qty = $sourceStock->qty + $qtys[$i];
                                $sourceStock->save();
                            }

                    if($serials){
                        $subSerials = explode(",", $serials[$i]);
                        for ($j = 0; $j < count($subSerials); $j++) {
                            if($subSerials[$j]){
                                Serial::create([
                                    'serial_no' => $subSerials[$j],
                                    'item_id' => $items[$i],
                                    'stock_id' => $sourceStock->id,
                                    'status' => 1,
                                    'location_id' => $location
                                ]);
    
                                SerialMevementHistory::create([
                                    'serial_no' => $subSerials[$j],
                                    'item_id' => $items[$i],
                                    'user_id' => $user->id,
                                    'location_id' => $location,
                                    'type' => 'FIXED'
                                ]);
                            }
                           
                        }
                    }
                    

                     ///stock movement to location history
                 StockMovementHistory::create([
                    'location_id' => $location,
                    'item_id' => $items[$i],
                    'qty' => $qtys[$i],
                    'balance' => 0,
                    'status' => 1,
                    'user_id' => $user->id,
                    'type' => 'FIXED'
                ]);
                }

            }
            return response()->json(['status' => 200, 'message' => 'Fix Item submitted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Fix Item submission failed', 'error' => $e->getMessage()]);
        }
            
    }

    public function stockAdjestment(Request $request)
    {   
        if($request['location_id']){
            $stock = Stock::with(['location','item'])->where('location_id',$request['location_id'])->get();
        }else{
            $stock = Stock::with(['location','item'])->where('location_id',134)->get();
        }
        $user = Auth::user();
        $location = Location::get();
        

        return view('stockAdjustmet')->with(['stock' => $stock, 'user' => $user, 'location' => $location, 'location_id'=> $request['location_id']]);
    }

    public function stockAdjestmentStore(Request $request){

        $user = Auth::user();
        // try {

            $location_id = request('location_id');
            $item_id = request('item_id');
            $qty = request('qty');

            $stock = Stock::where('item_id', $item_id)->where('location_id', $location_id)->first();
            $stock->qty = $qty;
            $stock->save();

            ///stock movement to location history
            StockMovementHistory::create([
                'location_id' => $location_id,
                'item_id' => $item_id,
                'qty' => $qty,
                'balance' => 0,
                'status' => 1,
                'user_id' => $user->id,
                'type' => 'STOCK ADJUSTMENT'
            ]);
          

        //     return response()->json(['status' => 200, 'message' => 'Stock Adjustment submitted successfully']);
        // } catch (Exception $e) {
        //     return response()->json(['status' => 500, 'message' => 'Stock Adjustment submission failed', 'error' => $e->getMessage()]);
        // }

    }

    
}
