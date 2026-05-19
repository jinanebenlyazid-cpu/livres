<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
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
        $user = $request->user();

        return view('emprunts.create', [
            'livres' => Livre::where('statut', Livre::STATUT_DISPONIBLE)
                ->where('nombre_exemplaires', '>', 0)
                ->orderBy('titre')
                ->get(),
            'membres' => $user->isAdmin()
                ? User::where('role', 'membre')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $authenticatedUser = $request->user();

        $rules = [
            'livre_id' => ['required', 'exists:livres,id'],
            'date_emprunt' => ['required', 'date', 'before_or_equal:today'],
            'date_retour_prevue' => ['required', 'date', 'after_or_equal:date_emprunt'],
        ];

        if ($authenticatedUser->isAdmin()) {
            $rules['user_id'] = [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'membre')),
            ];
        }

        $data = $request->validate($rules);

        $user = $authenticatedUser->isAdmin()
            ? User::findOrFail($data['user_id'])
            : $authenticatedUser;
        $livre = Livre::findOrFail($data['livre_id']);

        if ($this->hasActiveBorrowing($user->id, $livre->id)) {
            return back()
                ->withInput()
                ->with('error', 'Un emprunt en cours existe deja pour ce livre.');
        }

        try {
            app(BorrowingService::class)->borrow(
                $user,
                $livre,
                $data['date_retour_prevue'],
                $data['date_emprunt'],
                Emprunt::STATUT_EN_COURS
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

        if (! in_array($emprunt->statut, [Emprunt::STATUT_EN_COURS, Emprunt::STATUT_EN_RETARD], true)) {
            return back()->with('error', 'Cet emprunt est deja cloture.');
        }

        app(BorrowingService::class)->returnBook($emprunt);

        return back()->with('success', 'Livre retourne. Merci !');
    }

    private function hasActiveBorrowing(int $userId, int $livreId): bool
    {
        return Emprunt::where('user_id', $userId)
            ->where('livre_id', $livreId)
            ->whereIn('statut', [Emprunt::STATUT_EN_COURS, Emprunt::STATUT_EN_RETARD])
            ->exists();
    }
}
