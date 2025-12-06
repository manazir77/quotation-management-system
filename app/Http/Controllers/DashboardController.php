<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'customers' => Customer::where('user_id', $user->id)->count(),
            'items' => Item::where('user_id', $user->id)->count(),
            'quotations' => Quotation::where('user_id', $user->id)->count(),
            'total_value' => Quotation::where('user_id', $user->id)->sum('total_amount'),
        ];

        $recentQuotations = Quotation::where('user_id', $user->id)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentQuotations'));
    }
}

