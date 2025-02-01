<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MentoringResource\Pages;
use App\Filament\Admin\Resources\MentoringResource\RelationManagers;
use App\Models\Mentoring;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentoringResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentoring';

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
                            ->maxLength(191)
                            ->formatStateUsing(fn(Model $record) => $record->judul ? str_replace('[MENTORING] ', '', $record->judul) : null),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->image()
                            ->collection('program-thumbnail'),
                        Forms\Components\Select::make('mentors')
                            ->relationship('mentors', 'name', fn(Builder $query) => $query->whereHas('roles', fn($query) => $query->where('name', 'mentor')))
                            ->required()
                            ->getOptionLabelFromRecordUsing(function(Model $record) {
                                return $record->name . ' (' . ($record->custom_fields['bidang_mentor'] ?? ($record->custom_fields ?? null)['bidang_mentor'] ?? 'Mentor') . ')';
                            })
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make('Pricing Mentoring')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Repeater::make('mentoringPakets')
                        ->relationship('mentoringPakets')
                        ->label('Paket Mentoring')
                        ->reorderable()
                        ->required()
                        ->schema([
                            Forms\Components\Select::make('jenis')
                                ->options([
                                    'Lanjutan' => 'Lanjutan',
                                    'Pemula' => 'Pemula',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->maxLength(191),
                            Forms\Components\TextInput::make('jumlah_pertemuan')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('harga')
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->suffix('.00'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Program::query()->with(['media', 'mentors', 'mentoringPakets'])->whereProgram('Mentoring'))
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
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function(array $data, Program $record)
                    {
                        $data['judul'] = "[MENTORING] " . $data['judul'];

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
            'index' => Pages\ManageMentorings::route('/'),
        ];
    }
}
