<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\EventResource\Pages;
use App\Filament\Admin\Resources\EventResource\RelationManagers;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Event';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detail Event')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->collection('event-thumbnail'), 
                        Forms\Components\Select::make('mentors')
                            ->label('Mentor')
                            ->relationship('mentors', 'name', fn(Builder $query) => $query->whereHas('roles', fn($query) => $query->where('name', 'mentor')))
                            ->required()
                            ->getOptionLabelFromRecordUsing(function(Model $record) {
                                return $record->name . ' (' . ($record->custom_fields['bidang_mentor'] ?? ($record->custom_fields ?? null)['bidang_mentor'] ?? 'Mentor') . ')';
                            })
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'online' => 'Online',
                                'offline' => 'Offline',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state === 'online') {
                                    $set('venue', null);
                                } else {
                                    $set('link_meet', null);
                                }
                            })
                            ->required(),
                        Forms\Components\TextInput::make('link_meet')
                            ->url()
                            ->live()
                            ->required(fn(Get $get) => $get('tipe') === 'online')
                            ->visible(fn(Get $get) => $get('tipe') === 'online'),
                        Forms\Components\TextInput::make('venue')
                            ->required(fn(Get $get) => $get('tipe') === 'offline')
                            ->visible(fn(Get $get) => $get('tipe') === 'offline')
                            ->maxLength(191),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('kuota')
                            ->required()
                            ->numeric(),
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_open_registrasi')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('waktu_close_registrasi')
                                    ->required(),
                            ]),
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
                Forms\Components\Fieldset::make('Harga')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('pricing')
                            ->options([
                                'gratis' => 'Gratis',
                                'berbayar' => 'Berbayar',
                            ])
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state === 'gratis') {
                                    $set('harga', 0);
                                }
                            })
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('harga')
                            ->numeric()
                            ->required(fn(Get $get) => $get('pricing') === 'berbayar')
                            ->visible(fn(Get $get) => $get('pricing') === 'berbayar')
                            ->prefix('Rp')
                            ->suffix(',00'),
                    ]),
                Forms\Components\Fieldset::make('Jadwal')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Repeater::make('jadwal')
                            ->relationship('eventJadwals')
                            ->schema([
                                Forms\Components\TextInput::make('jadwal')
                                    ->required(),
                                Forms\Components\DatePicker::make('waktu')
                                    ->required(),
                            ])
                            ->required(),   
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('event-thumbnail'),
                Tables\Columns\TextColumn::make('tipe')
                    ->sortable()
                    ->getStateUsing(fn(Event $event) => $event->tipe === 'online' ? 'Online' : 'Offline')
                    ->badge(),
                Tables\Columns\TextColumn::make('harga')
                    ->weight(FontWeight::Bold)
                    ->money('IDR')
                    ->getStateUsing(fn(Event $event) => $event->harga ? $event->harga : 0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota')
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tersedia_asset')
                    ->label('Asset Tersedia')
                    ->getStateUsing(fn(Event $event) => $event->link_resource !== null ? 'Tersedia' : 'Tidak Tersedia')
                    ->badge()
                    ->color(fn($state) => $state === 'Tersedia' ? 'success' : 'danger'),
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
                Tables\Actions\Action::make('lihatPeserta')
                    ->label('Lihat Peserta')
                    ->icon('heroicon-o-user-group')
                    ->color('info')
                    ->url(fn(Event $event) => Pages\LihatPesertaEventPage::getUrl(['record' => $event->id])),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function(array $data)
                    {
                        if($data['tipe'] === 'online')
                        {
                            $data['venue'] = null;
                        }
                        else
                        {
                            $data['link_meet'] = null;
                        }

                        if($data['is_ready_asset'] === false)
                        {
                            $data['link_resource'] = null;
                        }
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
            'index' => Pages\ManageEvents::route('/'),
            'peserta' => Pages\LihatPesertaEventPage::route('/{record}/peserta'),
        ];
    }
}
