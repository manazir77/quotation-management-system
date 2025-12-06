<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);
        
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $items = Item::where('user_id', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->with('category')
            ->take(10)
            ->get();

        return response()->json($items);
    }
}

