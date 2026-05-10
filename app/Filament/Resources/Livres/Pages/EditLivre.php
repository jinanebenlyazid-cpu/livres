<?php

namespace App\Filament\Resources\Livres\Pages;

use App\Filament\Resources\Livres\LivreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLivre extends EditRecord
{
    protected static string $resource = LivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
