<?php

namespace App\Livewire\PromoPage;

use App\Models\Event;
use App\Models\Promo;
use App\Models\Program;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class PromoProdukTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public function render()
    {
        return view('livewire.promo-page.promo-produk-table');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Promo::query()->where('tipe', 'produk'))
            ->columns([
                TextColumn::make('promoable_type')
                    ->formatStateUsing(fn ($state) => 
                        match ($state) {
                            Program::class => 'Program',
                            ProdukDigital::class => 'Produk Digital',
                            Event::class => 'Event',
                            default => 'Tidak diketahui'
                        }
                    )
                    ->label('Jenis Produk'),
                TextColumn::make('promoable.judul')
                    ->searchable()
                    ->label('Produk'),
                TextColumn::make('persentase')
                    ->formatStateUsing(fn ($state) => "{$state}%"),
                ToggleColumn::make('aktif'),
                TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->date('d M Y H:i'),
            ])
            ->filters([
                // add filters if needed
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('product_selector')
                            ->label('Pilih Produk')
                            ->options($this->getAllProductsOptions())
                            ->searchable()
                            ->formatStateUsing(function(Promo $promo)
                            {
                                $kategori = match ($promo->promoable_type) {
                                    Program::class => $promo->promoable->program,
                                    ProdukDigital::class => 'Produk Digital',
                                    Event::class => 'Event',
                                    default => 'Tidak diketahui'
                                };

                                return "{$promo->promoable_type}|{$kategori}|{$promo->promoable_id}";
                            })
                            ->required(),
                            // ->disabled(), // Tidak mengizinkan perubahan produk
                        TextInput::make('persentase')
                            ->label('Persentase Diskon')
                            ->numeric()
                            ->suffix('%')
                            ->required()
                            ->minValue(1)
                            ->maxValue(100),
                        Toggle::make('aktif')
                            ->label('Status Aktif')
                            ->default(true),
                    ])
                    ->using(function (Promo $record, array $data){
                        $productParts = explode('|', $data['product_selector']);

                        if(Promo::query()->whereTipe('kategori')->whereAktif(true)->whereKategori($productParts[1])->exists())
                        {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Sudah ada promo kategori untuk produk ini. Harap nonaktifkan promo kategori produk ini terlebih dahulu.')
                                ->danger()
                                ->send();
                            
                            return;
                        }

                        if(Promo::query()->whereTipe('produk')->whereAktif(true)->where('promoable_type', $productParts[0])->where('promoable_id', $productParts[2])->where('id', '!=', $record->id)->exists())
                        {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Sudah ada promo untuk produk ini. Harap nonaktifkan promo produk ini terlebih dahulu.')
                                ->danger()
                                ->send();
                            
                            return;
                        }

                        $record->update([
                            'promoable_type' => $productParts[0],
                            'promoable_id' => $productParts[2],
                            'kategori' => $productParts[1],
                            'persentase' => $data['persentase'],
                            'aktif' => $data['aktif'],
                        ]);

                        Notification::make()
                            ->title('Sukses')
                            ->body('Promo Produk berhasil diperbarui')
                            ->success();
                    })
                    ->successNotification(null),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // add bulk actions if needed
            ]);
    }

    public function createPromoProdukAction(): CreateAction
    {
        return CreateAction::make('createPromoProduk')
            ->label('Buat Promo Produk')
            ->form([
                Select::make('product_selector')
                    ->label('Pilih Produk')
                    ->options($this->getAllProductsOptions())
                    ->searchable()
                    ->required(),
                TextInput::make('persentase')
                    ->label('Persentase Diskon')
                    ->numeric()
                    ->required()
                    ->suffix('%')
                    ->minValue(1)
                    ->maxValue(100),
            ])
            ->using(function (array $data){
                // Ambil jenis dan ID produk
                $productParts = explode('|', $data['product_selector']);
                
                if(Promo::query()->whereTipe('kategori')->whereAktif(true)->whereKategori($productParts[1])->exists())
                {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Sudah ada promo kategori untuk produk ini. Harap nonaktifkan promo kategori produk ini terlebih dahulu.')
                        ->danger()
                        ->send();
                    
                    return;
                }

                if(Promo::query()->whereTipe('produk')->whereAktif(true)->where('promoable_type', $productParts[0])->where('promoable_id', $productParts[2])->exists())
                {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Sudah ada promo untuk produk ini. Harap nonaktifkan promo produk ini terlebih dahulu.')
                        ->danger()
                        ->send();
                    
                    return;
                }
                
                Promo::create([
                    'tipe' => 'produk',
                    'promoable_type' => $productParts[0],
                    'promoable_id' => $productParts[2],
                    'persentase' => $data['persentase'],
                    'kategori' => $productParts[1],
                    'aktif' => true,
                ]);

                Notification::make()
                    ->title('Sukses')
                    ->body('Promo Produk berhasil ditambahkan')
                    ->success();
            })
            ->successNotification(null);
    }

    // Helper method untuk mendapatkan semua opsi produk
    protected function getAllProductsOptions(): array
    {
        // Ambil semua produk dari ketiga jenis
        $programs = Program::all()->map(function ($item) {
            return [
                'id' => Program::class . '|' . $item->program . '|' . $item->id,
                'label' => "[{$item->program}] {$item->judul}"
            ];
        });
        
        $produkDigitals = ProdukDigital::all()->map(function ($item) {
            return [
                'id' => ProdukDigital::class . '|' . 'Produk Digital' . '|' . $item->id,
                'label' => "[Produk Digital] {$item->judul}"
            ];
        });
        
        $events = Event::all()->map(function ($item) {
            return [
                'id' => Event::class . '|' . 'Event' . '|' . $item->id,
                'label' => "[Event] {$item->judul}"
            ];
        });
        
        // Gabungkan semua produk
        $allProducts = $programs->concat($produkDigitals)->concat($events);
        
        // Buat options array untuk Select
        return $allProducts->pluck('label', 'id')->toArray();
    }
}
