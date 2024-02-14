<?php

namespace App\Http\Controllers;

use App\Models\CategoryCode;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\ItemReturnDetails;
use App\Models\ReturnSerial;
use App\Models\Serial;
use App\Models\Location;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Uom;
use Exception;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use index;
use  Schema;



class ItemController extends Controller
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
        $uom = Uom::get();
        $categorycode = CategoryCode::get();     
        $itemlist = Item::with(['uom','categorycode','price'])->get();
        return view('item')->with(['uom'=> $uom, 'image' => $itemlist, 'categorycode' => $categorycode,'itemlist' => $itemlist]);
    }

    public function itemstore(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        $request->validate([
            
            'item_no' => 'required|unique:tbl_item|max:255',
            'category_code_id' => 'required',
            'uom_id' => 'required',
            'mr_status'=>'required',
            'description' => 'required',
            'item_type' => 'required'
        ]);
         $input = $request->all();

        // if($request->file('image')){
        //     $fileName = time() . $request->file('image')->getClientOriginalName();
        //     $path = $request->file('image')->storeAs('images', $fileName, 'public');
        //     $input["image"] = '/storage/' . $path;
        // }else{
        //     $input["image"] = '/storage/default.png';
        // }

        $po = Item::create([
            'item_no' => $request->item_no, 
            'category_code_id' => $request->category_code_id,
            'uom_id' => $request->uom_id,
            'item_type_id' => $request->item_type_id,
            'description' => $request->description, 
            'mr_status' => $request->mr_status,
            'item_type' => $request->item_type,
            // 'image' => $request->item_no,
            
        ]);
        
        // return redirect('item')->with('flash_message', 'Member Added!');
        //return $input;
        return back()->with('success','Successfully registered a new item!');
    }

    //update

    public function itemUpdates(Request $request)
    {

        $input = $request->all();
        // $fileName = time() . $request->file('image')->getClientOriginalName();

        // $path = $request->file('image')->storeAs('images', $fileName, 'public');
        // $image_path = '/storage/' . $path;

        DB::table('tbl_item')
            ->where('id', $request->id)
            ->update([
                'item_no' => $request->item_no,
                'category_code_id' => $request->category_code_id,
                'uom_id' => $request->uom_id,
                'mr_status' => $request->mr_status,
                'description' => $request->description,
                'mr_status' => $request->mr_status
                // 'image' => $image_path
            ]);

        //Item::create($input);
        return back()->with('success', 'Successfully Update!');
    }


    public function itemedit(Request $request, $id)
    {
        $item = Item::where('id', $id)->firstOrfail();
        $categorycode = CategoryCode::all();
        $uom = Uom::all();
        $mr_status = Item::where('id', $id )->firstOrfail();
        
        return view('itemedit', compact('item', 'categorycode', 'uom', 'mr_status'));
    }


    public function getserailnoitem(Request $request)
    {

        $serialno = $request['serialno'];
        $itemno = $request['itemno'];

        $serial = Serial::where('serial_no', $serialno)->first();
        $item = null;

        if (!$serial) {

            $item = Item::where("item_no", $itemno)->first();
            // $serial = ReturnSerial::create(
            // [
            //     'serial_no' => $serialno,
            //     'item_id' => $item->id,
            // ]); 

        } else {
            $item = Item::where("item_no", $serial->item_no)->first();
        }


        $data = ["serial" => $serial, "item" => $item];
        //return response()->json($request['serialno']);
        //$user = $request['location_id'];

        //$data = Serial::with(['item'])->where('serial_no',$serialno)->get();

        return  Response::json(array('data' => $data));
    }

    public function getitemdetails(Request $request){

        $id = $request['id'];
        $item = Item::where("id", $id)->first();
       
        return  Response::json(array('data'=>$item));

    }

   


    // MR item stock join
    function getMRItemStock()
    {
        $user = Auth::user();

        $MRItemList = Item::leftJoin('tbl_stock', function ($join) use ($user) {
            $join->on('tbl_item.id', '=', 'tbl_stock.item_id')
                ->where('tbl_stock.location_id', '=', $user->location_id);
        })
            ->select('tbl_item.id', 'tbl_item.item_no', 'tbl_item.description', 'tbl_stock.location_id', 'tbl_stock.qty')
            ->where('tbl_item.mr_status', 1)
            ->get();

        return view('mr-stock')->with(['MRItemList' => $MRItemList, 'user' => $user]);
    }

     // MR item stock join
     function getFIXItemStock()
     {
        $user = Auth::user();

        $orderlist = Order::get();
        $locationlist = Location::get();
        $itemlist = Item::get();

        return view('fix-stock')->with(['user' => $user, 'orderlist' => $orderlist, 'locationlist' => $locationlist, 'itemlist' => $itemlist]);
     }
}
