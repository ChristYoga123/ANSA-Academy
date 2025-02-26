<?php

namespace App\Livewire\PromoPage;

use App\Models\Promo;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction as ActionsCreateAction;

class PromoKuponTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public function render()
    {
        return view('livewire.promo-page.promo-kupon-table');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Promo::query()->where('tipe', 'kupon'))
            ->columns([
                TextColumn::make('kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('persentase')
                    ->formatStateUsing(fn ($state) => "{$state}%"),
                TextColumn::make('tanggal_berakhir')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->locale('id')->isoFormat('dddd, D MMMM Y')),
                TextColumn::make('aktif')
                    ->label('Status Aktif')
                    ->getStateUsing(function(Promo $promo) 
                    {
                        // jika waktu sekarang lebih besar dari tanggal berakhir, maka promo tidak aktif
                        if (Carbon::parse($promo->tanggal_berakhir)->isPast())
                        {
                            $promo->update(['aktif' => false]);
                        } else
                        {
                            $promo->update(['aktif' => true]);
                        }

                        return $promo->aktif ? 'Aktif' : 'Tidak Aktif';
                    })
                    ->badge()
                    ->color(fn($state) => $state === 'Aktif' ? 'success' : 'danger'),
            ])
            ->filters([
                // add filters if needed
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('kode')
                            ->label('Kode Kupon')
                            ->required()
                            ->unique(table: Promo::class, column: 'kode', ignoreRecord: true),
                        TextInput::make('persentase')
                            ->label('Persentase Diskon')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100),
                        DatePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->required(),
                    ])
                    ->using(function (array $data, Promo $promo): Promo {
                        $promo->update([
                            'kode' => $data['kode'],
                            'persentase' => $data['persentase'],
                            'tanggal_berakhir' => $data['tanggal_berakhir'],
                        ]);

                        return $promo;
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // add bulk actions if needed
            ]);
    }

    public function createKuponAction(): ActionsCreateAction
    {
        return ActionsCreateAction::make('createKupon')
            ->label('Buat Kupon Baru')
            ->form([
                TextInput::make('kode')
                    ->label('Kode Kupon')
                    ->required()
                    ->unique(table: Promo::class, column: 'kode'),
                TextInput::make('persentase')
                    ->label('Persentase Diskon')
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->minValue(1)
                    ->maxValue(100),
                DatePicker::make('tanggal_berakhir')
                    ->label('Tanggal Berakhir')
                    ->required(),
            ])
            ->using(function (array $data): Promo {
                return Promo::create([
                    'tipe' => 'kupon',
                    'kode' => $data['kode'],
                    'persentase' => $data['persentase'],
                    'tanggal_berakhir' => $data['tanggal_berakhir'],
                    'aktif' => true,
                ]);
            });
    }
}
