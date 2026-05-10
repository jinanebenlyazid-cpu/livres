<?php

namespace App\Filament\Resources\Livres;

use App\Filament\Resources\Livres\Pages\CreateLivre;
use App\Filament\Resources\Livres\Pages\EditLivre;
use App\Filament\Resources\Livres\Pages\ListLivres;
use App\Models\Livre;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LivreResource extends Resource
{
    protected static ?string $model = Livre::class;

    protected static ?string $recordTitleAttribute = 'titre';

    protected static ?string $navigationLabel = 'Livres';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titre')->required()->maxLength(255),
            TextInput::make('auteur')->required()->maxLength(255),
            TextInput::make('isbn')->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('image')->label('Image file')->helperText('Nom du fichier dans public/images/livres')->maxLength(255),
            TextInput::make('nombre_exemplaires')->numeric()->minValue(0)->required(),
            Select::make('statut')
                ->options([
                    Livre::STATUT_DISPONIBLE => Livre::STATUT_DISPONIBLE,
                    Livre::STATUT_INDISPONIBLE => Livre::STATUT_INDISPONIBLE,
                ])
                ->required(),
            Select::make('category_id')->relationship('category', 'nom')->searchable()->preload()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->getStateUsing(fn (Livre $record): ?string => $record->image ? asset('images/livres/'.$record->image) : null),
                TextColumn::make('titre')->searchable()->sortable(),
                TextColumn::make('auteur')->searchable()->sortable(),
                TextColumn::make('category.nom')->label('Categorie')->sortable(),
                TextColumn::make('nombre_exemplaires')->sortable(),
                TextColumn::make('statut')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')->relationship('category', 'nom')->label('Categorie'),
                SelectFilter::make('statut')->options([
                    Livre::STATUT_DISPONIBLE => Livre::STATUT_DISPONIBLE,
                    Livre::STATUT_INDISPONIBLE => Livre::STATUT_INDISPONIBLE,
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLivres::route('/'),
            'create' => CreateLivre::route('/create'),
            'edit' => EditLivre::route('/{record}/edit'),
        ];
    }
}
