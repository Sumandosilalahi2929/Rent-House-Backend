<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Listing;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ListingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ListingResource\RelationManagers;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))->live(debounce: 250),
                Forms\Components\TextInput::make('slug')->disabled(),
                Forms\Components\Textarea::make('description')->required(),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sqft')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('wifi_speed')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('max_person')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('price_per_day')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Checkbox::make('full_suppport_available')
                    ->default(0),
                Forms\Components\Checkbox::make('gym_area_available')
                    ->default(0),
                Forms\Components\Checkbox::make('mini_cafe_available')
                    ->default(0),
                Forms\Components\Checkbox::make('cinema_available')
                    ->default(0),
                FileUpload::make('attachments')
                    ->directory('listings')
                    ->image()
                    ->openable()
                    ->multiple()->reorderable()->appendFiles()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('sqft'),
                Tables\Columns\TextColumn::make('wifi_speed'),
                Tables\Columns\TextColumn::make('max_person'),
                Tables\Columns\TextColumn::make('price_per_day')->weight(FontWeight::Bold)->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}