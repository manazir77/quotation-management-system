<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::where('user_id', Auth::id())
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('quotations.index', compact('quotations'));
    }

    // Step 1: Select customer and set basic details
    public function create()
    {
        $customers = Customer::where('user_id', Auth::id())->orderBy('name')->get();
        return view('quotations.create', compact('customers'));
    }

    // Step 1 submission: Store basic quotation info in session
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'quotation_time' => 'required',
            'validity_date' => 'required|date|after_or_equal:quotation_date',
            'notes' => 'nullable|string',
        ]);

        // Store in session
        session(['quotation_step1' => $validated]);

        return redirect()->route('quotations.step2');
    }

    // Step 2 & 3: Display customer details and add items
    public function step2()
    {
        $step1 = session('quotation_step1');
        
        if (!$step1) {
            return redirect()->route('quotations.create')
                ->with('error', 'Please complete step 1 first.');
        }

        $customer = Customer::findOrFail($step1['customer_id']);
        $items = Item::where('user_id', Auth::id())->with('category')->get();
        $quotationItems = session('quotation_items', []);

        return view('quotations.step2', compact('customer', 'items', 'quotationItems', 'step1'));
    }

    // Add item to quotation (AJAX)
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $item = Item::findOrFail($validated['item_id']);
        
        $quotationItems = session('quotation_items', []);
        
        $basePrice = $validated['unit_price'] * $validated['quantity'];
        $gstAmount = $basePrice * ($item->gst_percentage / 100);
        
        $quotationItems[] = [
            'item_id' => $item->id,
            'item_name' => $item->name,
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'gst_percentage' => $item->gst_percentage,
            'gst_amount' => $gstAmount,
            'total_price' => $basePrice + $gstAmount,
        ];

        session(['quotation_items' => $quotationItems]);

        return response()->json([
            'success' => true,
            'items' => $quotationItems,
            'totals' => $this->calculateTotals($quotationItems),
        ]);
    }

    // Update item in quotation (AJAX)
    public function updateItem(Request $request)
    {
        $validated = $request->validate([
            'index' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $quotationItems = session('quotation_items', []);
        
        if (isset($quotationItems[$validated['index']])) {
            $item = &$quotationItems[$validated['index']];
            $item['quantity'] = $validated['quantity'];
            $item['unit_price'] = $validated['unit_price'];
            
            $basePrice = $item['unit_price'] * $item['quantity'];
            $item['gst_amount'] = $basePrice * ($item['gst_percentage'] / 100);
            $item['total_price'] = $basePrice + $item['gst_amount'];
            
            session(['quotation_items' => $quotationItems]);
        }

        return response()->json([
            'success' => true,
            'items' => $quotationItems,
            'totals' => $this->calculateTotals($quotationItems),
        ]);
    }

    // Remove item from quotation (AJAX)
    public function removeItem(Request $request)
    {
        $index = $request->input('index');
        
        $quotationItems = session('quotation_items', []);
        
        if (isset($quotationItems[$index])) {
            array_splice($quotationItems, $index, 1);
            session(['quotation_items' => $quotationItems]);
        }

        return response()->json([
            'success' => true,
            'items' => $quotationItems,
            'totals' => $this->calculateTotals($quotationItems),
        ]);
    }

    private function calculateTotals($items)
    {
        $subtotal = 0;
        $totalGst = 0;
        
        foreach ($items as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
            $totalGst += $item['gst_amount'];
        }

        return [
            'subtotal' => $subtotal,
            'total_gst' => $totalGst,
            'total_amount' => $subtotal + $totalGst,
        ];
    }

    // Step 4: Terms & Conditions
    public function step3()
    {
        $step1 = session('quotation_step1');
        $quotationItems = session('quotation_items', []);
        
        if (!$step1) {
            return redirect()->route('quotations.create')
                ->with('error', 'Please complete step 1 first.');
        }

        if (empty($quotationItems)) {
            return redirect()->route('quotations.step2')
                ->with('error', 'Please add at least one item.');
        }

        $terms = TermsCondition::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->get();

        $defaultTerms = $terms->where('is_default', true)
            ->pluck('content')
            ->implode("\n\n");

        return view('quotations.step3', compact('terms', 'defaultTerms'));
    }

    // Step 5: Save quotation
    public function store(Request $request)
    {
        $step1 = session('quotation_step1');
        $quotationItems = session('quotation_items', []);
        
        if (!$step1 || empty($quotationItems)) {
            return redirect()->route('quotations.create')
                ->with('error', 'Invalid quotation data.');
        }

        $validated = $request->validate([
            'terms_conditions' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totals = $this->calculateTotals($quotationItems);

            $quotation = Quotation::create([
                'user_id' => Auth::id(),
                'customer_id' => $step1['customer_id'],
                'quotation_number' => Quotation::generateQuotationNumber(),
                'quotation_date' => $step1['quotation_date'],
                'quotation_time' => $step1['quotation_time'],
                'validity_date' => $step1['validity_date'],
                'notes' => $step1['notes'],
                'terms_conditions' => $validated['terms_conditions'],
                'subtotal' => $totals['subtotal'],
                'total_gst' => $totals['total_gst'],
                'total_amount' => $totals['total_amount'],
                'status' => 'draft',
            ]);

            foreach ($quotationItems as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_id' => $item['item_id'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'gst_percentage' => $item['gst_percentage'],
                    'gst_amount' => $item['gst_amount'],
                    'total_price' => $item['total_price'],
                ]);
            }

            DB::commit();

            // Clear session data
            session()->forget(['quotation_step1', 'quotation_items']);

            return redirect()->route('quotations.show', $quotation)
                ->with('success', 'Quotation created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating quotation: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $this->authorize('view', $quotation);
        
        $quotation->load(['customer', 'items']);
        
        return view('quotations.show', compact('quotation'));
    }

    public function print(Quotation $quotation)
    {
        $this->authorize('view', $quotation);
        
        $quotation->load(['customer', 'items', 'user']);
        
        return view('quotations.print', compact('quotation'));
    }

    public function destroy(Quotation $quotation)
    {
        $this->authorize('delete', $quotation);
        
        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }

    // Clear session and start fresh
    public function cancel()
    {
        session()->forget(['quotation_step1', 'quotation_items']);
        return redirect()->route('quotations.index');
    }
}

