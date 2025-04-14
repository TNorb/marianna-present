<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Storage;


class ItemController extends Controller
{
    // Home page - összes item
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 24);
        $items = Item::where('status', 1)
                        ->paginate($perPage);

        return view('welcome', compact('items'));
    }

    // On sale - összes leárazott item
    public function onsale(Request $request)
    {
        $perPage = $request->input('per_page', 24);
        $items = Item::where('discount', '>', 0)
                        ->where('status', 1)->paginate($perPage);

        return view('welcome', compact('items'));
    }

    // Details - kiválasztott item részletei
    public function details($id)
    {
        $item = Item::findOrFail($id);

        if ($item->status == 0) {
            return redirect()->route('/')->with('error', 'Item unavailable');
        }

        $recommendeds = Item::inRandomOrder()->take(4)->get();
        $similars = Item::where('similar', $item->similar)
                            ->where('similar', '!=', null)
                            ->where('id', '!=', $item->id)
                            ->where('status', 1)
                            ->get();

        return view('item.details', compact('item', 'recommendeds', 'similars'));
    }

    // Search - megadott szó alapján keresés
    public function search(Request $request)
    {
        $searchQuery = $request->query('query');
        $perPage = $request->input('per_page', 24);
        $items = Item::where('name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('description', 'like', '%' . $searchQuery . '%')
                        ->orWhere('category', 'like', '%' . $searchQuery . '%')
                        ->where('status', 1)
                        ->paginate($perPage);

        return view('search', compact('items', 'searchQuery'));
    }
}
