<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class OrderController extends Controller
{
    // Checkout - itemek megjelenítésével
    public function checkout()
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
            if ($cartItem->item->status == 0) {
                continue;
            }

            $availableSizes = explode(',', $cartItem->item->sizes);

            if ($cartItem->size != null && !in_array($cartItem->size, $availableSizes)) {
                continue;
            }

            $total += intval($cartItem->item->price * $cartItem->quantity);
            $discountedTotal += intval($cartItem->item->price / 5 * 5 * (1 - $cartItem->item->discount / 100)) * $cartItem->quantity;
        }

        if ($discountedTotal > 30000) {
            $shippingCost = 0;
        } else {
            $shippingCost = 3000;
        }
        $total += $shippingCost;
        $discountedTotal += $shippingCost;

        return view('checkout', compact('cart', 'total', 'discountedTotal', 'shippingCost',));
    }

    // Invoice - számla generálása
    public function generateInvoice($orderId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $order = Order::with('items.item')->find($orderId);
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'You are not authorized.');
        }

        // PDF generálása
        $pdf = Pdf::loadView('invoice', compact('order'));
        // Legenerált PDF titkosítása
        $encryptedContent = Crypt::encrypt($pdf->output());

        // PDF enc fájl mentése az invoices mappába
        Storage::put('invoices/' . $order->ref_code . '_invoice.enc', $encryptedContent);

        return back()->with('success', 'Invoice generated successfully.');
    }

    // Invoice - számla letöltése
    public function invoice($orderId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $order = Order::with('items.item')->find($orderId);
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'You are not authorized.');
        }

        // PDF enc fájl létezésének ellenőrzése
        $filePath = 'invoices/' . $order->ref_code . '_invoice.enc';

        if (!Storage::exists($filePath)) {
            return back()->with('error', 'Invoice not found.');
        }

        // PDF enc fájl bekérése
        $encryptedContent = Storage::get($filePath);

        // PDF enc fájl decryptelés megpróbálása
        try {
            $decryptedContent = Crypt::decrypt($encryptedContent);
        } catch (\Exception $e) {
            return back()->with('error', 'Contact the administrator.');
        }

        return response($decryptedContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $order->ref_code . '_invoice.pdf"');
    }

    // Kártya adatok ellenőrzés - szám, typus, cvv, lejárati idő
    private function cardNumberCheck($number, $cvv, $expMonth, $expYear)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        $currentYear = (int) date('y');
        $currentMonth = (int) date('m');

        $expYear = (int) $expYear;
        $expMonth = (int) $expMonth;

        // Kártya lejárati idő ellenőrzés - teljes lejárati időnek az érvényessége
        if (($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth))) {
            return redirect()->route('checkout')->with(['error' => 'Card expired.']);
        }
        // Kártya lejárati idő ellenőrzés - hónap valóságának ellenőrzése
        if ($expMonth < 1 || $expMonth > 12) {
            return redirect()->route('checkout')->with(['error' => 'Invalid expiration month.']);
        }
        // számok kiszűrése hogy csak az maradjon a számban
        $number = preg_replace('/\D/', '', $number);

        // kártya típusok regex szabályokkal
        $cardPatterns = [
            'Visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'MasterCard' => '/^5[1-5][0-9]{14}$/',
            'American Express' => '/^3[47][0-9]{13}$/',
            'Discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/'
        ];

        foreach ($cardPatterns as $cardType => $pattern) {
            // Kártya típus ellenőrzés - összehasonlítja a regex szabályokat hogy melyikben találja meg magát

            if (preg_match($pattern, $number)) {
                $sum = 0;
                $alt = false;
                // Luhn algoritmus - végéről indulva minden második számot dupláz - ha nagyobb 9-nél akkor -9=
                for ($i = strlen($number) - 1; $i >= 0; $i--) {
                    $digit = intval($number[$i]);
                    if ($alt) {
                        $digit *= 2;
                        if ($digit > 9) {
                            $digit -= 9;
                        }
                    }
                    $sum += $digit;
                    $alt = !$alt;
                }

                // CVV ellenőrzése - ha mastercard akkor 4 azon kívül 3 karakteresnek kell lennie
                if ($cardType === 'American Express') {
                    if (!preg_match('/^\d{4}$/', $cvv)) {
                        return redirect()->route('checkout')->with(['error' => 'Invalid CVV.']);
                    }
                } else {
                    if (!preg_match('/^\d{3}$/', $cvv)) {
                        return redirect()->route('checkout')->with(['error' => 'Invalid CVV.']);
                    }
                }
                if ($sum % 10 === 0) {
                    return $cardType;
                } else {
                    return redirect()->route('checkout')->with(['error' => 'Invalid card number.']);
                }

            }
        }
        return redirect()->route('checkout')->with(['error' => 'Invalid card details.']);
    }

    public function placeOrder(Request $request)
    {
        // User authentikálása
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        // kosár létrehozása ha nincs
        Cart::firstOrCreate(['user_id' => $user->id]);

        // kosár lekérdezése
        $cart = Cart::with('items.item')->where('user_id', $user->id)->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        /*
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'cardNumber' => 'required|string|digits_between:13,19',
            'expirationDate' => 'required|string',
            'cvv' => 'required|string|digits_between:3,4',
        ]);
        */

        // Kiválasztott automata ellenőrzése
        if ($request->selected_automata == null) {
            return back()->with('error', 'Please select a automata.');
        }

        // Expiration date ellenőrzése
        $expirationToMonthToYear = explode('/', $request->expirationDate);
        if (count($expirationToMonthToYear) !== 2) {
            return back()->with('error', 'Invalid expiration date.');
        }

        // Szétszedjük a dátumot és eltároljuk
        $expMonth = trim($expirationToMonthToYear[0]);
        $expYear = trim($expirationToMonthToYear[1]);

        // Kártyaellenőrzés - kártyaszám, cvv, lejárati hónap és lejárati év adatokkal
        $cardType = $this->cardNumberCheck($request->cardNumber, $request->cvv, $expMonth, $expYear);


        if ($cardType instanceof \Illuminate\Http\RedirectResponse) {
            return $cardType;
        }

        // Kosár - item árak összeadása, total priceok kiszámítása
        $cartPriceCheck = Cart::with('items.item')->where('user_id', $user->id)->first();
        $total = 0;
        $discountedTotal = 0;
        $fragile = 0;

        foreach ($cartPriceCheck->items as $cartItem) {
            if (!$cartItem->item) {
                continue;
            }
            if ($cartItem->item->status == 0) {
                continue;
            }

            // Eltároljuk ha törékeny - Ezt elég egyszer mert akkor az egész csomag törékenynek van nyilvánítva
            if ($fragile == 0 || $fragile == null) {
                $fragile = $cartItem->item->fragile;
            }

            // Mennyiség ellenőrzése - meghaladja a stockot vissza returnoli
            if ($cartItem->item->stock < $cartItem->quantity) {
                return back()->with('error', 'Not enough stock for: ' . $cartItem->item->name);
            }

            // explode után ellenőrzi hogy
            $availableSizes = explode(',', $cartItem->item->sizes);

            // Ha az item rendelkezik mérettel akkor ellenőzi hogy elérhető-e
            if ($cartItem->size != null && !in_array($cartItem->size, $availableSizes)) {
                return back()->with('error', 'Size is not available for:' . $cartItem->item->name);
            }

            // Total priceok kiszámítása - 5-el osztható egész árak forinthoz
            $total += intval($cartItem->item->price * $cartItem->quantity);
            $discountedTotal += intval($cartItem->item->price / 5 * 5 * (1 - $cartItem->item->discount / 100)) * $cartItem->quantity;
        }

        // 30000 forint felett ingyenes szállítás, az alatt extra 3000 az árban
        if ($discountedTotal > 30000) {
            $shippingCost = 0;
        } else {
            $shippingCost = 3000;
        }
        $total += $shippingCost;
        $discountedTotal += $shippingCost;

        // Adatbázisban lévő árat összehasonlítja a frontendről kapott árral
        if ((int) $discountedTotal != (int) $request->total_price) {
            return back()->with('error', 'Invalid total price.');
        }

        // Json dekódolása Order feltöltése
        $selectedAutomata = json_decode($request->selected_automata, true);

        // Rendelés létrehozása
        $order = new Order();
        $order->user_id = $user->id;
        $order->status = 'Pending';
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->country = $selectedAutomata['country'] ?? null;
        $order->city = $selectedAutomata['city'] ?? null;
        $order->zip = $selectedAutomata['zip'] ?? null;
        $order->province = $request->province;
        $order->address = $selectedAutomata['address'] ?? null;
        $order->billing_address = $request->zip . ' ' . $request->city . ', ' . $request->address;
        $order->total_price = (int) $request->total_price;
        $order->card_number = substr($request->cardNumber, -4);
        $order->card_type = $cardType;
        $order->delivery_note = $request->delivery_note;
        $order->operator_id = $selectedAutomata['operator_id'] ?? null;
        $order->place_id = $selectedAutomata['place_id'] ?? null;
        $order->fragile = $fragile ?? false;


        // Foxpost function meghívása a létrehozott order adatokkal
        $foxpostResponse = $this->sendToFoxpost($order);

        // Ha foxpost elfogadja akkor 201-es kódot ad vissza, minden ellenekző esetben invalid
        if ($foxpostResponse['status_code'] !== 201) {
            return back()->with('error', 'Failed to send order to Foxpost.');
        }

        // Ha nem ad vissza adatot és nem valid akkor returnol
        if (!$foxpostResponse['content'] && !$foxpostResponse['valid']) {
            return back()->with('error', 'Invalid parcel data.');
        }

        // Validálás után mentjük az ordert az adatbázisba
        $order->save();

        // A unique_barcodehoz és a ref_codehoz szükség van az order id-ra így ezt a kettőt utólag adjuk hozzá
        $order->unique_barcode = 'MARPRES' . $order->id . date('Ymd');
        $order->ref_code = 'MP' . date('Md') . Str::random(8) . $order->id;

        // Mégegyszer mentünk
        $order->save();

        // Létrehozzuk az order item itemeket amik az adott rendeléshez tartozó itemek
        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->item->price * (1 - $cartItem->item->discount / 100),
                'discount' => $cartItem->item->discount,
                'size' => $cartItem->size,
            ]);

            // levonjuk a rendelés itemeit a stockból
            $item = $cartItem->item;
            $item->stock -= $cartItem->quantity;

            // ha a stock 0-ra csökken akkor status 0 az itemnek
            if ($item->stock < 1) {
                $item->status = 0;
            }
            $item->save();
        }

        // Invoice generállása order id alapján DomPDF segítségével
        try {
            $this->generateInvoice($order->id);
        } catch (\Exception $e) {
            \Log::error('Failed to generate invoice: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice. Contact the administrator.');
        }

        // Töröljük a kosár elemeit
        $cart->items()->delete();
        return redirect()->route('cart')->with('success', 'Your order has been placed successfully.');
    }



    // Foxpost által bekért adatokat egy jsonbe küldjük nekik
    public function sendToFoxpost($order)
    {
        $data = [
            [
                'recipientName' => $order->name,
                'recipientPhone' => $order->phone,
                'recipientEmail' => $order->email,
                'destination' => $order->operator_id,
                'recipientCountry' => $order->country,
                'recipientCity' => $order->city,
                'recipientZip' => $order->zip,
                'recipientAddress' => $order->address,
                'cod' => $order->total_price,
                'deliveryNote' => $order->delivery_note,
                'fragile' => $order->fragile,
                'uniqueBarcode' => $order->unique_barcode,
                'refCode' => $order->ref_code,
                'size' => 'M',
                'comment' => 'Szuvenir',
                'isWeb' => false
            ]
        ];

        // 2 azonosítás lehetséges a foxpostnál, amennyiben van lehetőség abban az esetben operator_id-t kell megadni ha nicns akkor place_id-t kell
        if ($order->operator_id == null){
            $data[0]['place_id'] = $order->place_id;
        }
        else {
            $data[0]['operator_id'] = $order->operator_id;
        }

        // headerben az apival basicauth segítségével küldjük az adatot a foxpostnak
        $response = Http::withHeaders([
            'Api-key' => config('services.foxpost.api_key'),
            'Content-Type' => 'application/json',
        ])->withBasicAuth(
            config('services.foxpost.username'),
            config('services.foxpost.password')
        )->post('https://webapi-test.foxpost.hu/api/parcel?isWeb=false&isRedirect=false', $data);

        // válasznak megkapjuk a contentet és a status codeot
        return [
            'status_code' => $response->status(),
            'content' => $response->json(),
        ];
    }
}