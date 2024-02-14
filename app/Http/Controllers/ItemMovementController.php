<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\location;
use App\Models\warehouse_type;
use App\Models\Item;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\SerialMevementHistory;
use App\Models\ItemReturnDetails;
use DB;
use App\Models\GRN;
use App\Models\Stock;

class ItemMovementController extends Controller
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
    }

    public function general(Request $request)
    {

        $itemlist = Item::get();
        $locationlist = Location::get();
        $from = $request['from'];
        $to = $request['to'];
        $item = $request['item'];
        $location = $request['location'];
        $value = '';
        // StockTransferDetails::select('qty')->with(['item','stocktransfer'=> function($q) use($value) {
        //     // Query the name field in status table
        //     $q->where('status', '>', 2); // '=' is optional
        // }])->whereBetween('created_at', [$from, $to])->gropBy('item_id')->get();

        ////Stock movement location data
        $stockmovementlocation = StockTransferDetails::select('tbl_stock_transfer.to_location_id', 'tbl_location.code', 'tbl_location.location', DB::raw("SUM(qty) as qty"))
            ->join('tbl_stock_transfer', 'tbl_stock_transfer.id', 'tbl_stock_transfer_details.transfer_id')
            ->join('tbl_location', 'tbl_location.id', 'tbl_stock_transfer.to_location_id')
            ->join('tbl_item', 'tbl_item.id', 'tbl_stock_transfer_details.item_id')
            ->whereBetween(DB::raw('DATE(tbl_stock_transfer.created_at)'), array($from, $to))
            ->where('tbl_stock_transfer.status', '>', 2);
        if ($item != '') {
            $stockmovementlocation =  $stockmovementlocation->where('tbl_stock_transfer_details.item_id', $item);
        }
        if ($location != '') {
            $stockmovementlocation = $stockmovementlocation->where('tbl_stock_transfer.to_location_id', $location);
        }

        $stockmovementlocation = $stockmovementlocation->groupBy('tbl_location.id', 'tbl_item.id')
            ->orderBy('item_id', 'Asc')
            ->get();



        // return $stockmovementlocation;
        ////Stock movement qty data
        // $stockmovement = StockTransferDetails::select(DB::raw("SUM(qty) as qty"),'item_no','description','tbl_stock_transfer.to_location_id','tbl_stock_transfer_details.item_id')
        // ->join('tbl_stock_transfer','tbl_stock_transfer.id','tbl_stock_transfer_details.transfer_id')
        // ->join('tbl_item','tbl_item.id','tbl_stock_transfer_details.item_id')
        // ->whereBetween(DB::raw('DATE(tbl_stock_transfer.created_at)'),array($from,$to))
        // ->where('tbl_stock_transfer.status', '>', 2);

        // if($item != ''){
        //     $stockmovement =  $stockmovement->where('tbl_item.id',$item);
        // }
        // if($location != ''){
        //     $stockmovement = $stockmovement->where('tbl_stock_transfer.to_location_id',$location);
        // }

        // $stockmovement = $stockmovement->groupBy('item_id','tbl_stock_transfer.to_location_id')
        // ->orderBy('item_id', 'Asc')
        // ->get();

        ////Stock movement item data
        // $stockmovementitem = StockTransferDetails::select('tbl_item.id','item_no','description','tbl_stock_transfer.to_location_id')
        // ->join('tbl_stock_transfer','tbl_stock_transfer.id','tbl_stock_transfer_details.transfer_id')
        // ->join('tbl_item','tbl_item.id','tbl_stock_transfer_details.item_id')
        // ->whereBetween(DB::raw('DATE(tbl_stock_transfer.created_at)'),array($from,$to))
        // ->where('tbl_stock_transfer.status', '>', 2);

        // if($item != ''){
        //     $stockmovementitem =  $stockmovementitem->where('tbl_item.id',$item);
        // }
        // if($location != ''){
        //     $stockmovementitem = $stockmovementitem->where('tbl_stock_transfer.to_location_id',$location);
        // }

        // $stockmovementitem = $stockmovementitem->groupBy('item_id')
        // ->orderBy('item_id', 'Asc')
        // ->get();

        return view('itemMovement')->with([
            'itemlist' => $itemlist,
            'locationlist' => $locationlist,
            'stockmovementlocation' => $stockmovementlocation,
            'to' => $to,
            'from' => $from,
            'location_id' => $location,
            'item_id' => $item
        ]);
    }

    public function itemMovementLocationWise(Request $request)
    {
        $itemlist = Item::get();
        $locationlist = Location::get();
        $from = $request['from'];
        $to = $request['to'];
        $item = $request['item'];
        $serial_no = $request['serial_no'];
        $from_location = $request['from_location'];
    
        // Stock movement location data
        $stockmovementlocation = StockTransferDetails::select(
            'from_location.id as from_location_id',
            'from_location.location as from_location_name',
            'to_location.id as to_location_id',
            'to_location.location as to_location_name',
            'tbl_stock_transfer.tr_no',
            DB::raw("SUM(qty) as qty"),
            'tbl_stock_transfer_details.created_at'
        )
            ->join('tbl_stock_transfer', 'tbl_stock_transfer.id', 'tbl_stock_transfer_details.transfer_id')
            ->join('tbl_location as from_location', 'from_location.id', 'tbl_stock_transfer.from_location_id')
            ->join('tbl_location as to_location', 'to_location.id', 'tbl_stock_transfer.to_location_id')
            ->join('tbl_item', 'tbl_item.id', 'tbl_stock_transfer_details.item_id')
            ->whereBetween(DB::raw('DATE(tbl_stock_transfer.created_at)'), array($from, $to))
            ->where('tbl_stock_transfer.status', '>', 2);
    
        if ($item != '') {
            $stockmovementlocation = $stockmovementlocation->where('tbl_stock_transfer_details.item_id', $item);
        } else {
            $stockmovementlocation = $stockmovementlocation->where('tbl_stock_transfer_details.item_id', 1);
        }

        if ($from_location != '') {
            $stockmovementlocation = $stockmovementlocation->where('tbl_stock_transfer.from_location_id', $from_location);
        }
    
        $stockmovementlocation = $stockmovementlocation
            ->groupBy('from_location.id', 'to_location.id', 'tbl_stock_transfer.id', 'tbl_item.id')
            ->orderBy('item_id', 'Asc')
            ->get();
    

        /////Get GRN Details
        $grn = Grn::select('tbl_item.id', 'tbl_item.item_no', 'tbl_item.description', DB::raw("SUM(qty) as qty"), 'tbl_grn_details.created_at', 'tbl_grn.grn_no')
            ->join('tbl_grn_details', 'tbl_grn.id', 'tbl_grn_details.grn_id')
            ->join('tbl_item', 'tbl_item.id', 'tbl_grn_details.item_id')->where('tbl_grn.status', 1);

        if ($item != '') {
            $grn =  $grn->where('tbl_grn_details.item_id', $item);
        } else {
            $grn =  $grn->where('tbl_grn_details.item_id', 1);
        }

        $grn = $grn->groupBy('tbl_grn.id', 'tbl_item.id')
            ->orderBy('item_id', 'Asc')
            ->get();

        ///Get Stock
        if ($item != '') {
            $stock = Stock::where('item_id', $item)->where('location_id', 134)->get();
        } else {
            $stock = Stock::where('item_id', 1)->where('location_id', 134)->get();
        }

        ////Get Serial No
        $serial = [];
        if ($serial_no != '') {
            $serial = SerialMevementHistory::select('tbl_item.id', 'tbl_item.item_no', 'tbl_item.description', 'tbl_serial_no_movement_history.serial_no', 'tbl_location.location', 'tbl_serial_no_movement_history.created_at', 'users.name', 'tbl_serial_no_movement_history.type')
                ->join('tbl_location', 'tbl_location.id', 'tbl_serial_no_movement_history.location_id')
                ->join('users', 'users.id', 'tbl_serial_no_movement_history.user_id')
                ->join('tbl_item', 'tbl_item.id', 'tbl_serial_no_movement_history.item_id');

            if ($serial_no != '') {
                $serial =  $serial->where('tbl_serial_no_movement_history.serial_no', $serial_no);
            }

            $serial = $serial->groupBy('tbl_serial_no_movement_history.id')
                ->orderBy('tbl_serial_no_movement_history.id', 'Asc')
                ->get();
        }

        // Get Item Return Details
        $itemReturnDetails = ItemReturnDetails::select('tbl_return_detail.*')
            ->join('tbl_return', 'tbl_return.id', '=', 'tbl_return_detail.return_id')
            ->when($item, function ($query) use ($item) {
                return $query->where('tbl_return_detail.item_id', $item);
            })
            ->whereBetween(DB::raw('DATE(tbl_return.created_at)'), array($from, $to))
            ->whereHas('returnTo', function ($query) {
                $query->where('id', auth()->user()->location->id);
            })
            ->get();


        return view('itemstockMovement')->with([
            'locationlist' => $locationlist,
            'itemlist' => $itemlist,
            'stockmovementlocation' => $stockmovementlocation,
            'to' => $to,
            'from' => $from,
            'item_id' => $item,
            'grn' => $grn,
            'stock' => $stock,
            'serial_no' => $serial_no,
            'serial' => $serial,
            'itemReturnDetails' => $itemReturnDetails
        ]);
    }

    public function serialnohistory(Request $request)
    {

        $itemlist = Item::get();
        $locationlist = Location::get();
        // $from = $request['from'];
        // $to = $request['to'];
        // $item = $request['item'];
        $serial_no = $request['serial_no'];
        // $location = $request['location'];
        $value = '';


        ////Get Serial No
        $serial = [];
        if ($serial_no != '') {
            $serial = SerialMevementHistory::select('tbl_item.id', 'tbl_item.item_no', 'tbl_item.description', 'tbl_serial_no_movement_history.serial_no', 'tbl_location.location', 'tbl_serial_no_movement_history.created_at', 'users.name', 'tbl_serial_no_movement_history.type')
                ->join('tbl_location', 'tbl_location.id', 'tbl_serial_no_movement_history.location_id')
                ->join('users', 'users.id', 'tbl_serial_no_movement_history.user_id')
                ->join('tbl_item', 'tbl_item.id', 'tbl_serial_no_movement_history.item_id');

            if ($serial_no != '') {
                $serial =  $serial->where('tbl_serial_no_movement_history.serial_no', $serial_no);
            }

            $serial = $serial->groupBy('tbl_serial_no_movement_history.id')
                ->orderBy('tbl_serial_no_movement_history.id', 'Asc')
                ->get();
        }

        $item = SerialMevementHistory::select('tbl_item.id', 'tbl_item.item_no', 'tbl_item.description', 'tbl_serial_no_movement_history.serial_no')
            ->join('tbl_item', 'tbl_item.id', 'tbl_serial_no_movement_history.item_id')
            // ->where('tbl_serial_no_movement_history.serial_no',$serial_no)
            ->groupBy('tbl_serial_no_movement_history.serial_no')
            ->get();

        return view('itemsserialhistory')->with([
            'serial_no' => $serial_no,
            'serial' => $serial,
            'item' => $item
        ]);
    }
}
