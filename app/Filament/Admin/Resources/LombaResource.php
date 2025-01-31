<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Lomba;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\LombaResource\Pages;
use App\Filament\Admin\Resources\LombaResource\RelationManagers;

class LombaResource extends Resource
{
    protected static ?string $model = Lomba::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Lomba';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detail Lomba')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->collection('lomba-thumbnail'),
                        Forms\Components\TextInput::make('penyelenggara')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make('Waktu & Link')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('waktu_open_registrasi')
                            ->required()
                            ->locale('ID'),
                        Forms\Components\DateTimePicker::make('waktu_close_registrasi')
                            ->locale('ID')
                            ->required(),
                        Forms\Components\DateTimePicker::make('waktu_mulai')
                            ->required()
                            ->locale('ID'),
                            Forms\Components\DateTimePicker::make('waktu_selesai')
                            ->required(fn(Get $get) => !$get('waktu_selesai_sama_dengan_waktu_mulai'))
                            ->live()
                            ->visible(fn(Get $get) => !$get('waktu_selesai_sama_dengan_waktu_mulai'))
                            ->locale('ID'),
                        
                        Forms\Components\Checkbox::make('waktu_selesai_sama_dengan_waktu_mulai')
                            ->label('Waktu Selesai sama dengan Waktu Mulai')
                            ->live()
                            ->formatStateUsing(function (string $operation, $record) {
                                if ($operation === 'edit') {
                                    return $record?->waktu_selesai === null;
                                }
                                return false; // Default unchecked for create operation
                            }),
                        Forms\Components\TextInput::make('link_pendaftaran')
                            ->required()
                            ->url()
                            ->columnSpanFull(),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('judul')
                ->searchable(),
            Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                ->collection('lomba-thumbnail'),
            Tables\Columns\TextColumn::make('penyelenggara')
                ->searchable(),
            Tables\Columns\TextColumn::make('status_pendaftaran')
                ->label('Status Pendafataran')
                ->badge()
                ->getStateUsing(fn(Lomba $lomba) => Carbon::now()->between($lomba->waktu_open_registrasi, $lomba->waktu_close_registrasi) ? 'Buka' : 'Tutup')
                ->color(fn($state) => $state === 'Buka' ? 'success' : 'danger'),
            Tables\Columns\TextColumn::make('status_pelaksanaan')
                ->label('Status Pelaksanaan')
                ->badge()
                ->getStateUsing(function(Lomba $lomba)
                {
                    if($lomba->waktu_selesai !== null)
                    {
                        return Carbon::now()->between($lomba->waktu_mulai, $lomba->waktu_selesai) ? 'Sedang Berlangsung' : 'Selesai';
                    }

                    return Carbon::now()->isBefore($lomba->waktu_mulai) ? 'Belum Mulai' : 'Sedang Berlangsung';
                })
                ->color(fn($state) => $state === 'Belum Mulai' ? 'danger' : ($state === 'Sedang Berlangsung' ? 'warning' : 'success')),
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
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function(array $data)
                {
                    $data['waktu_selesai_sama_dengan_waktu_mulai'] === true ? $data['waktu_selesai'] = null : $data['waktu_selesai'];
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLombas::route('/'),
        ];
    }
}
