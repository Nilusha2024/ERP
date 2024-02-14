<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/item', [App\Http\Controllers\ItemController::class, 'index'])->name('item');

Route::get('/general', [App\Http\Controllers\HomeController::class, 'general'])->name('general');

Route::get('/location', [App\Http\Controllers\LocationController::class, 'index'])->name('location');

Route::get('/po', [App\Http\Controllers\PoController::class, 'index'])->name('po');

Route::get('/po_view_all', [App\Http\Controllers\PoController::class, 'viewAll'])->name('view_all_po');

Route::get('/po_view', [App\Http\Controllers\PoController::class, 'viewDetails'])->name('view_po_details');

Route::post('/po/create', [App\Http\Controllers\PoController::class, 'create'])->name('create_po');

Route::post('/po/approve', [App\Http\Controllers\PoController::class, 'approve'])->name('approve_po');

Route::get('/price_card', [App\Http\Controllers\PriceCardController::class, 'index'])->name('price_card');

Route::get('/price_card/for_item', [App\Http\Controllers\PriceCardController::class, 'getPriceCardsForItem'])->name('price_cards_for_item');

Route::get('/price_card/details', [App\Http\Controllers\PriceCardController::class, 'details']);

Route::post('/price_card/create', [App\Http\Controllers\PriceCardController::class, 'create'])->name('create_price_card');

Route::get('/transfer_order', [App\Http\Controllers\TransferOrderController::class, 'index'])->name('transfer_order');

Route::get('/transfer_order_view_all', [App\Http\Controllers\TransferOrderController::class, 'viewAll'])->name('view_all_transfer_orders');

Route::get('/transfer_order_view', [App\Http\Controllers\TransferOrderController::class, 'viewDetails'])->name('view_transfer_order_details');

Route::get('/transfer_order_edit', [App\Http\Controllers\TransferOrderController::class, 'edit'])->name('edit_transfer_order');

Route::get('/transfer_order_view_serials', [App\Http\Controllers\TransferOrderController::class, 'viewSerials'])->name('view_transfer_order_serials');

Route::post('/transfer_order/create', [App\Http\Controllers\TransferOrderController::class, 'create'])->name('create_transfer_order');

Route::post('/transfer_order/update', [App\Http\Controllers\TransferOrderController::class, 'update'])->name('update_transfer_order');

Route::post('/transfer_order/receive', [App\Http\Controllers\TransferOrderController::class, 'receive'])->name('receive_transfer_order');

Route::get('/locationEdit', [App\Http\Controllers\LocationController::class, 'editindex'])->name('locationEdit');

Route::post('/tr_status_update', [App\Http\Controllers\TransferOrderController::class, 'tr_status_update']); 

Route::get('/mr_tr_view', [App\Http\Controllers\MrController::class, 'mr_order_received'])->name('mr_tr_view'); 
// item

Route::post('/item', [App\Http\Controllers\ItemController::class, 'itemstore'])->name('itemstore');

Route::get('/item_mr_stock', [App\Http\Controllers\ItemController::class, 'getMRItemStock'])->name('MR_item_stock');

Route::get('/item_fix_stock', [App\Http\Controllers\ItemController::class, 'getFIXItemStock'])->name('fix_item_stock1');

Route::get('/image', [ImageController::class, 'index'])->name('image.index');

Route::post('/image', [ImageController::class, 'store'])->name('image.store');

Route::get('/itemedit-{id}', [App\Http\Controllers\ItemController::class, 'itemedit'])->name('itemedit');

Route::PUT('/itemedit-{id}', [App\Http\Controllers\ItemController::class, 'itemUpdates'])->name('itemupdate');

Route::get('/itemereturn', [App\Http\Controllers\ItemReturnController::class, 'index'])->name('itemeretur');

Route::get('/getserailnoitem', [App\Http\Controllers\ItemController::class, 'getserailnoitem'])->name('getserailnoitem');

Route::get('/getitemdetails', [App\Http\Controllers\ItemController::class, 'getitemdetails'])->name('getitemdetails');

Route::post('/savereturnitems', [App\Http\Controllers\ItemReturnController::class, 'saveReturnItems'])->name('saveReturnItems');

Route::get('/itemreturnview', [App\Http\Controllers\ItemReturnController::class, 'ReturnViewAll'])->name('ItemReturnView');

Route::get('/itemreturnviewdetails', [App\Http\Controllers\ItemReturnController::class, 'ReturnViewDetails'])->name('ItemReturnViewDetails');

Route::get('/itemstatechange', [App\Http\Controllers\ItemReturnController::class, 'changeState'])->name('changeState');

Route::post('/ir_status_update', [App\Http\Controllers\ItemReturnController::class, 'ir_status_update']);

//

Route::post('/location', [App\Http\Controllers\LocationController::class, 'locationtore'])->name('location');

Route::post('/locationupdate', [App\Http\Controllers\LocationController::class, 'locationupdatestore'])->name('locationupdate');

// itemmovement report
Route::get('/itemMovement', [App\Http\Controllers\ItemMovementController::class, 'general'])->name('itemMovement');

Route::post('/itemMovement', [App\Http\Controllers\ItemMovementController::class, 'general'])->name('itemMovement');


// itemmovement report
Route::get('/itemMovementLocationWise', [App\Http\Controllers\ItemMovementController::class, 'itemMovementLocationWise'])->name('itemMovementLocationWise');

Route::post('/itemMovementLocationWise', [App\Http\Controllers\ItemMovementController::class, 'itemMovementLocationWise'])->name('itemMovementLocationWise');


// grn page
Route::get('/grn', [App\Http\Controllers\GRNController::class, 'index'])->name('grn');

Route::post('/grn', [App\Http\Controllers\GRNController::class, 'addgrn'])->name('grn');

Route::get('/getpodetals', [App\Http\Controllers\PoController::class, 'getpodetals'])->name('getpodetals');

Route::get('/grnview', [App\Http\Controllers\GRNController::class, 'GrnView'])->name('grnview');

Route::get('/grndetail', [App\Http\Controllers\GRNController::class, 'GrnDetails'])->name('grndetail');

Route::get('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock');

Route::post('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock');

Route::get('/stock_ledger', [App\Http\Controllers\StockController::class, 'stock_ledger'])->name('stock_ledger');

Route::get('/stock_transfer_ledger', [App\Http\Controllers\StockController::class, 'stock_transfer_ledger'])->name('stock_transfer_ledger');

Route::post('/stock_transfer_ledger_filtered', [App\Http\Controllers\StockController::class, 'stock_transfer_ledger_filtered'])->name('stock_transfer_ledger_filtered');

Route::post('/stock/mr_item_stock_create', [App\Http\Controllers\StockController::class, 'createMRItemStock'])->name('create_stock_for_mr_items');

Route::get('/stock/for_location', [App\Http\Controllers\StockController::class, 'getStockForLocation'])->name('stock_for_location');

Route::get('/serial', [App\Http\Controllers\SerialController::class, 'index'])->name('serial');

Route::get('/serial/for_stock', [App\Http\Controllers\SerialController::class, 'getSerialsForStock'])->name('serials_for_stock');

Route::get('/serial/get_location', [App\Http\Controllers\SerialController::class, 'getLocationForSerial'])->name('location_for_serial');

Route::get('/return/setvalue', [App\Http\Controllers\ItemReturnController::class, 'getReturnOldSerial']);

Route::get('/serial/get_item', [App\Http\Controllers\SerialController::class, 'getItemForSerial'])->name('item_for_serial');

Route::get('/order/details', [App\Http\Controllers\OrderController::class, 'details']);

Route::get('/mrlocation', [App\Http\Controllers\OrderController::class, 'mrlocation']);

Route::get('/mrdetails', [App\Http\Controllers\OrderController::class, 'mrdetails']);

Route::get('/stock_movement_details', [App\Http\Controllers\StockController::class, 'stock_movement_details']);

Route::get('/getitemdata', [App\Http\Controllers\ItemController::class, 'getitemdata'])->name('getitemdata');

Route::get('po-pdf', [App\Http\Controllers\PDFController::class, 'generatePoPDF'])->name('generatePoPDF');

Route::get('grn-pdf', [App\Http\Controllers\PDFController::class, 'generateGRNPDF'])->name('generateGRNPDF');

Route::get('to-pdf', [App\Http\Controllers\PDFController::class, 'generateTOPDF'])->name('generateTOPDF');

Route::get('/autocomplete-search', [App\Http\Controllers\ItemReturnController::class, 'autocompleteSearch']);

Route::get('/stock-adjestment', [App\Http\Controllers\StockController::class, 'stockAdjestment'])->name('stockadjestment');

Route::get('po-print',[App\Http\Controllers\PDFController::class,'printPo'])->name('printpo');

Route::get('to-print',[App\Http\Controllers\PDFController::class,'printTo'])->name('printto');

Route::get('grn-print',[App\Http\Controllers\PDFController::class,'printGrn'])->name('printGrn');

// itemmovement report
Route::get('/serialnohistory', [App\Http\Controllers\ItemMovementController::class, 'serialnohistory'])->name('serialnohistory');

Route::post('/serialnohistory', [App\Http\Controllers\ItemMovementController::class, 'serialnohistory'])->name('serialnohistory');




// User routes
// ----------

Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user');

Route::post('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('create_user');

Route::get('/editedit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit_user');

Route::put('/user/update', [App\Http\Controllers\UserController::class, 'update'])->name('update_user');

Route::delete('/user/delete', [App\Http\Controllers\UserController::class, 'delete'])->name('delete_user');

// mr routes

Route::get('/mr', [App\Http\Controllers\MrController::class, 'index'])->name('mr');

Route::post('/createmr', [App\Http\Controllers\MrController::class, 'create'])->name('createmr');

Route::get('/mrview', [App\Http\Controllers\MrController::class, 'MrView'])->name('mrview');

Route::get('/mrdetail', [App\Http\Controllers\MrController::class, 'MrDetail'])->name('mrdetail');

Route::post('/mr_status_update', [App\Http\Controllers\MrController::class, 'mr_status_update'])->name('mr_status_update');

Route::get('/mr_order_received', [App\Http\Controllers\MrController::class, 'mr_order_received'])->name('mr_order_received');

// mr routes

Route::get('/mr', [App\Http\Controllers\MrController::class, 'index'])->name('mr');

// ----------

Route::post('/fix_stock/createone', [App\Http\Controllers\StockController::class, 'createfixitemstock'])->name('fix_item_stock');

Route::post('/stock/stock_adjustment', [App\Http\Controllers\StockController::class, 'stockAdjestmentStore']);

Route::get('/serailedit', [App\Http\Controllers\SerialController::class, 'seraileditview'])->name('seraileditview');

Route::post('/serailedit', [App\Http\Controllers\SerialController::class, 'serailedit'])->name('serailedit');

Route::get('/operationreport', [App\Http\Controllers\HomeController::class, 'operationreport'])->name('operationreport');

//////Management Dashboard

Route::get('/firstdashoard', [App\Http\Controllers\HomeController::class, 'firstdashoard'])->name('firstdashoard');


//testing
Route::post('/receive-all', [App\Http\Controllers\TransferOrderController::class, 'receiveAll'])->name('receive-all');

//check zone
Route::get('/checkzone', [App\Http\Controllers\ZoneController::class, 'showCheckZoneForm'])->name('checkzone.form');
Route::post('/checkzone', [App\Http\Controllers\ZoneController::class, 'showCheckZoneForm'])->name('checkzone.form');
Route::post('/zone-check/store', [App\Http\Controllers\ZoneController::class, 'storeZoneCheck'])->name('zone.check.store');


Auth::routes();