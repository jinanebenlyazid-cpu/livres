<?php

namespace App\Filament\Resources\Livres\Pages;

use App\Filament\Resources\Livres\LivreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLivres extends ListRecords
{
    protected static string $resource = LivreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
