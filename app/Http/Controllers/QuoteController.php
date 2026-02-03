<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Artisan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::where('user_id', Auth::id())
            ->with(['artisan.user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('quotes.index', compact('quotes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'artisan_id' => 'required|exists:artisans,id',
            'product_id' => 'nullable|exists:products,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'budget' => 'nullable|numeric|min:0',
            'desired_date' => 'nullable|date|after:today'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $quote = Quote::create($validated);

        // Notifier l'artisan (à implémenter)
        // $artisan = Artisan::find($validated['artisan_id']);
        // Envoyer notification email/WhatsApp

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Votre demande de devis a été envoyée avec succès !');
    }

    public function show(Quote $quote)
    {
        // Vérifier que l'utilisateur a accès à ce devis
        if ($quote->user_id !== Auth::id() &&
            (!$quote->artisan || $quote->artisan->user_id !== Auth::id())) {
            abort(403);
        }

        $quote->load(['artisan.user', 'product', 'user']);

        return view('quotes.show', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        // Seul l'artisan peut répondre
        if (!$quote->artisan || $quote->artisan->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'response' => 'required|string|min:10',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:responded,accepted,rejected'
        ]);

        $validated['response_date'] = now();

        $quote->update($validated);

        // Notifier le client (à implémenter)

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Réponse envoyée avec succès.');
    }

    public function accept(Quote $quote)
    {
        // Seul le client peut accepter
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Devis accepté. Vous pouvez maintenant procéder au paiement.');
    }

    public function reject(Quote $quote)
    {
        // Seul le client peut refuser
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('quotes.show', $quote)
            ->with('success', 'Devis refusé.');
    }
}
