<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\GrnDetails;
use App\Models\PoDetails;
use App\Models\GRN;
use App\Models\StockTransfer;
use App\Models\Po;
use App\Models\User;
use PDF;
use DB;

  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePoPDF()
    {
        $ID = request('ID');
        $po = Po::with(['podetails.item.uom','createdBy','vendor'])->where('id',$ID)->get();
        
        $data = [
            'title' => 'Purchase Order',
            'po' => $po
        ];

        view()->share('po',$data);
          
        $pdf = PDF::loadView('poPDF', $data);
    
        return $pdf->download('PO-'.$po[0]['po_no'].'.pdf');
    }

    public function generateGRNPDF()
    {
        $ID = request('ID');
        $grn = Grn::with(['grndetails.item.uom','createdBy','po.vendor'])->where('id',$ID)->get();
        $data = [
            'title' => 'Good Received Note',
            'grn' => $grn
        ];

        view()->share('grn',$data);
          
        $pdf = PDF::loadView('grnPDF', $data);
    
        return $pdf->download('GRN-'.$grn[0]['grn_no'].'.pdf');
    }

    public function generateTOPDF()
    {
        $ID = request('ID');
        $tr_details = DB::select("SELECT
                    it.item_no,
                    it.description,
                    it.item_type_id,
                    it.uom,
                    gr.tr_no,
                    SUM(grd.qty) AS tr_qty,
                    grd.item_id,
                    sum(od.center_request_qty) as center_request_qty
                    
                FROM
                    tbl_stock_transfer gr 
                        INNER JOIN
                    tbl_stock_transfer_details grd ON gr.id = grd.transfer_id
                        INNER JOIN
                    tbl_item it ON it.id = grd.item_id   
                        LEFT JOIN
                    tbl_order o ON o.id = gr.mr_id
                        LEFT JOIN
                    tbl_order_details od ON o.id = od.order_id
                    
                WHERE
                    gr.id = '$ID'
                GROUP BY grd.item_id;");
        $tr = StockTransfer::with(['stdetails.item.uom','createdBy','from','to','mr'])->where('id',$ID)->get();
        $data = [
            'title' => 'Good Received Note',
            'tr' => $tr
        ];

        view()->share('tr',$data);
          
        $pdf = PDF::loadView('trPDF', $data);
    
        return $pdf->download('TR-'.$tr[0]['tr_no'].'.pdf');
    }

    public function printPo()
    {
        $ID = request('ID');
        $po = Po::with(['podetails.item.uom','createdBy','vendor'])->where('id',$ID)->get();
        
          return view('printPo')->with('po', $po);;
    }

    public function printTo()
    { $ID = request('ID');
        $tr_details = DB::select("SELECT
                    it.item_no,
                    it.description,
                    it.item_type_id,
                    it.uom,
                    gr.tr_no,
                    SUM(grd.qty) AS tr_qty,
                    grd.item_id,
                    sum(od.center_request_qty) as center_request_qty,
                    GROUP_CONCAT(serial_no) AS serial_numbers
                    
                FROM
                    tbl_stock_transfer gr 
                        INNER JOIN
                    tbl_stock_transfer_details grd ON gr.id = grd.transfer_id
                        INNER JOIN
                    tbl_item it ON it.id = grd.item_id   
                        LEFT JOIN
                    tbl_order o ON o.id = gr.mr_id
                        LEFT JOIN
                    tbl_order_details od ON o.id = od.order_id
                    LEFT JOIN
                    tbl_stock_transfer_serial serials ON grd.id = serials.transfer_details_id
                    
                WHERE
                    gr.id = '$ID'
                GROUP BY grd.item_id;");
        $tr = StockTransfer::with(['stdetails.item.uom','createdBy','from','to','mr'])->where('id',$ID)->get();

        // dd($tr_details);
          return view('PrintTo')->with('tr', $tr)->with('tr_details', $tr_details);
    }

    public function printGrn()
    {
        $ID = request('ID');
        $grn = Grn::with(['grndetails.item.uom','createdBy','po.vendor'])->where('id',$ID)->get();
        
          return view('printGrn')->with('grn', $grn);;
    }
}

