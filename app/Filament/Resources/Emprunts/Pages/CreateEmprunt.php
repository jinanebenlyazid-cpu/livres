<?php

namespace App\Filament\Resources\Emprunts\Pages;

use App\Filament\Resources\Emprunts\EmpruntResource;
use App\Models\Livre;
use App\Models\User;
use App\Services\BorrowingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmprunt extends CreateRecord
{
    protected static string $resource = EmpruntResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(BorrowingService::class)->borrow(
            User::findOrFail($data['user_id']),
            Livre::findOrFail($data['livre_id']),
            $data['date_retour_prevue'] ?? null
        );
    }
}
