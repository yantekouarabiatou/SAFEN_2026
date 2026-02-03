<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        Contact::create($validated);

        return redirect()->route('contact.create')
            ->with('success', 'Votre message a été envoyé avec succès !');
    }
}
