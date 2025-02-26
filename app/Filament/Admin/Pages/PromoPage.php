<?php

namespace App\Filament\Admin\Pages;

use App\Models\Promo;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;

class PromoPage extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Promo';

    protected static string $view = 'filament.admin.pages.promo-page';

    public function getTitle(): string|Htmlable
    {
        return 'Promo';
    }

    


}