<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmpruntController extends Controller
{
    public function index(Request $request): View
    {
        $emprunts = $request->user()
            ->emprunts()
            ->with('livre.category')
            ->latest()
            ->paginate(10);

        return view('mesEmprunts', compact('emprunts'));
    }

    public function create(Request $request): View
    {
        return view('emprunts.create', [
            'livres' => Livre::where('statut', Livre::STATUT_DISPONIBLE)
                ->where('nombre_exemplaires', '>', 0)
                ->orderBy('titre')
                ->get(),
            'membres' => $request->user()->isAdmin()
                ? User::orderBy('name')->get()
                : collect(),
            'statuts' => [
                Emprunt::STATUT_EN_COURS,
                Emprunt::STATUT_RETOURNE,
                Emprunt::STATUT_EN_RETARD,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $statuts = [
            Emprunt::STATUT_EN_COURS,
            Emprunt::STATUT_RETOURNE,
            Emprunt::STATUT_EN_RETARD,
        ];

        $rules = [
            'livre_id' => ['required', 'exists:livres,id'],
            'date_emprunt' => ['required', 'date'],
            'date_retour_prevue' => ['required', 'date', 'after_or_equal:date_emprunt'],
            'statut' => ['nullable', Rule::in($statuts)],
        ];

        if ($request->user()->isAdmin()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $data = $request->validate($rules);
        $userId = $request->user()->isAdmin() ? $data['user_id'] : $request->user()->id;
        $livre = Livre::findOrFail($data['livre_id']);

        if ($this->hasActiveBorrowing((int) $userId, $livre->id)) {
            return back()
                ->withInput()
                ->with('error', 'Un emprunt en cours existe deja pour ce membre et ce livre.');
        }

        try {
            app(BorrowingService::class)->borrow(
                User::findOrFail($userId),
                $livre,
                $data['date_retour_prevue'],
                $data['date_emprunt'],
                $data['statut'] ?? Emprunt::STATUT_EN_COURS
            );
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->validator)
                ->with('error', $exception->validator->errors()->first());
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt cree avec succes.');
    }

    public function storeFromBook(Request $request, Livre $livre): RedirectResponse
    {
        $user = $request->user();

        if ($this->hasActiveBorrowing($user->id, $livre->id)) {
            return back()->with('error', 'Vous avez deja un emprunt en cours pour ce livre.');
        }

        try {
            app(BorrowingService::class)->borrow($user, $livre);
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        }

        return back()->with('success', 'Livre emprunte avec succes.');
    }

    public function destroy(Request $request, Emprunt $emprunt): RedirectResponse
    {
        abort_unless($emprunt->user_id === $request->user()->id, 403);

        app(BorrowingService::class)->delete($emprunt);

        return back()->with('success', 'Emprunt supprime.');
    }

    public function returnBook(Request $request, Emprunt $emprunt): RedirectResponse
    {
        abort_unless($emprunt->user_id === $request->user()->id, 403);

        if ($emprunt->statut !== Emprunt::STATUT_EN_COURS) {
            return back()->with('error', 'Cet emprunt est deja cloture.');
        }

        app(BorrowingService::class)->returnBook($emprunt);

        return back()->with('success', 'Livre retourne. Merci !');
    }

    private function hasActiveBorrowing(int $userId, int $livreId): bool
    {
        return Emprunt::where('user_id', $userId)
            ->where('livre_id', $livreId)
            ->where('statut', Emprunt::STATUT_EN_COURS)
            ->exists();
    }
}
