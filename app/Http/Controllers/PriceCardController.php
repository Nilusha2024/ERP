<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PriceCard;
use Exception;
use Illuminate\Http\Request;

class PriceCardController extends Controller
{

    function index()
    {
        $itemlist = Item::get();
        return view('price-card')->with(['itemlist' => $itemlist]);
    }

    public function details()
    {
        $item_id = request('item_id');
        $item_code = request('item_code');
        $priceCards = PriceCard::where('item_id', $item_id)->orderBy('price')->get();

        return view('price-card-details')->with(['priceCards' => $priceCards, 'item' => $item_code]);
    }


    public function create()
    {
        $items = request('items');
        $item_types = request('item_types');
        $prices = request('prices');

        try {
            for ($i = 0; $i < count($items); $i++) {
                PriceCard::create(
                    [
                        'item_id' => $items[$i],
                        'item_type' => $item_types[$i],
                        'price' => $prices[$i],
                        'status' => 1
                    ]
                );
            }
            return response()->json(['status' => 200, 'message' => 'Price cards submitted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Price card submission failed', 'error' => $e->getMessage()]);
        }
    }

    function getPriceCardsForItem()
    {
        $item = request('item');
        $priceCards = PriceCard::where('item_id', $item)
        // ->where('item_type', 1)
        ->orderBy('price')->get();

        return response()->json(array('priceCards' => $priceCards), 200);
    }
}
