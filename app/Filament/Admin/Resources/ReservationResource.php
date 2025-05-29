<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReservationResource\Pages;
use App\Filament\Admin\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('event_type_id')
                    ->relationship('eventType', 'name')
                    ->required(),
                Forms\Components\Select::make('package_template_id')
                    ->relationship('packageTemplate', 'name')
                    ->default(null),
                Forms\Components\DatePicker::make('event_date')
                    ->required(),
                Forms\Components\TextInput::make('event_time')
                    ->required(),
                Forms\Components\TextInput::make('guest_count')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('bride_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('groom_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending_admin_review'),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('admin_notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('estimated_revenue')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eventType.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageTemplate.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_time'),
                Tables\Columns\TextColumn::make('guest_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bride_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('groom_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estimated_revenue')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
