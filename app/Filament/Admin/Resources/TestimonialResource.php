<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Testimoni';
    protected static ?string $pluralModelLabel = 'Testimoni';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Testimoni')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                            
                        Forms\Components\Select::make('reservation_id')
                            ->label('Reservasi (Opsional)')
                            ->relationship('reservation', 'id')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('content')
                            ->label('Isi Testimoni')
                            ->required()
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->rows(5)
                            ->helperText('Bagikan pengalaman pelanggan dengan kami')
                            ->rules(['required', 'string', 'min:10', 'max:1000']),
                            
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                '1' => '1 Bintang',
                                '2' => '2 Bintang',
                                '3' => '3 Bintang',
                                '4' => '4 Bintang',
                                '5' => '5 Bintang',
                            ])
                            ->required()
                            ->default('5')
                            ->columnSpanFull()
                            ->rules(['required', 'integer', 'min:1', 'max:5']),
                            
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending_admin_approval' => 'Menunggu Persetujuan',
                                'published' => 'Dipublikasikan',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending_admin_approval')
                            ->required()
                            ->columnSpanFull()
                            ->rules(['required', 'in:pending_admin_approval,published,rejected']),
                            
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->helperText('Tampilkan testimoni ini di bagian unggulan')
                            ->default(false)
                            ->columnSpanFull()
                            ->rules(['boolean']),
                    ])
                    ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reservation.id')
                    ->label('ID Reservasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Isi Testimoni')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending_admin_approval' => 'Menunggu Persetujuan',
                        'published' => 'Dipublikasikan',
                        'rejected' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('is_featured')
                    ->label('Unggulan')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai')
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
