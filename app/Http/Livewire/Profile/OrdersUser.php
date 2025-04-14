<?php
namespace App\Http\Livewire\Profile;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrdersUser extends Component
{
    // Profil alatt lévő order megjelenítése
    public function render()
    {
        $orders = Order::where('user_id', Auth::id())->get();

        return view('profile.orders-user', ['orders' => $orders]);
    }
}