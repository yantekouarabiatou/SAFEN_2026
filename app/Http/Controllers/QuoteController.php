<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Affiche la liste des devis du client.
     */
    public function index()
    {
        $quotes = Quote::where('user_id', auth()->id())
            ->with('artisan.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Affiche le formulaire de création d'un devis.
     */
    public function create(Request $request)
    {
        // Récupère tous les artisans avec leur utilisateur associé
        $artisans = Artisan::with('user')->get();

        // Artisan pré-sélectionné (si fourni dans l'URL)
        $selectedArtisanId = $request->query('artisan_id');

        return view('quotes.create', compact('artisans', 'selectedArtisanId'));
    }

    /**
     * Enregistre un nouveau devis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'artisan_id'   => 'required|exists:artisans,id',
            'product_id'   => 'nullable|exists:products,id',
            'subject'      => 'required|string|max:255',
            'description'  => 'required|string',
            'budget'       => 'nullable|numeric|min:0',
            'desired_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Quote::create($validated);

        return redirect()->route('client.quotes.index')
            ->with('success', 'Votre demande de devis a été envoyée avec succès.');
    }

    /**
     * Affiche les détails d'un devis.
     */
    public function show(Quote $quote)
    {
        // Vérifie que le devis appartient bien à l'utilisateur connecté
        if ($quote->user_id !== auth()->id()) {
            abort(403);
        }

        return view('quotes.show', compact('quote'));
    }

    /**
     * Affiche le formulaire d'édition (si vous souhaitez permettre la modification).
     */
    public function edit(Quote $quote)
    {
        if ($quote->user_id !== auth()->id()) {
            abort(403);
        }

        // On peut restreindre l'édition uniquement aux devis en attente
        if ($quote->status !== 'pending') {
            return redirect()->route('client.quotes.index')
                ->with('error', 'Vous ne pouvez modifier que les devis en attente.');
        }

        $artisans = Artisan::with('user')->get();

        return view('client.quotes.edit', compact('quote', 'artisans'));
    }

    /**
     * Met à jour un devis.
     */
    public function update(Request $request, Quote $quote)
    {
        if ($quote->user_id !== auth()->id()) {
            abort(403);
        }

        if ($quote->status !== 'pending') {
            return redirect()->route('client.quotes.index')
                ->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'artisan_id'   => 'required|exists:artisans,id',
            'subject'      => 'required|string|max:255',
            'description'  => 'required|string',
            'budget'       => 'nullable|numeric|min:0',
            'desired_date' => 'nullable|date',
        ]);

        $quote->update($validated);

        return redirect()->route('client.quotes.index')
            ->with('success', 'Devis mis à jour avec succès.');
    }

    /**
     * Supprime un devis (si nécessaire).
     */
    public function destroy(Quote $quote)
    {
        if ($quote->user_id !== auth()->id()) {
            abort(403);
        }

        // On peut autoriser la suppression seulement si le devis est encore en attente
        if ($quote->status !== 'pending') {
            return redirect()->route('client.quotes.index')
                ->with('error', 'Vous ne pouvez supprimer que les devis en attente.');
        }

        $quote->delete();

        return redirect()->route('client.quotes.index')
            ->with('success', 'Devis supprimé.');
    }
}
