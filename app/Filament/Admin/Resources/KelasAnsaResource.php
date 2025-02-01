<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Program;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\KelasAnsa;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\KelasAnsaResource\Pages;
use App\Filament\Admin\Resources\KelasAnsaResource\RelationManagers;

class KelasAnsaResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kelas Ansa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detail Kelas')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->image()
                            ->collection('program-thumbnail'),
                        Forms\Components\Select::make('mentors')
                            ->relationship('mentors', 'name', fn(Builder $query) => $query->whereHas('roles', fn($query) => $query->where('name', 'mentor')))
                            ->required()
                            ->multiple()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(function(Model $record) {
                                return $record->name . ' (' . ($record->custom_fields['bidang_mentor'] ?? ($record->custom_fields ?? null)['bidang_mentor'] ?? 'Mentor') . ')';
                            })
                            ->searchable(),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                        ]),
                        Forms\Components\Fieldset::make('Jadwal & Kuota')
                        ->columns(1)
                        ->relationship('kelasAnsaDetail')
                        ->schema([
                            Forms\Components\TextInput::make('kuota')
                                ->label('Kuota Peserta')
                                ->required()
                                ->numeric()
                                ->minValue(1),
                            Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_open_registrasi')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('waktu_close_registrasi')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DatePicker::make('waktu_mulai')
                                    ->required(),
                                Forms\Components\DatePicker::make('waktu_selesai')
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('link_meet')
                            ->required()
                            ->url(),
                    ]),
                Forms\Components\Fieldset::make('Pricing Kelas')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Repeater::make('Paket Kelas')
                            ->relationship('kelasAnsaPakets')
                            ->required()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required(),
                                Forms\Components\TextInput::make('harga')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->prefix('Rp')
                                    ->suffix(',00'),
                                Forms\Components\Checkbox::make('is_ready_asset')
                                    ->label('Apakah terdapat asset digital sebagai benefit?')
                                    ->live()
                                    ->formatStateUsing(function(string $operation, $record) {
                                        if ($operation === 'edit') {
                                            return $record->link_resource !== null;
                                        }
                                        return false; // Default unchecked for create operation
                                    }),
                                Forms\Components\TextInput::make('link_resource')
                                    ->url()
                                    ->required(fn(Get $get) => $get('is_ready_asset'))
                                    ->visible(fn(Get $get) => $get('is_ready_asset'))
                                    ->live()
                                    ->label('Link Asset (slide, materi, ebook, dll)'),
                                ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Program::query()->with(['media', 'kelasAnsaPakets', 'mentors'])->whereProgram('Kelas ANSA'))
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('program-thumbnail'),
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
            'index' => Pages\ManageKelasAnsas::route('/'),
        ];
    }
}
