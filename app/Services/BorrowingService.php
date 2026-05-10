<?php

namespace App\Services;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingService
{
    public function borrow(User $user, Livre $livre, ?string $expectedReturnDate = null): Emprunt
    {
        return DB::transaction(function () use ($user, $livre, $expectedReturnDate): Emprunt {
            $book = Livre::whereKey($livre->id)->lockForUpdate()->firstOrFail();

            if (! $book->isAvailable()) {
                throw ValidationException::withMessages([
                    'livre_id' => 'Ce livre est actuellement indisponible.',
                ]);
            }

            $book->decrement('nombre_exemplaires');
            $book->refresh();

            if ($book->nombre_exemplaires === 0) {
                $book->update(['statut' => Livre::STATUT_INDISPONIBLE]);
            }

            return Emprunt::create([
                'livre_id' => $book->id,
                'user_id' => $user->id,
                'date_emprunt' => now()->toDateString(),
                'date_retour_prevue' => $expectedReturnDate ?? now()->addDays(14)->toDateString(),
                'statut' => Emprunt::STATUT_EN_COURS,
            ]);
        });
    }

    public function returnBook(Emprunt $emprunt): void
    {
        DB::transaction(function () use ($emprunt): void {
            $emprunt = Emprunt::whereKey($emprunt->id)->lockForUpdate()->firstOrFail();

            if ($emprunt->statut !== Emprunt::STATUT_EN_COURS) {
                return;
            }

            $emprunt->update(['statut' => Emprunt::STATUT_RETOURNE]);
            $this->restoreBookCopy($emprunt);
        });
    }

    public function delete(Emprunt $emprunt): void
    {
        DB::transaction(function () use ($emprunt): void {
            $emprunt = Emprunt::whereKey($emprunt->id)->lockForUpdate()->firstOrFail();

            if ($emprunt->statut === Emprunt::STATUT_EN_COURS) {
                $this->restoreBookCopy($emprunt);
            }

            $emprunt->delete();
        });
    }

    public function restoreBookCopy(Emprunt $emprunt): void
    {
        $book = Livre::whereKey($emprunt->livre_id)->lockForUpdate()->firstOrFail();
        $book->increment('nombre_exemplaires');
        $book->refresh();

        if ($book->nombre_exemplaires > 0 && $book->statut !== Livre::STATUT_DISPONIBLE) {
            $book->update(['statut' => Livre::STATUT_DISPONIBLE]);
        }
    }
}
