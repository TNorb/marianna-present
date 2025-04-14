<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class CategoryController extends Controller
{
    // Kategóriák - itemek kategóriáinak megjelenítése
    public function categories()
    {
        $categories = Item::where('status', 1)
                            ->pluck('category') // Az összes kategória mező lekérése
                            ->flatMap(fn($category) => explode(', ', $category)) // vessző menti szétszedés
                            ->unique() // ismétlődés szűrés
                            ->sort() // rendezés
                            ->toArray(); // tömbbé alakítás

        return view('categories', compact('categories'));
    }
    // Kategória - kategóriák szerinti itemek szűrése
    public function search(Request $request, $category)
    {
        $searchQuery = $category;
        $perPage = $request->input('per_page', 24);
        $items = Item::where('category', 'like', '%' . $category . '%')
                     ->where('status', 1)
                     ->paginate($perPage);

        return view('search', compact('items', 'searchQuery'));
    }
}
