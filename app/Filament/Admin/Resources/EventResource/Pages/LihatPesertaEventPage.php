<?php

namespace App\Filament\Admin\Resources\EventResource\Pages;

use App\Models\User;
use App\Models\Event;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Admin\Resources\EventResource;
use Filament\Tables\Concerns\InteractsWithTable;

class LihatPesertaEventPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.admin.resources.event-resource.pages.lihat-peserta-event-page';

    public function getTitle(): string|Htmlable
    {
        return 'Lihat Peserta';
    }

    public $event;

    public function mount()
    {
        $event = Event::find(request()->route('record'));

        if(!$event) {
            Notification::make()
                ->title('Gagal')
                ->body('Event tidak ditemukan')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.resources.events.index');
        }

        $this->event = $event;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::with('mentee')->whereTransaksiableType(Event::class)->whereTransaksiableId($this->event->id)->whereStatus('Sukses'))
            ->columns([
                TextColumn::make('mentee.name')
                    ->label('Nama Mentee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mentee.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->mentee->custom_fields['no_hp']),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->mentee?->custom_fields['alamat'] ?? '-'),
                ImageColumn::make('avatar_url')
                    ->label('Foto Profil')
            ])
            ->actions([
                Action::make('waMentee')
                    ->label('Hubungi')
                    ->icon('heroicon-o-phone')
                    ->url(fn(Transaksi $transaksi) => 'https://wa.me/' . trim($transaksi->mentee->custom_fields['no_hp'], '+'))
                    ->openUrlInNewTab(),
            ]);
    }
}
