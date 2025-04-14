<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    // Admin dashboard - itemek kilistázása
    public function admin(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        if (!$isAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $perPage = $request->input('per_page', 24);
        $items = Item::paginate($perPage);

        return view('admin', compact('items'));
    }

    // Admin dashboard - itemek kilistázása kereséssel
    public function adminSearch(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        if (!$isAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $searchQuery = $request->query('query');
        $perPage = $request->input('per_page', 24);
        $items = Item::where('name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('description', 'like', '%' . $searchQuery . '%')
                        ->orWhere('category', 'like', '%' . $searchQuery . '%')
                        ->paginate($perPage);

        return view('admin', compact('items', 'searchQuery'));
    }

    // Admin dashboard - item létrehozása
    public function store(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        if (!$isAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'discount' => 'required|integer',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|string|max:255',
            'similar' => 'string|nullable',
            'sizes' => 'string|nullable',
            'status' => 'boolean',
            'fragile' => 'boolean',
        ]);

        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount' => $request->discount,
            'category' => $request->category,
            'similar' => $request->similar,
            'sizes' => $request->sizes,
            'status' => $request->status,
            'fragile' => $request->fragile,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin')->with('success', 'Item added.');
    }

    // Admin dashboard - item adatainak frissítése
    public function update(Request $request, Item $item)
    {
        $isAdmin = Auth::user()->isAdmin();
        if (!$isAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'discount' => 'integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|string|max:255',
            'similar' => 'string|nullable',
            'sizes' => 'string|nullable',
            'status' => 'boolean',
            'fragile' => 'boolean',
        ]);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount' => $request->discount,
            'category' => $request->category,
            'similar' => $request->similar,
            'sizes' => $request->sizes,
            'status' => $request->status,
            'fragile' => $request->fragile,
        ]);


        if ($request->filled('deleted_images')) {
            $deletedImages = explode(',', $request->input('deleted_images'));
            foreach ($deletedImages as $imageId) {
                $image = ItemImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin')->with('success', 'Item updated.');
    }

    // Archive - kiválasztott elem archiválása
    public function archive(Item $item)
    {
        if ($item->status == 0) {
            $item->update(['status' => 1]);
        }
        else {
            $item->update(['status' => 0]);
        }

        return redirect()->route('admin')->with('success', 'Item status changed.');
    }

    // Admin dashboard - item módosítása
    public function edit(Item $item)
    {
        $isAdmin = Auth::user()->isAdmin();
        if (!$isAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        return view('admin.items.edit', compact('item'));
    }

    // Admin dashboard - userek kilistázása
    public function users(Request $request)
    {
        $isSuperAdmin = Auth::user()->isSuperAdmin();
        if (!$isSuperAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $perPage = $request->input('per_page', 24);
        $users = User::select('id', 'email', 'role', 'status')
                        ->paginate($perPage);

        return view('admin', compact('users'));
    }

    // Admin dashboard - userek kilistázása kereséssel
    public function searchUsers(Request $request)
    {
        $isSuperAdmin = Auth::user()->isSuperAdmin();
        if (!$isSuperAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $searchQuery = $request->query('query');
        $perPage = $request->input('per_page', 24);
        $users = User::select('id', 'email', 'role', 'status')
                     ->where('email', 'like', '%' . $searchQuery . '%')
                     ->paginate($perPage);

        return view('admin', compact('users', 'searchQuery'));
    }

    // Admin dashboard - user archiválása
    public function toggleArchiveUser(User $user)
    {
        $isSuperAdmin = Auth::user()->isSuperAdmin();
        if (!$isSuperAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        if ($user->status === 1) {
            $user->status = 0;
        }
        elseif ($user->status === 0) {
            $user->status = 1;
        }
        else {
            return redirect()->route('admin.users')->with('error', 'Failed to update.');
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    // Admin dashboard - user jogosultság módosítás
    public function toggleRole(User $user)
    {
        $isSuperAdmin = Auth::user()->isSuperAdmin();
        if (!$isSuperAdmin) {
            return redirect()->route('/')->with('error', 'You are not autorized.');
        }

        $roles = ['user', 'admin', 'superadmin'];
        $role = array_search($user->role, $roles);
        $new = ($role + 1) % count($roles);
        $user->role = $roles[$new];
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated.');
    }
}
