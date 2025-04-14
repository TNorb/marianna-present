<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Item kosárhoz adása
    public function add(Request $request, $itemId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $item = Item::where('id', $itemId)->first();
        if ($item->sizes) {
            $request->validate([
                'selected_size' => 'required|string']);
        }

        if ($item->status == 0) {
            return back()->with('error', 'Item is not available.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $itemIsIn = CartItem::where('cart_id', $cart->id)
                            ->where('item_id', $itemId)
                            ->where('size', $request->input('selected_size'))
                            ->first();

        if ($itemIsIn) {
            if($itemIsIn->quantity >= $item->stock) {
                return back()->with('error', 'Not enough stock.');
            }
            $itemIsIn->quantity += 1;
            $itemIsIn->save();
        } else {
            if ($item->stock < 1) {
                return back()->with('error', 'Not enough stock.');
            }
            CartItem::create([
                'cart_id' => $cart->id,
                'item_id' => $itemId,
                'quantity' => 1,
                'size' => $request->input('selected_size'),
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    // Kosár - itemek megjelenítésével
    public function cart()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        Cart::firstOrCreate(['user_id' => $user->id]);
        $cart = Cart::with('items.item')->where('user_id', $user->id)->first();
        $total = 0;
        $discountedTotal = 0;

        foreach ($cart->items as $cartItem) {
            if (!$cartItem->item) {
                continue;
            }

            $availableSizes = explode(',', $cartItem->item->sizes);

            if ($cartItem->size != null && !in_array($cartItem->size, $availableSizes)) {
                continue;
            }

            $total += intval($cartItem->item->price * $cartItem->quantity);
            $discountedTotal += intval($cartItem->item->price / 5 * 5 * (1 - $cartItem->item->discount / 100)) * $cartItem->quantity;
        }
        return view('cart', compact('cart', 'total', 'discountedTotal'));
    }

    // Kosár - item törlése
    public function remove($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed.');
    }

    // Kosár - item mennyiségének frissítése
    public function update(Request $request, $itemId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $cart = Cart::where('user_id', $user->id)->first();
        $cartItem = $cart->items()->where('id', $itemId)->first();
        $item = Item::where('id', $cartItem->item_id)->first();
        $quantity = $request->input('quantity');

        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Cart not found.');
        }

        if (!$cartItem) {
            return redirect()->route('cart')->with('error', 'Item not found.');
        }

        if ($quantity < 1) {
            return back()->with('error', 'Minimum is 1.');
        }

        if ($quantity > 20) {
            return back()->with('error', 'Limit is 20.');
        }

        if ($quantity < $cartItem->quantity) {
            $cartItem->update(['quantity' => $quantity]);
            return redirect()->route('cart')->with('success', 'Cart updated.');
        }

        if ($quantity > $item->stock) {
            return back()->with('error', 'Not enough stock.');
        }


        $cartItem->update(['quantity' => $quantity]);

        return redirect()->route('cart')->with('success', 'Cart updated.');
    }
}
