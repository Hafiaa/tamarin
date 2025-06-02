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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options(Reservation::getStatusOptions())
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\Section::make('Event Details')
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
                    ])->columns(3),
                    
                Forms\Components\Section::make('Event Date & Time')
                    ->schema([
                        Forms\Components\DatePicker::make('event_date')
                            ->required(),
                        Forms\Components\TimePicker::make('event_time')
                            ->required(),
                        Forms\Components\TextInput::make('guest_count')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('bride_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('groom_name')
                            ->maxLength(255),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        Forms\Components\TextInput::make('estimated_revenue')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Customer Notes')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable()
                    ->description(fn (Reservation $record) => $record->event_time?->format('H:i')),
                    
                Tables\Columns\TextColumn::make('eventType.name')
                    ->label('Event Type')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('guest_count')
                    ->label('Guests')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Reservation::STATUS_APPROVED => 'success',
                        Reservation::STATUS_PENDING => 'warning',
                        Reservation::STATUS_DECLINED => 'danger',
                        Reservation::STATUS_CANCELLED => 'gray',
                        Reservation::STATUS_COMPLETED => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state)))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked On')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(Reservation::getStatusOptions())
                    ->multiple(),
                    
                SelectFilter::make('event_type_id')
                    ->relationship('eventType', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\Filter::make('event_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('event_date', '<=', $date),
                            );
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Reservation $record, array $data) {
                        $record->approve(auth()->id());
                        Notification::make()
                            ->title('Reservation approved successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Reservation')
                    ->modalDescription('Are you sure you want to approve this reservation?')
                    ->modalSubmitActionLabel('Yes, approve')
                    ->visible(fn (Reservation $record): bool => $record->isPending())
                    ->button(),
                    
                Action::make('decline')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason for declining')
                            ->required(),
                    ])
                    ->action(function (Reservation $record, array $data) {
                        $record->decline(auth()->id(), $data['reason']);
                        Notification::make()
                            ->title('Reservation declined')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Decline Reservation')
                    ->modalDescription('Please provide a reason for declining this reservation.')
                    ->modalSubmitActionLabel('Yes, decline')
                    ->visible(fn (Reservation $record): bool => $record->isPending())
                    ->button(),
                    
                Action::make('cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('gray')
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason for cancellation')
                            ->required(),
                    ])
                    ->action(function (Reservation $record, array $data) {
                        $record->cancel(auth()->id(), $data['reason']);
                        Notification::make()
                            ->title('Reservation cancelled')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Reservation')
                    ->modalDescription('Please provide a reason for cancelling this reservation.')
                    ->modalSubmitActionLabel('Yes, cancel')
                    ->visible(fn (Reservation $record): bool => in_array($record->status, [
                        Reservation::STATUS_PENDING,
                        Reservation::STATUS_APPROVED
                    ]))
                    ->button(),
                    
                Action::make('complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->action(function (Reservation $record) {
                        $record->complete();
                        Notification::make()
                            ->title('Reservation marked as completed')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Completed')
                    ->modalDescription('Are you sure you want to mark this reservation as completed?')
                    ->modalSubmitActionLabel('Yes, mark as completed')
                    ->visible(fn (Reservation $record): bool => $record->isApproved())
                    ->button(),
                    
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
