<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Uom;
use App\Models\CategoryCode;
use App\Models\GrnDetails;
use App\Models\PoDetails;
use App\Models\Po;
use App\Models\Grn;
use App\Models\Stock;
use App\Models\Serial;
use App\Models\StockMovementHistory;
use App\Models\SerialMevementHistory;
use App\Models\GrnSerialDetail;
use Response;
use Exception;
use Illuminate\Support\Facades\Auth;

class GRNController extends Controller
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
        $po = Po::where('status', 0)->get();
        $itemlist = Item::get();
        $pod = PoDetails::with(['item', 'po'])->get();
        $lastorder = Grn::all()->last();

        if (isset($lastorder)) {

            $lastorder = Grn::all()->last();

            $lastID =  $lastorder['id'] + 1;
            $nextid = "GRN000" . $lastID;
        } else {
            $nextid = "GRN0001";
        }

        return view('grn')->with(['po' => $po, 'pod' => $pod, 'itemlist' => $itemlist, 'nextid' => $nextid]);
    }


    public function addgrn()
    {
        $user = Auth::user();
        try {
            $grn = request('grn');
            $po = request('po');
            $ref_no = request('ref_no');
            $items = request('items');
            $qtys = request('qtys');
            $serials = request('serials');
            $postatus = request('postatus');

            $grnRecord = Grn::create([
                'grn_no' => $grn,
                'ref_no' => $ref_no,
                'po_id' => $po,
                'created_by' => $user->id
            ]);

            $grnRecordId = $grnRecord->id;

            for ($i = 0; $i < count($items); $i++) {
                if ($qtys[$i] > 0) {
                    $po_price = PoDetails::where('po_id', $po)->where('item_id', $items[$i])->get();

                    $grnDetail = GrnDetails::create(
                        [
                            'grn_id' => $grnRecordId,
                            'item_id' => $items[$i],
                            'qty' =>  $qtys[$i],
                            'price' => $po_price[0]['price'],
                        ]
                    );
                    $stock = Stock::where('item_id', $items[$i])->where('location_id', 134)->first();
                    if ($stock == null) {
                        $stock = Stock::create([
                            'location_id' => 134,
                            'item_id' => $items[$i],
                            'qty' => 0,
                            'balance' => 0,
                            'status' => 1,
                        ]);
                    }

                    ///stock movement to location history
                    StockMovementHistory::create([
                        'location_id' => 134,
                        'item_id' => $items[$i],
                        'qty' => $qtys[$i],
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user->id,
                        'type' => 'GRN'
                    ]);

                    $stock->qty = $stock->qty + $qtys[$i];
                    $stock->save();
                    $subSerials = explode(",", $serials[$i]);
                    for ($j = 0; $j < count($subSerials); $j++) {
                        Serial::create([
                            'item_id' => $items[$i],
                            'serial_no' => $subSerials[$j],
                            'stock_id' => $stock->id,
                            'status' => '1',
                            'location_id' => 134
                        ]);

                        SerialMevementHistory::create([
                            'serial_no' => $subSerials[$j],
                            'item_id' => $items[$i],
                            'user_id' => $user->id,
                            'location_id' => 134,
                            'type' => 'GRN'
                        ]);



                        GrnSerialDetail::create([
                            'grn_id' => $grnRecordId,
                            'grn_detail_id' => $grnDetail->id,
                            'item_id' => $items[$i],
                            'serial_no' => $subSerials[$j],
                            'status' => 1
                        ]);
                    }
                }
            }

            $po_status = Po::where('id', $po)->first();
            $po_status->status = $postatus;
            $po_status->save();

            return response()->json(['status' => 200, 'message' => 'GRN inserted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'GRN insert failed', 'error' => $e->getMessage()]);
        }
    }

    public function GrnView()
    {

        $grn = Grn::join('tbl_po', 'tbl_po.id', '=', 'tbl_grn.po_id')
            ->select('tbl_grn.id', 'tbl_grn.grn_no',  'tbl_grn.po_id', 'tbl_po.po_no', 'tbl_grn.ref_no', 'tbl_grn.created_by', 'tbl_grn.status', 'tbl_grn.created_at')
            ->orderBy('id', 'Desc')
            ->paginate(5);


        return view('grnView', compact('grn'));
    }

    public function GrnDetails()
    {
        $GRN = request('GRN');
        $grnd = GrnDetails::where('grn_id', $GRN)->with('item.serials')->get();

        return view('grnDetail')->with(['grnd' => $grnd]);
    }
}
