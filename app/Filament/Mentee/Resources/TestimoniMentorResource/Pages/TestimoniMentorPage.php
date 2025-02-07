<?php

namespace App\Filament\Mentee\Resources\TestimoniMentorResource\Pages;

use App\Models\User;
use App\Models\Testimoni;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Query\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Mentee\Resources\TestimoniMentorResource;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar as TableRatingStar;

class TestimoniMentorPage extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    protected static string $resource = TestimoniMentorResource::class;

    protected static string $view = 'filament.mentee.resources.testimoni-mentor-resource.pages.testimoni-mentor-page';

    public $mentor;

    public function mount()
    {
        $mentor = User::whereHas('roles', fn ($query) => $query->where('name', 'mentor'))->whereId(request()->route('record'))->first();

        if(!$mentor)
        {
            Notification::make()
                ->title('Error')
                ->body('Mentor tidak ditemukan')
                ->danger()
                ->send();

            return redirect()->route('filament.mentee.resources.testimoni-mentors.index');
        }

        $this->mentor = $mentor;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Testimoni Mentor';
    }

    public function createTestimoniAction(): CreateAction
    {
        return CreateAction::make('createTestimoni')
            ->model(Testimoni::class)
            ->form([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Hidden::make('testimoniable_id')
                            ->default($this->mentor->id),
                        RatingStar::make('rating'),
                        Textarea::make('ulasan'),
                        Hidden::make('mentee_id')
                            ->default(auth()->id()),
                        Hidden::make('testimoniable_type')
                            ->default(User::class),
                ]),
            ]);
    } 

    public function table(Table $table): Table
    {
        return $table
            ->query(Testimoni::query()->whereTestimoniableType(User::class)->whereTestimoniableId($this->mentor->id))
            ->columns([
                TableRatingStar::make('rating')
                    ->sortable(),
                TextColumn::make('ulasan')
                    ->searchable(),
            ])
            ->actions([
                // 
            ]);
    }
}
