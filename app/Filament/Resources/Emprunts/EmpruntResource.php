<?php

namespace App\Filament\Resources\Emprunts;

use App\Filament\Resources\Emprunts\Pages\CreateEmprunt;
use App\Filament\Resources\Emprunts\Pages\EditEmprunt;
use App\Filament\Resources\Emprunts\Pages\ListEmprunts;
use App\Models\Emprunt;
use App\Services\BorrowingService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmpruntResource extends Resource
{
    protected static ?string $model = Emprunt::class;

    protected static ?string $navigationLabel = 'Emprunts';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('livre_id')->relationship('livre', 'titre')->searchable()->preload()->required(),
            Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            DatePicker::make('date_retour_prevue')->required()->minDate(now()),
            Select::make('statut')
                ->options([
                    Emprunt::STATUT_EN_COURS => Emprunt::STATUT_EN_COURS,
                    Emprunt::STATUT_RETOURNE => Emprunt::STATUT_RETOURNE,
                    Emprunt::STATUT_EN_RETARD => Emprunt::STATUT_EN_RETARD,
                ])
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('livre.titre')->label('Livre')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Membre')->searchable()->sortable(),
                TextColumn::make('date_emprunt')->date()->sortable(),
                TextColumn::make('date_retour_prevue')->date()->sortable(),
                TextColumn::make('statut')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('statut')->options([
                    Emprunt::STATUT_EN_COURS => Emprunt::STATUT_EN_COURS,
                    Emprunt::STATUT_RETOURNE => Emprunt::STATUT_RETOURNE,
                    Emprunt::STATUT_EN_RETARD => Emprunt::STATUT_EN_RETARD,
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->action(fn (Emprunt $record) => app(BorrowingService::class)->delete($record)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmprunts::route('/'),
            'create' => CreateEmprunt::route('/create'),
            'edit' => EditEmprunt::route('/{record}/edit'),
        ];
    }
}
