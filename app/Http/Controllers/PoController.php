<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Uom;
use App\Models\CategoryCode;
use App\Models\po;
use App\Models\PoDetails;
use App\Models\GrnDetails;
use App\Models\Grn;
use App\Models\Stock;
use App\Models\Vendor;
use Exception;
use Response;
use Illuminate\Support\Facades\Auth;
use DB;

class PoController extends Controller
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

        $itemlist = Item::get();
        $vendorlist = Vendor::get();
        $lastorder = Po::all()->last();

            if(isset($lastorder)){
               
                $lastorder = Po::all()->last();
               
                $lastID =  $lastorder['id'] + 1;
                $nextid = "PO-000".$lastID;
            }else{
                $nextid = "PO-0001";
            }

        return view('po')->with(['user' => $user, 'itemlist' => $itemlist, 'vendorlist' => $vendorlist, 'nextid' => $nextid]);
    }

    public function viewAll()
    {
        $user = Auth::user();

        $polist = Po::OrderBy('id','Desc')->paginate(20);
        return view('po-view-all')->with(['polist' => $polist, 'user' => $user]);
    }

    public function viewDetails()
    {
        $user = Auth::user();

        $po_id = request('po');
        $po = Po::find($po_id);
        $poDetailsRecordList = PoDetails::where('po_id', $po_id)->get();

        return view('po-view-details')->with(['po' => $po, 'poDetailsRecordList' => $poDetailsRecordList, 'user' => $user]);
    }

    public function create()
    {
        $user = Auth::id();

        try {

            $po_no = request('po_no');
            $vendor = request('vendor');
            $items = request('items');
            $qtys = request('qtys');
            $prices = request('prices');

            // creating po
            $po = Po::create([
                'po_no' => $po_no,
                'vendor_id' => $vendor,
                'approved_by_finance' => 0,
                'approved_by_ed' => 0,
                'created_by' => $user,
                'status' => 0,
            ]);

            $po_id = $po->id;

            // creating po details records for po
            for ($i = 0; $i < count($items); $i++) {
                PoDetails::create(
                    [
                        'po_id' => $po_id,
                        'item_id' => $items[$i],
                        'qty' => $qtys[$i],
                        'price' => $prices[$i],
                        'status' => 0
                    ]
                );
            }

            return response()->json(['status' => 200, 'message' => 'Purchase order submitted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Purchase order submission failed', 'error' => $e->getMessage()]);
        }
    }

    public function approve()
    {
        try {

            $po_id = request('po');
            $po = Po::find($po_id);

            $approval = strtoupper(request('approval'));
            $status = (int)request('status');

            $completed_action = ($status == 1) ? 'given' : 'rejected';
            $action = ($status == 1) ? 'approval' : 'rejection';

            if ($approval == 'FINANCE') {
                $po->approved_by_finance = $status;
            } else if ($approval == 'ED') {
                $po->approved_by_ed = $status;
            }

            $po->save();

            return response()->json(['status' => 200, 'message' => ucfirst(request('approval')) . ' approval ' . $completed_action]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => ucfirst(request('approval')) . ' approval ' . $action . ' failed', 'error' => $e->getMessage()]);
        }
    }

    public function getpodetals(Request $request)
    {
        $pono = $request['id'];

        $data = DB::select("SELECT
        it.item_no,
        it.description,
        po.po_no,
        pod.item_id,
        pod.qty AS pod_qty,
        gr.grn_no,
        IFNULL(SUM(grd.qty), 0) AS grn_qty,
        it.mr_status
    FROM
        tbl_po po
            INNER JOIN
        tbl_po_details pod ON po.id = pod.po_id
            LEFT JOIN
        tbl_item it ON it.id = pod.item_id
            LEFT JOIN
        tbl_grn gr ON po.id = gr.po_id
            LEFT JOIN
        tbl_grn_details grd ON (gr.id = grd.grn_id
            AND grd.item_id = pod.item_id)
    WHERE
        po.id = '$pono' 
    GROUP BY pod.item_id");
        
    return  Response::json(array('data' => $data));
    }
}
