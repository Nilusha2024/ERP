<?php

namespace App\Http\Controllers;

use App\Models\CategoryCode;
use App\Models\Item;
use App\Models\Location;
use App\Models\Order;
use App\Models\Serial;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\StockTransferSerial;
use App\Models\StockMovementHistory;
use App\Models\SerialMevementHistory;
use App\Models\Uom;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TransferOrderController extends Controller
{
    // transfer order controller

    // load transfer order page
    public function index(Request $request)
    {
        $user = Auth::user();

        $orderlist = Order::orderBy('id', 'Desc')->get();
        $locationlist = Location::get();
        $itemlist = Item::get();
        $mr_id = $request['od'];
        if ($mr_id) {
            $mr = Order::where('id', $mr_id)->get();
        } else {
            $mr = [];
        }

        return view('transfer-order')->with(['user' => $user, 'orderlist' => $orderlist, 'locationlist' => $locationlist, 'itemlist' => $itemlist, 'mr' => $mr, 'user' => $user]);
    }

    // view all transfer orders
    public function viewAll()
    {
        $user = Auth::user();
        $to = $user->location_id;
        $location = Location::get();
        $allusers = User::whereIn('role_id', [2, 9, 4, 5])->get();
        if ($user->role_id == 3) {
            $transferlist = StockTransfer::where('to_location_id', $to)->orderby('id', 'DESC')->get();
        } elseif ($user->role_id == 12) {
            $transferlist = StockTransfer::where('created_by', $user->id)->orderby('id', 'DESC')->get();
        } else {
            $transferlist = StockTransfer::orderby('id', 'DESC')->get();
        }

        return view('transfer-order-view-all')->with(['transferlist' => $transferlist, 'user' => $user, 'location' => $location, 'allusers' => $allusers]);
    }


    // view a single transfer order in detail
    public function viewDetails()
    {
        $user = Auth::user();
        $transferId = request('transfer');
        $transfer = StockTransfer::find($transferId);
        $transferDetailsRecordList = StockTransferDetails::where('transfer_id', $transferId)->get();

        return view('transfer-order-view-details')->with(['transfer' => $transfer, 'transferDetailsRecordList' => $transferDetailsRecordList, 'user' => $user]);
    }


    // view a the serials associated with a single transfer detail record
    public function viewSerials()
    {
        $transferDetailId = request('transfer_detail');
        $transferDetail = StockTransferDetails::find($transferDetailId);
        $transferSerialRecordList = StockTransferSerial::where('transfer_details_id',  $transferDetailId)->get();

        return view('transfer-order-view-serials')->with(['transferDetail' => $transferDetail, 'transferSerialRecordList' => $transferSerialRecordList]);
    }

    // create stock transfer record
    public function create()
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $type = request('type');
            $from = request('from');
            $to = request('to');
            $items = request('items');
            $item_types = request('item_types');
            $qtys = request('qtys');
            $serials = request('serials');
            $mr = request('mr');
            $remark = request('remark');
            $helpdeskno = request('helpdeskno');

            $lastorder = StockTransfer::all()->last();

            if (isset($lastorder)) {

                $lastorder = StockTransfer::all()->last();

                $lastID =  $lastorder['id'] + 1;
                $nextid = "TR0" . $lastID;
            } else {
                $nextid = "TR01";
            }

            // creating stock transfer
            $stockTransfer = StockTransfer::create([
                'tr_no' => $nextid,
                'created_by' => $user->id,
                'received_by' => 0,
                'from_location_id' => $from,
                'to_location_id' => $to,
                'reason' => $remark,
                'helpdeskno' => $helpdeskno,
                'type' => $type,
                'status' => 1,
                'mr_id' => $mr
            ]);

            if ($mr != '') {

                $mrorder = Order::find($mr);
                $mrorder->status = 2;
                $mrorder->save();
            }

            $stockTransferId = $stockTransfer->id;
            $stockTransferSourceLocation = $stockTransfer->from_location_id;

            // creating stock tranfer details for the stock transfer
            for ($i = 0; $i < count($items); $i++) {
                if ($qtys[$i] > 0) {
                    $stockTransferDetail = StockTransferDetails::create(
                        [
                            'transfer_id' => $stockTransferId,
                            'item_id' => $items[$i],
                            'qty' => $qtys[$i],
                        ]
                    );

                    $stockTransferDetailId = $stockTransferDetail->id;
                    $subSerials = explode(",", $serials[$i]);

                    // creating stock transfer serial records for the stock transfer detail, only if the item type is 0
                    // if ($item_types[$i] == 1) {

                    for ($j = 0; $j < count($subSerials); $j++) {

                        $serialRecord = Serial::where('serial_no', $subSerials[$j])->first();
                        $itemCode = Item::where('id', $items[$i])->value('item_no');

                        if ($serialRecord == null) {
                            $sourceStock = Stock::where('item_id', $items[$i])->where('location_id', $stockTransferSourceLocation)->first();
                            if ($sourceStock == null) {
                                $addsourceStock = Stock::create([
                                    'location_id' => $to,
                                    'item_id' => $items[$i],
                                    'qty' => 1,
                                    'balance' => 0,
                                    'status' => 1,
                                ]);
                                Serial::create([
                                    'serial_no' => $subSerials[$j],
                                    'item_id' => $items[$i],
                                    'stock_id' => $addsourceStock->id,
                                    'status' => 1,
                                    'item_no' => $itemCode,
                                    'location_id' => $to
                                ]);
                                // Serial::where('serial_no', $subSerials[$j])
                                // ->where('item_id', $items[$i])
                                // ->update([
                                //     'stock_id' => $addsourceStock->id,
                                //     'status' => 1,
                                //     'item_no' => $itemCode,
                                //     'location_id' => $to
                                // ]);

                            } else {
                                // Serial::create([
                                //     'serial_no' => $subSerials[$j],
                                //     'item_id' => $items[$i],
                                //     'stock_id' => $sourceStock->id,
                                //     'status' => 1,
                                //     'item_no' => $itemCode,
                                //     'location_id' => $to
                                // ]);
                                Serial::where('serial_no', $subSerials[$j])
                                    ->where('item_id', $items[$i])
                                    ->update([
                                        'stock_id' => $sourceStock->id,
                                        'status' => 1,
                                        'item_no' => $itemCode,
                                        'location_id' => $to
                                    ]);
                            }

                            SerialMevementHistory::create([
                                'serial_no' => $subSerials[$j],
                                'item_id' => $items[$i],
                                'user_id' => $user->id,
                                'location_id' => $to,
                                'type' => 'TR'
                            ]);
                        } else {

                            $existsourceStock = Stock::where('item_id', $items[$i])->where('location_id', $to)->first();
                            if ($existsourceStock == null) {
                                $addsourceStock = Stock::create([
                                    'location_id' => $to,
                                    'item_id' => $items[$i],
                                    'qty' => 0,
                                    'balance' => 0,
                                    'status' => 1,
                                ]);
                                Serial::create([
                                    'serial_no' => $subSerials[$j],
                                    'item_id' => $items[$i],
                                    'stock_id' => $addsourceStock->id,
                                    'status' => 1,
                                    'item_no' => $itemCode,
                                    'location_id' => $to
                                ]);
                            } else {

                                $serialRecord->stock_id = $existsourceStock->id;
                                $serialRecord->location_id = $to;
                                $serialRecord->save();
                            }


                            SerialMevementHistory::create([
                                'serial_no' => $subSerials[$j],
                                'item_id' => $items[$i],
                                'user_id' => $user->id,
                                'location_id' => $to,
                                'type' => 'TR'
                            ]);
                        }

                        StockTransferSerial::create([
                            'transfer_id' => $stockTransfer->id,
                            'transfer_details_id' => $stockTransferDetail->id,
                            'item_id' => $items[$i],
                            'serial_no' => $subSerials[$j],
                            'status' => 1
                        ]);
                    }

                    $sourceStock = Stock::where('item_id', $items[$i])->where('location_id', $stockTransferSourceLocation)->first();

                    if ($sourceStock != null) {
                        $sourceStock->qty = $sourceStock->qty - $qtys[$i];
                        $sourceStock->save();
                        // $sourceStock = Stock::create([
                        //     'location_id' => $stockTransferSourceLocation,
                        //     'item_id' => $items[$i],
                        //     'qty' => 0,
                        //     'balance' => 0,
                        //     'status' => 1,
                        // ]);
                    }
                    // }
                }
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => 'Transfer order submitted successfully']);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Transfer order submission failed', 'error' => $e->getMessage()]);
        }
    }

    public function edit()
    {
        $user = Auth::user();

        $transferId = request('transfer');

        $transfer = StockTransfer::find($transferId);
        $transferItemDetails = StockTransferDetails::where('transfer_id', $transferId)->get();

        foreach ($transferItemDetails as &$itemRecord) {
            $itemRecord->serials = $itemRecord->serials()->pluck('serial_no')->implode(',');;
            $itemRecord->itemCode = $itemRecord->item()->value('item_no');
            $itemRecord->itemDescription = $itemRecord->item()->value('description');
        }

        $orderlist = Order::get();
        $locationlist = Location::get();
        $itemlist = Item::get();

        // grab all the transfer order related details

        return view('transfer-order-edit')->with(compact('user', 'transfer', 'transferItemDetails', 'orderlist', 'locationlist', 'itemlist'));
    }

    // TODO: Send the code to Joshua and see what changes are required
    public function update()
    {
        $user = Auth::user();

        try {

            // grab the stock transfer
            $stockTransfer = StockTransfer::find(request('tr_id'));

            $type = request('type');
            $from = request('from');
            $to = request('to');
            $items = request('items');
            $item_types = request('item_types');
            $qtys = request('qtys');
            $serials = request('serials');
            $mr = request('mr');

            // tr item details to be deleted
            $trashedRecordIds = request('to_be_deleted');

            // update the core stock transfer record
            $stockTransfer->update([
                'from_location_id' => $from,
                'to_location_id' => $to,
                'reason' => 'Unknown',
                'type' => $type,
                'mr_id' => $mr
            ]);


            if ($mr != '') {
                $mrorder = Order::find($mr);
                $mrorder->status = 2;
                $mrorder->save();
            }

            $stockTransferId = $stockTransfer->id;
            $stockTransferSourceLocation = $stockTransfer->from_location_id;

            // creating stock tranfer details for the stock transfer
            if ($items) {
                for ($i = 0; $i < count($items); $i++) {

                    $stockTransferDetail = StockTransferDetails::create(
                        [
                            'transfer_id' => $stockTransferId,
                            'item_id' => $items[$i],
                            'qty' => $qtys[$i],
                        ]
                    );

                    $stockTransferDetailId = $stockTransferDetail->id;
                    $subSerials = explode(",", $serials[$i]);

                    // creating stock transfer serial records for the stock transfer detail, only if the item type is 0
                    if ($item_types[$i] == 1) {

                        for ($j = 0; $j < count($subSerials); $j++) {

                            $serialRecord = Serial::where('serial_no', $subSerials[$j])->first();
                            $itemCode = Item::where('id', $items[$i])->value('item_no');

                            if ($serialRecord == null) {

                                $sourceStock = Stock::where('item_id', $items[$i])->where('location_id', $stockTransferSourceLocation)->first();

                                if ($sourceStock == null) {
                                    $sourceStock = Stock::create([
                                        'location_id' => $stockTransferSourceLocation,
                                        'item_id' => $items[$i],
                                        'qty' => 0,
                                        'balance' => 0,
                                        'status' => 1,
                                    ]);
                                }

                                Serial::create([
                                    'serial_no' => $subSerials[$j],
                                    'item_id' => $items[$i],
                                    'stock_id' => $sourceStock->id,
                                    'status' => 1,
                                    'item_no' => $itemCode
                                ]);

                                $sourceStock->increment('qty');
                            }


                            StockTransferSerial::create(
                                [
                                    'transfer_id' => $stockTransferId,
                                    'transfer_details_id' => $stockTransferDetailId,
                                    'item_id' => $items[$i],
                                    'serial_no' => $subSerials[$j],
                                    'status' => 0
                                ]
                            );
                        }
                    }
                }
            }

            if ($trashedRecordIds) {
                // soft deleting stock transfers that were removed in the edit
                foreach ($trashedRecordIds as $trashedRecordId) {
                    $trDetail = StockTransferDetails::find($trashedRecordId);
                    $trDetail->delete();
                }
            }

            return response()->json(['status' => 200, 'message' => 'Transfer order updated successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Transfer order update failed', 'error' => $e->getMessage()]);
        }
    }

    // receive all : run the receival process on all
    public function receiveAll()
    {
        $user = Auth::id();

        try {

            // Update all the dispatched transfer orders the transfer order

            $dispatchedTransfers = StockTransfer::where('status', 3)->get();

            foreach ($dispatchedTransfers as $transfer) {

                $transfer->status = 4;
                $transfer->received_by = $user;
                $transfer->save();

                // Retrieving items and quantities
                $transferDetailsRecordList = StockTransferDetails::where('transfer_id', $transfer->id)->get();

                $transferStocks = [];

                foreach ($transferDetailsRecordList as $transferDetailRecord) {

                    $transferSerialList = StockTransferSerial::where('transfer_details_id', $transferDetailRecord->id)->pluck('serial_no')->toArray();
                    $transferSerialList = ($transferSerialList == null) ? ['N/A'] : $transferSerialList;

                    array_push($transferStocks, array($transferDetailRecord->item_id, $transferDetailRecord->qty, $transferSerialList, $transferDetailRecord->id));
                }

                // Deduct, add stock and change serial locations
                foreach ($transferStocks as $transferStock) {

                    // $sourceStock = Stock::where('location_id', $transfer->from_location_id)->where('item_id', $transferStock[0])->firstOrFail();
                    $targetStock = Stock::where('location_id', $transfer->to_location_id)->where('item_id', $transferStock[0])->first();

                    if ($targetStock == null) {
                        $targetStock = Stock::create([
                            'location_id' => $transfer->to_location_id,
                            'item_id' => $transferStock[0],
                            'qty' => $transferStock[1],
                            'balance' => 0,
                            'status' => 1
                        ]);
                    } else {
                        // $sourceStock->qty = $sourceStock->qty - $transferStock[1];
                        $targetStock->qty = $targetStock->qty + $transferStock[1];

                        // $sourceStock->save();
                        $targetStock->save();
                    }



                    ///stock movement from location history
                    StockMovementHistory::create([
                        'location_id' => $transfer->from_location_id,
                        'item_id' => $transferStock[0],
                        'qty' => - ($transferStock[1]),
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user,
                        'type' => 'TR'
                    ]);

                    ///stock movement to location history
                    StockMovementHistory::create([
                        'location_id' => $transfer->to_location_id,
                        'item_id' => $transferStock[0],
                        'qty' => $transferStock[1],
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user,
                        'type' => 'TR'
                    ]);


                    // foreach ($transferStock[2] as $serial) {
                    //     if ($serial != 'N/A') {
                    //         $serialEntry = Serial::where('serial_no', $serial)->first();
                    //         $serialEntry->stock_id = $targetStock->id;
                    //         $serialEntry->location_id = $transfer->to_location_id;
                    //         $serialEntry->save();

                    //         $serialTransferEntry = StockTransferSerial::where('serial_no', $serial)->where('transfer_details_id', $transferStock[3])->first();
                    //         $serialTransferEntry->status = 1;
                    //         $serialTransferEntry->save();

                    //          ///serial movement to location history
                    //         SerialMevementHistory::create([
                    //             'serial_no' => $serial,
                    //             'item_id' => $transferStock[0],
                    //             'user_id' => $user,
                    //             'location_id' => $transfer->to_location_id,
                    //             'type' => 'TR'
                    //         ]);
                    //     }
                    // }
                }
            }

            return response()->json(['status' => 200, 'message' => 'Transfer order receival success']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Transfer order receival failed', 'error' => $e->getMessage()]);
        }
    }

    // updates stock transfer record as received and change stock qtys
    public function receive()
    {
        $user = Auth::id();
        $transferId = request('transfer');
        $transfer = StockTransfer::find($transferId);
        if ($transfer->status == 3) {

            try {

                DB::beginTransaction();
                // Update the transfer order
                $transfer->status = 4;
                $transfer->received_by = $user;
                $transfer->save();

                // Retrieving items and quantities
                $transferDetailsRecordList = StockTransferDetails::where('transfer_id', $transferId)->get();

                $transferStocks = [];

                foreach ($transferDetailsRecordList as $transferDetailRecord) {

                    $transferSerialList = StockTransferSerial::where('transfer_details_id', $transferDetailRecord->id)->pluck('serial_no')->toArray();
                    $transferSerialList = ($transferSerialList == null) ? ['N/A'] : $transferSerialList;

                    array_push($transferStocks, array($transferDetailRecord->item_id, $transferDetailRecord->qty, $transferSerialList, $transferDetailRecord->id));
                }

                // Deduct, add stock and change serial locations
                foreach ($transferStocks as $transferStock) {

                    // $sourceStock = Stock::where('location_id', $transfer->from_location_id)->where('item_id', $transferStock[0])->firstOrFail();
                    $targetStock = Stock::where('location_id', $transfer->to_location_id)->where('item_id', $transferStock[0])->first();

                    if ($targetStock == null) {
                        $targetStock = Stock::create([
                            'location_id' => $transfer->to_location_id,
                            'item_id' => $transferStock[0],
                            'qty' => $transferStock[1],
                            'balance' => 0,
                            'status' => 1
                        ]);
                    } else {
                        // $sourceStock->qty = $sourceStock->qty - $transferStock[1];
                        $targetStock->qty = $targetStock->qty + $transferStock[1];

                        // $sourceStock->save();
                        $targetStock->save();
                    }



                    ///stock movement from location history
                    StockMovementHistory::create([
                        'location_id' => $transfer->from_location_id,
                        'item_id' => $transferStock[0],
                        'qty' => - ($transferStock[1]),
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user,
                        'type' => 'TR'
                    ]);

                    ///stock movement to location history
                    StockMovementHistory::create([
                        'location_id' => $transfer->to_location_id,
                        'item_id' => $transferStock[0],
                        'qty' => $transferStock[1],
                        'balance' => 0,
                        'status' => 1,
                        'user_id' => $user,
                        'type' => 'TR'
                    ]);


                    // foreach ($transferStock[2] as $serial) {
                    //     if ($serial != 'N/A') {
                    //         $serialEntry = Serial::where('serial_no', $serial)->first();
                    //         $serialEntry->stock_id = $targetStock->id;
                    //         $serialEntry->location_id = $transfer->to_location_id;
                    //         $serialEntry->save();

                    //         $serialTransferEntry = StockTransferSerial::where('serial_no', $serial)->where('transfer_details_id', $transferStock[3])->first();
                    //         $serialTransferEntry->status = 1;
                    //         $serialTransferEntry->save();

                    //          ///serial movement to location history
                    //         SerialMevementHistory::create([
                    //             'serial_no' => $serial,
                    //             'item_id' => $transferStock[0],
                    //             'user_id' => $user,
                    //             'location_id' => $transfer->to_location_id,
                    //             'type' => 'TR'
                    //         ]);
                    //     }
                    // }
                }
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Transfer order receival success']);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'Transfer order receival failed', 'error' => $e->getMessage()]);
            }
        } else {
            // Transfer stock status is equal to 3, return an appropriate response
            return response()->json(['status' => 400, 'message' => 'Transfer order receival failed. Transfer stock status is equal to 3.']);
        }
    }

    public function tr_status_update()
    {

        try {
            $id = request('id');
            $status = request('status');
            $stocktransfer = StockTransfer::where('id', $id)->first();
            $stocktransfer->status = $status;
            $stocktransfer->save();

            // $orderhistory = Order::where('id', $id)->first();
            // $orderhistory->status = $status;
            // $orderhistory->save();

            return response()->json(['status' => 200, 'message' => 'TR Status changed successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'TR Status changed failed', 'error' => $e->getMessage()]);
        }
    }
}
