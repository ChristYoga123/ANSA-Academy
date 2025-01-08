<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Article;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ArticleResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Admin\Resources\ArticleResource\RelationManagers;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $author = Step::make('Author')
            ->schema([
                Select::make('division_id')
                    ->relationship('division', 'nama')
            ]);

        $metaData = Step::make('Metadata')
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('kategori')
                    ->required()
                    ->options([
                        // 'informasi', 'tutorial', 'mitos-fakta', 'tips-trik', 'press-release'
                        'informasi' => 'Informasi',
                        'tutorial' => 'Tutorial',
                        'mitos-fakta' => 'Mitos & Fakta',
                        'tips-trik' => 'Tips & Trik',
                        'press-release' => 'Press Release',
                    ]),
                SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->required()
                    ->collection('article-thumbnail')
                    ->image(),
                Forms\Components\RichEditor::make('konten')
                    ->required(),
                Forms\Components\Toggle::make('is_unggulan')
                    ->required()
                    ->default(false),
            ]);
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Wizard::make(['Author', 'Metadata'])
                            ->steps([$author, $metaData])
                            ->skippable(fn(string $operation) => $operation === 'edit')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('division.nama')
                    ->label('Divisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori'),
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('article-thumbnail'),
                Tables\Columns\ToggleColumn::make('is_unggulan')
                    ->label('Unggulan'),
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
            'index' => Pages\ManageArticles::route('/'),
        ];
    }
}
