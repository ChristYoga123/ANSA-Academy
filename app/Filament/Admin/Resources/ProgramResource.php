<?php

namespace App\Filament\Admin\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Program;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ProgramResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Admin\Resources\ProgramResource\RelationManagers;
use Joshembling\ImageOptimizer\Components\SpatieMediaLibraryFileUpload;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $author = Step::make('Author')
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Select::make('division_id')
                            ->relationship('division', 'nama')
                            ->required(),
                    ]),
                ]);

        $metaData = Step::make('Metadata')
                ->schema([
                    Forms\Components\TextInput::make('judul_program')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('judul_kegiatan')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    SpatieMediaLibraryFileUpload::make('thumbnail')
                        ->required()
                        ->collection('program-thumbnail')
                        ->image(),
                    Forms\Components\RichEditor::make('konten')
                        ->required(),
                ]);

        $waktuTempat = Step::make('Waktu & Tempat')
                ->schema([
                    Map::make('location')
                        ->label('Lokasi Kegiatan')
                        ->defaultLocation(latitude: -8.165516031480806, longitude: 113.71727423131937)
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            $set('lat', $state['lat']);
                            $set('long', $state['lng']);
                        })
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            $set('location', [
                                    'lat'     => $record->lat,
                                    'lng'     => $record->long,
                                    // 'geojson' => json_decode(strip_tags($record->description))
                                ]
                            );
                        })
                        ->liveLocation(true, true, 5000)
                        ->showMarker()
                        ->markerColor("#22c55eff")
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                        ->zoom(15)
                        ->detectRetina()
                        ->showMyLocationButton()
                        ->extraTileControl([])
                        ->extraControl([
                            'zoomDelta'           => 1,
                            'zoomSnap'            => 2,
                        ]),
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('lat')
                                ->required()
                                ->label('Latitude')
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\TextInput::make('long')
                                ->required()
                                ->label('Longitude')
                                ->readOnly()
                                ->numeric(),
                        ])
                ]);

        $registrasi = Step::make('Registrasi')
                ->schema([
                    Section::make('Registrasi Panitia')
                        ->schema([
                            Forms\Components\TextInput::make('gform_panitia')
                                ->required()
                                ->label('Google Form Registrasi Panitia')
                                ->placeholder('https://docs.google.com/forms/xxxxxx'),
                            Grid::make()
                                ->columns(2)
                                ->schema([
                                    Forms\Components\DateTimePicker::make('open_regis_panitia')
                                        ->label('Waktu Buka Registrasi Panitia')
                                        ->required(),
                                    Forms\Components\DateTimePicker::make('close_regis_panitia')
                                        ->label('Waktu Tutup Registrasi Panitia')
                                        ->required(),
                                ]),
                    ]),

                    Section::make('Registrasi Peserta')
                        ->schema([
                            Forms\Components\TextInput::make('gform_peserta')
                                ->required()
                                ->label('Google Form Registrasi Peserta')
                                ->placeholder('https://docs.google.com/forms/xxxxxx'),
                            Grid::make()
                                ->columns(2)
                                ->schema([
                                    Forms\Components\DateTimePicker::make('open_regis_peserta')
                                        ->required()
                                        ->label('Waktu Buka Registrasi Peserta'),
                                    Forms\Components\DateTimePicker::make('close_regis_peserta')
                                        ->required()
                                        ->label('Waktu Tutup Registrasi Peserta'),
                                ]),
                    ]),

            ]);

        $jadwal = Step::make('Jadwal Kegiatan')
            ->schema([
                Repeater::make('jadwal_kegiatan')
                    ->required()
                    ->schema([
                        Forms\Components\TextInput::make('jadwal')
                            ->required(),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_mulai')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('waktu_selesai')
                                    ->required(),
                            ])
                    ])
            ]);
        
        $dokumentasi = Step::make('Dokumentasi (Optional)')
            ->schema([
                SpatieMediaLibraryFileUpload::make('galeri (multiple & optional)')
                    ->label('Galeri Kegiatan (Multiple & Optional)')
                    ->collection('program-galerry')
                    ->multiple()
                    ->reorderable()
                    ->nullable()
                    ->image(),
            ]);

        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Wizard::make(['Author', 'Metadata', 'Waktu & Tempat', 'Registrasi', 'Jadwal Kegiatan', 'Dokumentasi'])
                            ->steps([$author, $metaData, $waktuTempat, $registrasi, $jadwal, $dokumentasi])
                            ->skippable(fn(string $operation) => $operation === 'edit' || $operation === 'view')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('division.nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('program-thumbnail')
                    ->collection('program-thumbnail')
                    ->label('Thumbnail'),
                    Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Berjalan' => 'success',
                        'Selesai' => 'danger',
                    })
                    ->getStateUsing(function (Program $program) {
                        $jadwal = $program->jadwal_kegiatan;
                        // dd($jadwal[array_key_last($jadwal)]['waktu_mulai']);
                        if (empty($jadwal)) {
                            return 'Selesai';
                        }
                
                        // Set timezone ke Asia/Jakarta
                        $waktuMulaiPertama = Carbon::parse($jadwal[0]['waktu_mulai'])->setTimezone('Asia/Jakarta');
                        $waktuSelesaiTerakhir = Carbon::parse($jadwal[array_key_last($jadwal)]['waktu_selesai'])->setTimezone('Asia/Jakarta');
                        $now = now()->setTimezone('Asia/Jakarta');
                
                        if ($now->between($waktuMulaiPertama, $waktuSelesaiTerakhir)) {
                            return 'Berjalan';
                        }
                
                        return 'Selesai';
                    }),
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
                SelectFilter::make('division_id')
                    ->label('Divisi')
                    ->relationship('division', 'nama'),
                    Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('availability')
                            ->options([
                                'Berjalan' => 'Berjalan',
                                'Selesai' => 'Selesai',
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['availability'],
                                function (Builder $query, string $status) {
                                    $now = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                                    
                                    if ($status === 'Berjalan') {
                                        return $query->whereRaw("
                                            JSON_UNQUOTE(
                                                JSON_EXTRACT(
                                                    jadwal_kegiatan, 
                                                    '$[0].waktu_mulai'
                                                )
                                            ) <= ? 
                                            AND 
                                            (
                                                SELECT 
                                                    JSON_UNQUOTE(
                                                        JSON_EXTRACT(
                                                            jadwal_kegiatan,
                                                            CONCAT('$[', JSON_LENGTH(jadwal_kegiatan) - 1, '].waktu_selesai')
                                                        )
                                                    )
                                            ) >= ?
                                        ", [$now, $now]);
                                    }
                                    
                                    if ($status === 'Selesai') {
                                        return $query->whereRaw("
                                            (
                                                SELECT 
                                                    JSON_UNQUOTE(
                                                        JSON_EXTRACT(
                                                            jadwal_kegiatan,
                                                            CONCAT('$[', JSON_LENGTH(jadwal_kegiatan) - 1, '].waktu_selesai')
                                                        )
                                                    )
                                            ) < ?
                                        ", [$now]);
                                    }
                                }
                            );
                    })
                    ->indicateUsing(function(array $data)
                    {
                        if(!$data['availability']) {
                            return null;
                        }

                        return 'Status: ' . $data['availability'];
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManagePrograms::route('/'),
        ];
    }
}
