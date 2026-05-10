<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use App\Services\BorrowingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmpruntController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Emprunt::with(['livre.category', 'user'])->latest();

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json($query->paginate(12));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'livre_id' => ['required', 'exists:livres,id'],
        ]);

        if ($request->user()->isAdmin()) {
            $data += $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'date_retour_prevue' => ['nullable', 'date', 'after_or_equal:today'],
            ]);
        }

        try {
            $emprunt = app(BorrowingService::class)->borrow(
                User::findOrFail($data['user_id'] ?? $request->user()->id),
                Livre::findOrFail($data['livre_id']),
                $data['date_retour_prevue'] ?? null
            );
        } catch (ValidationException $exception) {
            return response()->json(['message' => $exception->validator->errors()->first()], 422);
        }

        return response()->json([
            'message' => 'Emprunt cree avec succes.',
            'emprunt' => $emprunt->load(['livre.category', 'user']),
        ], 201);
    }

    public function destroy(Request $request, Emprunt $emprunt): JsonResponse
    {
        abort_unless($request->user()->isAdmin() || $emprunt->user_id === $request->user()->id, 403);

        app(BorrowingService::class)->delete($emprunt);

        return response()->json(['message' => 'Emprunt supprime.']);
    }

    public function returnBook(Request $request, Emprunt $emprunt): JsonResponse
    {
        abort_unless($request->user()->isAdmin() || $emprunt->user_id === $request->user()->id, 403);

        app(BorrowingService::class)->returnBook($emprunt);

        return response()->json(['message' => 'Livre retourne.']);
    }
}
