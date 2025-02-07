<?php

namespace App\Filament\Admin\Resources\MentorResource\Pages;

use App\Models\User;
use App\Models\Testimoni;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Admin\Resources\MentorResource;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar;

class TestimoniMentor extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;
    protected static string $resource = MentorResource::class;

    protected static string $view = 'filament.admin.resources.mentor-resource.pages.testimoni-mentor';

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

    public function table(Table $table): Table
    {
        return $table
            ->query(Testimoni::query()->with('mentee')->whereTestimoniableType(User::class)->whereTestimoniableId($this->mentor->id))
            ->columns([
                TextColumn::make('mentee.name')
                    ->label('Nama Mentee')
                    ->searchable()
                    ->sortable(),
                RatingStar::make('rating')
                    ->sortable(),
                TextColumn::make('ulasan')
                    ->searchable(),
            ])
            ->actions([
                // 
            ]);
    }
}
