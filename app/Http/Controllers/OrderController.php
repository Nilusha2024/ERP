<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;
use Response;
use DB;

class OrderController extends Controller
{
    public function mrdetails()
    {

        $order = request('MR');
       
        $data = DB::select("SELECT
        it.item_no,
        it.description,
        it.item_type_id,
        po.orderno,
        SUM(pod.center_request_qty) AS center_request_qty,
        gr.tr_no,
        pod.item_id,
        IFNULL(SUM(grd.qty), 0) AS grn_qty,
        SUM(s.qty) AS stockqty,
        s.id as stock_id
    FROM
        tbl_order po
            INNER JOIN
        tbl_order_details pod ON po.id = pod.order_id
            INNER JOIN
        tbl_item it ON it.id = pod.item_id
        INNER JOIN
        tbl_stock s ON s.item_id = pod.item_id
            LEFT JOIN
            tbl_stock_transfer gr ON po.id = gr.mr_id
            LEFT JOIN
            tbl_stock_transfer_details grd ON (gr.id = grd.transfer_id
            AND grd.item_id = pod.item_id)
    WHERE
        po.id = '$order' AND s.location_id  = 134
    GROUP BY pod.item_id");
       
        return  Response::json(array('data' => $data));
    }

    public function mrlocation(){
    
        $order = request('MR');
        $data = Order::with(['location'])->where('id', $order)->get();
        $details = OrderDetails::where('order_id', $order)->get();
        return  Response::json(array('data'=> $data, 'details' => $details));
    }
}
