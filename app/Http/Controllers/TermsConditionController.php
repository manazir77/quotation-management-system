<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsConditionController extends Controller
{
    public function index()
    {
        $terms = TermsCondition::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(10);

        return view('terms.index', compact('terms'));
    }

    public function create()
    {
        return view('terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_default' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->boolean('is_default');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        TermsCondition::create($validated);

        return redirect()->route('terms.index')
            ->with('success', 'Terms & Conditions created successfully.');
    }

    public function edit(TermsCondition $term)
    {
        $this->authorize('update', $term);
        return view('terms.edit', compact('term'));
    }

    public function update(Request $request, TermsCondition $term)
    {
        $this->authorize('update', $term);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_default' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_default'] = $request->boolean('is_default');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $term->update($validated);

        return redirect()->route('terms.index')
            ->with('success', 'Terms & Conditions updated successfully.');
    }

    public function destroy(TermsCondition $term)
    {
        $this->authorize('delete', $term);
        
        $term->delete();

        return redirect()->route('terms.index')
            ->with('success', 'Terms & Conditions deleted successfully.');
    }
}

