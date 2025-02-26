<?php

namespace App\Livewire\PromoPage;

use App\Models\Promo;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PromoKategoriTable extends Component implements HasForms, HasActions, HasTable
{
    use InteractsWithForms, InteractsWithActions, InteractsWithTable;

    public function render()
    {
        return view('livewire.promo-page.promo-kategori-table');
    }

    public ?array $data = []; 

    public function mount()
    {
        $this->form->fill([
            'kategori' => Promo::query()->whereTipe('kategori')->first()
        ]);
    }

    public function createPromoKategoriAction(): CreateAction
    {
        return CreateAction::make('createPromoKategori')
            ->label('Tambah Promo Kategori')
            // ->visible(fn() => Promo::query()->whereTipe('kategori')->count() == 0)
            ->form([
                Select::make('kategori')
                    ->options([
                        'Mentoring' => 'Mentoring',
                        'Kelas ANSA' => 'Kelas ANSA',
                        'Proofreading' => 'Proofreading',
                        'Produk Digital' => 'Produk Digital',
                        'Event' => 'Event',
                    ])
                    ->required(),
                TextInput::make('persentase')
                    ->numeric()
                    ->required()
                    ->suffix('%')
                    ->minValue(1)
                    ->maxValue(100)
            ])
            ->using(function(array $data)
            {
                if(Promo::query()->whereTipe('produk')->whereAktif(true)->whereKategori($data['kategori'])->exists())
                {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Sudah ada promo untuk produk ini. Harap nonaktifkan promo produk ini terlebih dahulu.')
                        ->danger()
                        ->send();

                    return;
                }

                // jika promo kategori sudah ada, maka tidak bisa membuat promo kategori baru
                if(Promo::query()->whereTipe('kategori')->whereAktif(true)->whereKategori($data['kategori'])->exists())
                {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Sudah ada promo kategori untuk kategori ini. Harap nonaktifkan promo kategori ini terlebih dahulu.')
                        ->danger()
                        ->send();

                    return;
                }
                Promo::create([
                    'tipe' => 'kategori',
                    'kategori' => $data['kategori'],
                    'persentase' => $data['persentase'],
                    'aktif' => true
                ]);

                Notification::make()
                    ->title('Sukses')
                    ->body('Promo Kategori berhasil ditambahkan')
                    ->success();
            })
            ->successNotification(null);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Promo::query()->whereTipe('kategori'))
            ->columns([
                TextColumn::make('kategori'),
                TextColumn::make('persentase')
                    ->getStateUsing(function(Promo $promo)
                    {
                        return $promo->persentase . '%';
                    })
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('kategori')
                            ->options([
                                'Mentoring' => 'Mentoring',
                                'Kelas ANSA' => 'Kelas ANSA',
                                'Proofreading' => 'Proofreading',
                                'Produk Digital' => 'Produk Digital',
                                'Event' => 'Event',
                            ])
                            ->required(),
                        TextInput::make('persentase')
                            ->numeric()
                            ->required()
                            ->suffix('%')
                            ->minValue(1)
                            ->maxValue(100)
                    ])
                    ->using(function(Promo $promo, array $data)
                    {
                        if(Promo::query()->whereTipe('produk')->whereAktif(true)->whereKategori($data['kategori'])->exists())
                        {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Sudah ada promo untuk produk ini. Harap nonaktifkan promo produk ini terlebih dahulu.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // jika promo kategori sudah ada, maka tidak bisa membuat promo kategori baru
                        if(Promo::query()->whereTipe('kategori')->whereAktif(true)->whereKategori($data['kategori'])->where('id', '!=', $promo->id)->exists())
                        {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Sudah ada promo kategori untuk kategori ini. Harap nonaktifkan promo kategori ini terlebih dahulu.')
                                ->danger()
                                ->send();

                            return;
                        }
                        $promo->update([
                            'kategori' => $data['kategori'],
                            'persentase' => $data['persentase']
                        ]);

                        Notification::make()
                            ->title('Sukses')
                            ->body('Promo Kategori berhasil diubah')
                            ->success();
                    })
                    ->successNotification(null),
                DeleteAction::make(),
            ]);
    }
}
