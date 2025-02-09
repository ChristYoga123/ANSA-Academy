<?php

namespace App\Filament\Mentee\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Testimoni;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use App\Models\TestimoniMentor;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentee\Resources\TestimoniMentorResource\Pages;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar;
use App\Filament\Mentee\Resources\TestimoniMentorResource\RelationManagers;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar as ComponentsRatingStar;

class TestimoniMentorResource extends Resource
{
    protected static ?string $model = Testimoni::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Testimoni Mentor';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('testimoniable_id')
                            ->label('Nama Mentor')
                            ->options(User::whereHas('roles', fn (Builder $query) => $query->where('name', 'mentor'))->pluck('name', 'id')),
                        RatingStar::make('rating'),
                        Forms\Components\Textarea::make('ulasan'),
                        Forms\Components\Hidden::make('mentee_id')
                            ->default(auth()->id()),
                        Forms\Components\Hidden::make('testimoniable_type')
                            ->default(User::class),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->select('users.*')
                    ->withAvg('testimoni as rating', 'rating')
                    ->whereHas('roles', fn (Builder $query) => $query->where('name', 'mentor'))
                    ->when(auth()->check(), function ($query) {
                        return $query->withExists([
                            'programMentees' => fn ($query) => 
                                $query->where('mentee_id', auth()->id())
                        ]);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Mentor')
                    ->searchable(),
                ComponentsRatingStar::make('rating')
                    ->label('Rating Keseluruhan')
                    ->getStateUsing(fn ($record) => $record->rating),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('buatTestimoni')
                    ->label('Beri Testimoni')
                    ->icon('heroicon-o-star')
                    ->url(fn(User $user) => Pages\TestimoniMentorPage::getUrl(['record' => $user->id]))
                    ->visible(fn(User $user) => $user->program_mentees_exists),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTestimoniMentors::route('/'),
            'testimoni' => Pages\TestimoniMentorPage::route('{record}/testimoni'),
        ];
    }
}
