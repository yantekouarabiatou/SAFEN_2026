<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotes = $query->latest()->paginate(15);

        return view('admin.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote)
    {
        $quote->load(['user', 'product']);
        return view('admin.quotes.show', compact('quote'));
    }

    public function updateStatus(Request $request, Quote $quote)
    {
        $request->validate([
            'status' => 'required|in:pending,quoted,accepted,rejected',
            'quoted_price' => 'nullable|numeric|min:0',
        ]);

        $quote->update([
            'status' => $request->status,
            'quoted_price' => $request->quoted_price,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Devis mis à jour.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('admin.quotes.index')->with('success', 'Devis supprimé.');
    }
}
