<?php

namespace App\Filament\Widgets;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total books', Livre::count())->description('Livres dans le catalogue'),
            Stat::make('Total members', User::where('role', 'membre')->count())->description('Comptes membres'),
            Stat::make('Total emprunts', Emprunt::count())->description('Tous les emprunts'),
            Stat::make('Unavailable books', Livre::where('statut', Livre::STATUT_INDISPONIBLE)->count())->description('Livres indisponibles'),
        ];
    }
}
