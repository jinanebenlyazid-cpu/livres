<?php

namespace App\Filament\Resources\Emprunts\Pages;

use App\Filament\Resources\Emprunts\EmpruntResource;
use App\Models\Emprunt;
use App\Services\BorrowingService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmprunt extends EditRecord
{
    protected static string $resource = EmpruntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('returnBook')
                ->label('Retourner')
                ->visible(fn (Emprunt $record): bool => $record->statut === Emprunt::STATUT_EN_COURS)
                ->action(fn (Emprunt $record) => app(BorrowingService::class)->returnBook($record)),
            DeleteAction::make()->action(fn (Emprunt $record) => app(BorrowingService::class)->delete($record)),
        ];
    }
}
