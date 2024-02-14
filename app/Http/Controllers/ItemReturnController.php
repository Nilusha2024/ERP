<?php

namespace App\Http\Controllers;

use App\Models\ItemReturnDetails;
use Illuminate\Http\Request;
use App\Models\ItemReturn;
use App\Models\Item;
use App\Models\Location;
use App\Models\OldSerial;
use App\Models\Serial;
use App\Models\ReturnSerial;
use App\Models\StockMovementHistory;
use App\Models\SerialMevementHistory;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Auth;
use Exception;

class ItemReturnController extends Controller
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

        $input = $request->all();
        $item = Item::get();
        $location = Location::orderBy('id','Asc')->get();
        $oldserial = OldSerial::get();
        
        $loginlocation = Location::find($user->location_id);
        $serial = Serial::get();
        
        $returnlist = ItemReturn::with(['item', 'location', 'serial',])->get();

        $lastorder = ItemReturn::all()->last();

        if(isset($lastorder)){
           
            $lastorder = ItemReturn::all()->last();
           
            $lastID =  $lastorder['id'] + 1;
            $nextid = "IR00".$lastID;
        }else{
            $nextid = "IR001";
        }
       
        return view('ItemReturn', compact('user') )->with(['item' => $item, 'location' => $location, 'serial' => $serial, 'list' => $returnlist, 'loginlocation' => $loginlocation,'nextid' => $nextid, 'oldserial' => $oldserial]);
    }  


    public function saveReturnItems(){

        $froms = request('from');
        $tos = request('to');
        $item_ids = request('item_ids');
        $item_descriptions = request('item_descriptions');
        $serial_numbers = request('serial_numbers');
        $return_no = request('return_no');
        $qtys = request('qtys');
        $user = Auth::user();

        try{
        ////create retun for all users

                    if($user->role_id == 4 || $user->role_id == 9 || $user->role_id == 11 || $user->role_id == 12){

                    
                        $rtn_items = ItemReturn::create([
                            'created_by' => $user->id,
                            'return_no' => $return_no,
                            'status' => 4
                        ]);

                        for ($i = 0; $i < count($item_ids); $i++) {
                        

                            $return_detail =  ItemReturnDetails::create([
                                'return_id' => $rtn_items->id,
                                'item_id' => $item_ids[$i],
                                'qty'  => $qtys[$i],
                                'serial_no' => $serial_numbers[$i],
                                'returnFrom' => $froms[$i],
                                'returnTo' =>$tos[$i]
                                
                            ]);

                            $fromstock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $froms[$i])->first();
                            if($fromstock ==  NULL){
                                $data = Stock::create([
                                    'location_id' => $froms[$i],
                                    'item_id' => $item_ids[$i],
                                    'qty' => -($qtys[$i]),
                                    'balance' => 0,
                                    'status' => 1,
                                ]);

                                ///stock movement from location history
                                StockMovementHistory::create([
                                    'location_id' =>  $froms[$i],
                                    'item_id' => $item_ids[$i],
                                    'qty' => -($qtys[$i]),
                                    'balance' => 0,
                                    'status' => 1,
                                    'user_id' => $user->id,
                                    'type' => 'RETURN STOCK'
                                ]);

                            }else{

                                ////Balance Stock
                                $fromstock->qty = $fromstock->qty - $qtys[$i];
                                $fromstock->save();

                                ///stock movement from location history
                                StockMovementHistory::create([
                                    'location_id' =>  $froms[$i],
                                    'item_id' => $item_ids[$i],
                                    'qty' => -($qtys[$i]),
                                    'balance' => 0,
                                    'status' => 1,
                                    'user_id' => $user->id,
                                    'type' => 'RETURN STOCK'
                                ]);
                            }

                            ////From Stock Serial balance
                            $stock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $froms[$i])->first();
                            if($serial_numbers[$i] != NULL){
                                $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                                if($existserial == NULL){
                                    Serial::create([
                                        'serial_no' => $serial_numbers[$i], 
                                        'item_id' => $item_ids[$i],
                                        'stock_id'=> $stock->id,
                                        'status' => 1,
                                        'location_id' => $froms[$i]
                                    ]);
                                }else{
                                    $existserial->stock_id = $stock->id;
                                    $existserial->location_id = $froms[$i];
                                    $existserial->save();
                                }
                
                                ///serial movement to location history
                                SerialMevementHistory::create([
                                    'serial_no' => $serial_numbers[$i],
                                    'item_id' => $item_ids[$i],
                                    'user_id' => $user->id,
                                    'location_id' =>  $froms[$i],
                                    'type' => 'RETURN STOCK'
                                ]);
                        }

                            // Check To location stock
                            $tostock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $tos[$i])->first();
                                if($tostock ==  NULL){

                                    $data = Stock::create([
                                        'location_id' => $tos[$i],
                                        'item_id' => $item_ids[$i],
                                        'qty' => $qtys[$i],
                                        'balance' => 0,
                                        'status' => 1,
                                    ]);

                                    ///stock movement from location history
                                    StockMovementHistory::create([
                                        'location_id' =>  $tos[$i],
                                        'item_id' => $item_ids[$i],
                                        'qty' => $qtys[$i],
                                        'balance' => 0,
                                        'status' => 1,
                                        'user_id' => $user->id,
                                        'type' => 'RETURN STOCK'
                                    ]);
                                }else{

                                    ////Balance Stock
                                    $tostock->qty = $tostock->qty + $qtys[$i];
                                    $tostock->save();
                
                                    ///stock movement from location history
                                    StockMovementHistory::create([
                                        'location_id' =>  $tos[$i],
                                        'item_id' => $item_ids[$i],
                                        'qty' => $qtys[$i],
                                        'balance' => 0,
                                        'status' => 1,
                                        'user_id' => $user->id,
                                        'type' => 'RETURN STOCK'
                                    ]);
                                }    

                                ////To Stock Serial balance
                                $tostock2 =   Stock::where('item_id', $item_ids[$i])->where('location_id', $tos[$i])->first();
                            if($serial_numbers[$i] != NULL){
                                $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                                if($existserial == NULL){
                                    Serial::create([
                                        'serial_no' => $serial_numbers[$i], 
                                        'item_id' => $item_ids[$i],
                                        'stock_id'=> $tostock2->id,
                                        'status' => 1,
                                        'location_id' => $tos[$i]
                                    ]);
                                }else{
                                    $existserial->stock_id = $tostock2->id;
                                    $existserial->location_id = $tos[$i];
                                    $existserial->save();
                                }
                
                                ///serial movement to location history
                                SerialMevementHistory::create([
                                    'serial_no' => $serial_numbers[$i],
                                    'item_id' => $item_ids[$i],
                                    'user_id' => $user->id,
                                    'location_id' =>  $tos[$i],
                                    'type' => 'RETURN STOCK'
                                ]);
                        }
                    }

                }else{

                    $rtn_items = ItemReturn::create([
                        'created_by' => $user->id,
                        'return_no' => $return_no,
                        'status' => 4
                    ]);

                    for ($i = 0; $i < count($item_ids); $i++) {
                    
                        $return_detail =  ItemReturnDetails::create([
                            'return_id' => $rtn_items->id,
                            'item_id' => $item_ids[$i],
                            'qty'  => $qtys[$i],
                            'serial_no' => $serial_numbers[$i],
                            'returnFrom' => $froms[$i],
                            'returnTo' =>$tos[$i]
                            
                        ]);


                        $fromstock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $froms[$i])->first();
                        if($fromstock ==  NULL){
                            $data = Stock::create([
                                'location_id' => $froms[$i],
                                'item_id' => $item_ids[$i],
                                'qty' => -($qtys[$i]),
                                'balance' => 0,
                                'status' => 1,
                            ]);

                            ///stock movement from location history
                            StockMovementHistory::create([
                                'location_id' =>  $froms[$i],
                                'item_id' => $item_ids[$i],
                                'qty' => -($qtys[$i]),
                                'balance' => 0,
                                'status' => 1,
                                'user_id' => $user->id,
                                'type' => 'RETURN STOCK'
                            ]);

                        }else{

                            ////Balance Stock
                            $fromstock->qty = $fromstock->qty - $qtys[$i];
                            $fromstock->save();

                            ///stock movement from location history
                            StockMovementHistory::create([
                                'location_id' =>  $froms[$i],
                                'item_id' => $item_ids[$i],
                                'qty' => -($qtys[$i]),
                                'balance' => 0,
                                'status' => 1,
                                'user_id' => $user->id,
                                'type' => 'RETURN STOCK'
                            ]);
                        }

                        ////From Stock Serial balance
                        $stock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $froms[$i])->first();
                        if($serial_numbers[$i] != NULL){
                            $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                            if($existserial == NULL){
                                Serial::create([
                                    'serial_no' => $serial_numbers[$i], 
                                    'item_id' => $item_ids[$i],
                                    'stock_id'=> $stock->id,
                                    'status' => 1,
                                    'location_id' => $froms[$i]
                                ]);
                            }else{
                                $existserial->stock_id = $stock->id;
                                $existserial->location_id = $froms[$i];
                                $existserial->save();
                            }
            
                            ///serial movement to location history
                            SerialMevementHistory::create([
                                'serial_no' => $serial_numbers[$i],
                                'item_id' => $item_ids[$i],
                                'user_id' => $user->id,
                                'location_id' =>  $froms[$i],
                                'type' => 'RETURN STOCK'
                            ]);
                    }
                }
            }
        
            return response()->json(['status' => 200, 'message' => 'Insert success']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Insert failed',  'error' => $e->getMessage()]);
        }

    }

    public function saveReturnItemsssssss() {
       
        $user = Auth::user();

        // $itemno = request('itemno');
        $froms = request('from');
        $tos = request('to');
        $item_ids = request('item_ids');
        $item_descriptions = request('item_descriptions');
        $serial_numbers = request('serial_numbers');
        $return_no = request('return_no');
        $qtys = request('qtys');

        // create Return record
        // try {

            // $rtn_items = ItemReturn::create([
            //     'created_by' => $user->id,
            //     'return_no' => $return_no,
            //     'status' => 4
            // ]);

            // create ReturnDetail records for Return id

            // for ($i = 0; $i < count($item_ids); $i++) {
               

            //     $return_detail =  ItemReturnDetails::create([
            //         'return_id' => $rtn_items->id,
            //         'item_id' => $item_ids[$i],
            //         'qty'  => $qtys[$i],
            //         'serial_no' => $serial_numbers[$i],
            //         'returnFrom' => $froms[$i],
            //         'returnTo' =>$tos[$i]
                    
            //     ]);
            // }
die();
           
                for ($i = 0; $i < count($item_ids); $i++) {

                    ////Stock Remove
                    $fromstock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $froms[$i])->first();

                    if($fromstock ==  NULL){
                        $data = Stock::create([
                            'location_id' => $froms[$i],
                            'item_id' => $item_ids[$i],
                            'qty' => $qtys[$i],
                            'balance' => 0,
                            'status' => 1,
                        ]);
        

                          ///stock movement from location history
                          StockMovementHistory::create([
                            'location_id' =>  $froms[$i],
                            'item_id' => $item_ids[$i],
                            'qty' => -($qtys[$i]),
                            'balance' => 0,
                            'status' => 1,
                            'user_id' => $user->id,
                            'type' => 'RETURN STOCK'
                        ]);

                        ///stock movement to location history
                        StockMovementHistory::create([
                            'location_id' =>  $tos[$i],
                            'item_id' => $item_ids[$i],
                            'qty' => $qtys[$i],
                            'balance' => 0,
                            'status' => 1,
                            'user_id' => $user->id,
                            'type' => 'RETURN STOCK'
                        ]);

                       if($serial_numbers[$i] != NULL){
                        $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                        if($existserial == NULL){
                            Serial::create([
                                'serial_no' => $serial_numbers[$i], 
                                'item_id' => $item_ids[$i],
                                'stock_id'=> $data->id,
                                'status' => 1,
                                'location_id' => $froms[$i]
                            ]);
                        }else{
                            $existserial->stock_id = $data->id;
                            $existserial->location_id = $froms[$i];
                            $existserial->save();
                        }
        
                        ///serial movement to location history
                        SerialMevementHistory::create([
                            'serial_no' => $serial_numbers[$i],
                            'item_id' => $item_ids[$i],
                            'user_id' => $user->id,
                            'location_id' =>  $froms[$i],
                            'type' => 'RETURN STOCK'
                        ]);

                         ///serial movement to location history
                         SerialMevementHistory::create([
                            'serial_no' => $serial_numbers[$i],
                            'item_id' => $item_ids[$i],
                            'user_id' => $user->id,
                            'location_id' =>  $tos[$i],
                            'type' => 'RETURN STOCK'
                        ]);

                       }
                      
        
                      }else{
                        $fromstock->qty = $stock->qty - $qtys[$i];
                        $stock->save();
        
                        ///stock movement to location history
                        StockMovementHistory::create([
                            'location_id' => $froms[$i],
                            'item_id' => $item_ids[$i],
                            'qty' => -($qtys[$i]),
                            'balance' => 0,
                            'status' => 1,
                            'user_id' => $user->id,
                            'type' => 'RETURN'
                        ]);


                        
                        if($serial_numbers[$i] != NULL){
                            $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                            if($existserial == NULL){
                                Serial::create([
                                    'serial_no' => $serial_numbers[$i], 
                                    'item_id' => $item_ids[$i],
                                    'stock_id'=> $stock->id,
                                    'status' => 1,
                                    'location_id' => $froms[$i]
                                ]);
                            }else{
                                $existserial->stock_id = $stock->id;
                                $existserial->location_id = $froms[$i];
                                $existserial->save();
                            }
            
                             ///serial movement to location history
                             SerialMevementHistory::create([
                                'serial_no' => $serial_numbers[$i],
                                'item_id' => $item_ids[$i],
                                'user_id' => $user->id,
                                'location_id' => $froms[$i],
                                'type' => 'RETURN'
                            ]);
                        }
                       
                      }

                    ////Stock Add
                  $stock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $tos[$i])->first();
                 
                  if($stock ==  NULL){
                    $data = Stock::create([
                        'location_id' => $tos[$i],
                        'item_id' => $item_ids[$i],
                        'qty' => $qtys[$i],
                        'balance' => 0,
                        'status' => 1,
                    ]);
    
                      ///stock movement to location history
                      StockMovementHistory::create([
                        'location_id' => $tos[$i],
                        'item_id' => $item_ids[$i],
                        'qty' => $qtys[$i],
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user->id,
                        'type' => 'RETURN'
                    ]);
                   if($serial_numbers[$i] != NULL){
                    $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                    if($existserial == NULL){
                        Serial::create([
                            'serial_no' => $serial_numbers[$i], 
                            'item_id' => $item_ids[$i],
                            'stock_id'=> $data->id,
                            'status' => 1,
                            'location_id' => $tos[$i]
                        ]);
                    }else{
                        $existserial->stock_id = $data->id;
                        $existserial->location_id = $tos[$i];
                        $existserial->save();
                    }
    
                    ///serial movement to location history
                    SerialMevementHistory::create([
                        'serial_no' => $serial_numbers[$i],
                        'item_id' => $item_ids[$i],
                        'user_id' => $user->id,
                        'location_id' => $tos[$i],
                        'type' => 'RETURN'
                    ]);
                   }
                  
    
                  }else{
                    $stock->qty = $stock->qty + $qtys[$i];
                    $stock->save();
    
                    ///stock movement to location history
                    StockMovementHistory::create([
                        'location_id' => $tos[$i],
                        'item_id' => $item_ids[$i],
                        'qty' => $qtys[$i],
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user->id,
                        'type' => 'RETURN'
                    ]);

                    
                    
                    if($serial_numbers[$i] != NULL){
                        $existserial = Serial::where('serial_no', $serial_numbers[$i])->first();
                        if($existserial == NULL){
                            Serial::create([
                                'serial_no' => $serial_numbers[$i], 
                                'item_id' => $item_ids[$i],
                                'stock_id'=> $stock->id,
                                'status' => 1,
                                'location_id' => $tos[$i]
                            ]);
                        }else{
                            $existserial->stock_id = $stock->id;
                            $existserial->location_id = $tos[$i];
                            $existserial->save();
                        }
        
                         ///serial movement to location history
                         SerialMevementHistory::create([
                            'serial_no' => $serial_numbers[$i],
                            'item_id' => $item_ids[$i],
                            'user_id' => $user->id,
                            'location_id' => $tos[$i],
                            'type' => 'RETURN'
                        ]);
                    }
                   
                  }
                                  
            }

        //     return response()->json(['status' => 200, 'message' => 'Insert success']);
        // } catch (Exception $e) {
        //     return response()->json(['status' => 500, 'message' => 'Insert failed',  'error' => $e->getMessage()]);
        // }
    }
    
    public function ReturnViewAll()
    {
        $user = Auth::user();
        $itemReturnList = [];
    
        if ($user['role_id'] == 3) {
            $itemReturnList = ItemReturn::where('return_from', $user->location_id)->get();
        } elseif ($user['role_id'] == 4 || $user['role_id'] == 11 || $user['role_id'] == 9 || $user['role_id'] == 12) {
            $itemReturnList = ItemReturn::get();
        }
    
        $returnIds = $itemReturnList->pluck('id')->toArray(); // Extracting return IDs
    
        $location = Location::get();
    
        $itemReturnDetailRecordList = [];
    
        // Assuming ItemReturnDetails has a 'return_id' column
        foreach ($returnIds as $itemReturnId) {
            $itemReturnDetails = ItemReturnDetails::with(['item', 'returnfrom', 'returnto'])
                ->where('return_id', $itemReturnId)
                ->get();
    
            // Combine details for all return IDs into one array
            $itemReturnDetailRecordList[$itemReturnId] = $itemReturnDetails;
        }

        return view('ItemReturnView')->with([
            'itemReturnList' => $itemReturnList,
            'returnIds' => $returnIds,
            'location' => $location,
            'user' => $user,
            'itemReturnDetailRecordList' => $itemReturnDetailRecordList,
        ]);
    }

    
    public function ReturnViewDetails()
    {  $user = Auth::user();
        $location = Location::get();
        $itemReturnId = request('itemReturn');

        $itemReturn = ItemReturn::find($itemReturnId);
        $itemReturnDetailRecordList = ItemReturnDetails::with(['item','returnfrom','returnto'])->where('return_id', $itemReturnId)->get();

        return view('item-return-view-details')->with(['itemReturn' => $itemReturn, 'itemReturnDetailRecordList' => $itemReturnDetailRecordList, 'user'=> $user, 'location' => $location]);
    }

    public function changeState(Request $request) {
        $itemReturnId = request('item');
        $location = request('location');

        $item =  ItemReturn::where('id', $itemReturnId)->firstOrFail();
        $item->update([
            'status' => 2,
        ]);

        if ($location) {
            $item->update([
                'return_to' => $location
            ]);
        }

        return response()->json('Item Updated!');
    }

    public function ir_status_update(){
        
        try{
            $id = request('id');
            $status = request('status');
            $user = Auth::user();
            $returndetail_ids = request('returndetail_ids');
            $location_ids = request('location_ids');
            $item_ids = request('item_ids');
            $serial_nos = request('serial_nos');
            $qtys = request('qtys');

            $itemreturn = ItemReturn::where('id', $id)->first();
                            $itemreturn->status = $status;
                            $itemreturn->save();

            for ($i = 0; $i < count($returndetail_ids); $i++) {
                // DB::table('tbl_return_detail')
                // ->where('id',$returndetail_ids[$i])
                // ->update([
                //     'returnFrom' => $user->location_id,
                //     'returnTo' => $location_ids[$i]
                // ]);
                
              $stock =   Stock::where('item_id', $item_ids[$i])->where('location_id', $location_ids[$i])->first();
             
              if($stock ==  NULL){
                $data = Stock::create([
                    'location_id' => $location_ids[$i],
                    'item_id' => $item_ids[$i],
                    'qty' => $qtys[$i],
                    'balance' => 0,
                    'status' => 1,
                ]);

                  ///stock movement to location history
                  StockMovementHistory::create([
                    'location_id' => $location_ids[$i],
                    'item_id' => $item_ids[$i],
                    'qty' => $qtys[$i],
                    'balance' => 0,
                    'status' => 1,
                    'user_id' => $user->id,
                    'type' => 'RETURN'
                ]);
               
                $existserial = Serial::where('serial_no', $serial_nos[$i])->first();
                if($existserial == NULl){
                    Serial::create([
                        'serial_no' => $serial_nos[$i], 
                        'item_id' => $item_ids[$i],
                        'stock_id'=> $data->id,
                        'status' => 1,
                        'location_id' => $location_ids[$i]       

                    ]);
                }else{
                    $existserial->stock_id = $data->id;
                    $existserial->location_id = $location_ids[$i];
                    $existserial->save();
                }

                ///serial movement to location history
                SerialMevementHistory::create([
                    'serial_no' => $serial_nos[$i],
                    'item_id' => $item_ids[$i],
                    'user_id' => $user->id,
                    'location_id' => $location_ids[$i],
                    'type' => 'RETURN'
                ]);

              }else{
                $stock->qty = $stock->qty + $qtys[$i];
                $stock->save();

                ///stock movement to location history
                StockMovementHistory::create([
                    'location_id' => $location_ids[$i],
                    'item_id' => $item_ids[$i],
                    'qty' => $qtys[$i],
                    'balance' => 0,
                    'status' => 1,
                    'user_id' => $user->id,
                    'type' => 'RETURN'
                ]);


                $existserial = Serial::where('serial_no', $serial_nos[$i])->first();
                if($existserial == NULl){
                    Serial::create([
                        'serial_no' => $serial_nos[$i], 
                        'item_id' => $item_ids[$i],
                        'stock_id'=> $stock->id,
                        'status' => 1,
                        'location_id' => $location_ids[$i]    
                    ]);
                }else{
                    $existserial->stock_id = $stock->id;
                    $existserial->location_id = $location_ids[$i];
                    $existserial->save();
                }

                 ///serial movement to location history
                 SerialMevementHistory::create([
                    'serial_no' => $serial_nos[$i],
                    'item_id' => $item_ids[$i],
                    'user_id' => $user->id,
                    'location_id' => $location_ids[$i],
                    'type' => 'RETURN'
                ]);
              }
                              
            }
            // $orderhistory = Order::where('id', $id)->first();
            // $orderhistory->status = $status;
            // $orderhistory->save();

        return response()->json(['status' => 200, 'message' => 'Return Status changed successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Return Status changed failed', 'error' => $e->getMessage()]);
        }
    }

    public function getReturnOldSerial(Request $request){

        $serialno = request('serialno');
        // $item = request('item');
        $data = OldSerial::with(['location','item'])->where('serial_no', $serialno)->orderBy('posting_date','Desc')->get();

        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function autocompleteSearch(Request $request)
    {
          $query = $request->get('query');
          $filterResult = Item::where('code', 'LIKE', '%'. $query. '%')->get();
          return response()->json($filterResult);
    } 


}