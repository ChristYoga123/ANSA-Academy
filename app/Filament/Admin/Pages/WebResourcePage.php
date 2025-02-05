<?php

namespace App\Filament\Admin\Pages;

use App\Models\WebAd;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\WebResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class WebResourcePage extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Web Resource';

    protected static string $view = 'filament.admin.pages.web-resource-page';

    public function getTitle(): string|Htmlable
    {
        return 'Web Resource';
    }

    public ?array $data = [];

    public function mount()
    {
        $resourceWeb = WebResource::with('media')->first();
        
        if ($resourceWeb) {
            $data = [
                'tentang' => $resourceWeb->tentang,
                'visi' => $resourceWeb->visi,
                'youtube_url' => $resourceWeb->youtube_url,
                'quote' => $resourceWeb->quote,
                'faqs' => $resourceWeb->faqs,
                'instagram' => $resourceWeb->media_sosial['instagram'],
                'linkedin' => $resourceWeb->media_sosial['linkedin'],
                'youtube' => $resourceWeb->media_sosial['youtube'],
            ];

            $this->form->fill($data);
        } else {
            $this->form->fill([]);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(WebAd::query())
            ->columns([
                TextColumn::make('judul')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('background')
                    ->collection('ad-background'),
            ])
            ->actions([
                EditAction::make('editIklan')
                    ->label('Edit Iklan')
                    ->icon('heroicon-o-pencil')
                    ->model(WebAd::class)
                    ->form([
                        TextInput::make('headline')
                            ->required(),
                        TextInput::make('judul')
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('background')
                            ->collection('ad-background')
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->required(),
                        Textarea::make('deskripsi')
                            ->required(),
                        TextInput::make('url')
                            ->url()
                            ->required(),
                    ]),
                DeleteAction::make()
            ]);
    }

    public function createIklanAction(): CreateAction
    {
        return CreateAction::make('createIklan')
            ->model(WebAd::class)
            ->form([
                TextInput::make('headline')
                            ->required(),
                        TextInput::make('judul')
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('background')
                            ->collection('ad-background')
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->required(),
                        Textarea::make('deskripsi')
                            ->required(),
                        TextInput::make('url')
                            ->label('URL Iklan')
                            ->url()
                            ->required(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(WebResource::class)
            ->schema([
                Fieldset::make('Sumber Daya Website')
                    ->columns(1)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo-website')
                            ->image()
                            ->label('Logo Website (Jika tidak diisi, maka logo website tidak akan berubah)')
                            ->collection('logo-website')
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->required(),
                        Textarea::make('visi')
                            ->required(),
                        
                        RichEditor::make('tentang')
                            ->required(),
                        TextInput::make('youtube_url')
                            ->required()
                            ->visible(),
                        Textarea::make('quote')
                            ->required(),
                    ]),
                
                // Handle Banner Section
                Fieldset::make('banner')
                    ->label('Banner Section')
                    ->columns(1)
                    ->schema([
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('tentang_kami_banner')
                                    ->label('Banner Tentang Kami (Jika tidak diisi, maka banner tidak akan berubah, Minimal 2 foto)')
                                    ->image()
                                    ->required()
                                    ->multiple()
                                    ->minFiles(2)
                                    ->collection('banner-tentang-kami'),
                                SpatieMediaLibraryFileUpload::make('mentoring_banner')
                                    ->label('Banner Mentoring')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-mentoring'),
                                SpatieMediaLibraryFileUpload::make('kelas_banner')
                                    ->label('Banner Kelas')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-kelas'),
                                SpatieMediaLibraryFileUpload::make('proofreading_banner')
                                    ->label('Banner Proofreading')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-proofreading'),
                                SpatieMediaLibraryFileUpload::make('produk_banner')
                                    ->label('Banner Produk Digital')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-produk'),
                                SpatieMediaLibraryFileUpload::make('event_banner')
                                    ->label('Banner Event')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-event'),
                                SpatieMediaLibraryFileUpload::make('lomba_banner')
                                    ->label('Banner Lomba')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-lomba'),
                                SpatieMediaLibraryFileUpload::make('blog_banner')
                                    ->label('Banner Blog')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-blog'),
                                SpatieMediaLibraryFileUpload::make('karir_banner')
                                    ->label('Banner Karir')
                                    ->image()
                                    ->required()
                                    ->maxFiles(1)
                                    ->collection('banner-karir'),
                            ])
                    ]),
                
                Fieldset::make('FAQs')
                    ->columns(1)
                    ->schema([
                        Repeater::make('faqs')
                            ->schema([
                                TextInput::make('pertanyaan')
                                    ->required(),
                                TextInput::make('jawaban')
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah FAQ')
                            ->required(),
                    ]),
                Fieldset::make('Media Sosial')
                    ->columns(1)
                    ->schema([
                        TextInput::make('instagram')
                            ->required()
                            ->url(),
                        // X dan tiktok
                        TextInput::make('youtube')
                            ->required()
                            ->url(),
                        TextInput::make('linkedin')
                            ->required()
                            ->url(),
                    ])
            ])
            ->statePath('data')    
        ;
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save()
    {
        // dd($this->data);
        DB::beginTransaction();
        try {
            $data = [
                'visi' => $this->data['visi'],
                'tentang' => $this->data['tentang'],
                'youtube_url' => $this->data['youtube_url'],
                'quote' => $this->data['quote'],
                'faqs' => collect($this->data['faqs'])->map(fn($faq) => [
                    'pertanyaan' => $faq['pertanyaan'],
                    'jawaban' => $faq['jawaban'],
                ]),
                'media_sosial' => [
                    'instagram' => $this->data['instagram'],
                    'youtube' => $this->data['youtube'],
                    'linkedin' => $this->data['linkedin'],
                ]
            ];

            $sumberDaya = WebResource::updateOrCreate(['id' => WebResource::first()?->id], $data);

            // Handle logo website upload
            if (isset($this->data['logo-website']) && count($this->data['logo-website']) > 0) {
                $sumberDaya->clearMediaCollection('logo-website');
                
                // Get the temporary file
                foreach ($this->data['logo-website'] as $uuid => $file) {
                    if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        // Add the file to the media collection
                        $sumberDaya->addMediaFromString($file->get())
                            ->usingFileName($file->getClientOriginalName())
                            ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                            ->toMediaCollection('logo-website');
                    }
                }
            }

            // Handle banner upload
            $bannerFields = [
                'tentang_kami_banner',
                'mentoring_banner',
                'kelas_banner',
                'proofreading_banner',
                'produk_banner',
                'event_banner',
                'lomba_banner',
                'blog_banner',
                'karir_banner',
            ];

            foreach ($bannerFields as $field) {
                if (isset($this->data[$field]) && count($this->data[$field]) > 0) {
                    $sumberDaya->clearMediaCollection($field);
                    
                    // Get the temporary file
                    foreach ($this->data[$field] as $uuid => $file) {
                        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                            // Add the file to the media collection
                            $sumberDaya->addMediaFromString($file->get())
                                ->usingFileName($file->getClientOriginalName())
                                ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                ->toMediaCollection($field);
                        }
                    }
                }
            }

            DB::commit();
            Notification::make()
                ->success()
                ->title('Success')
                ->body('Data berhasil disimpan')
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title('Error')  
                ->body('Data gagal disimpan ' . $e->getMessage())
                ->send();
        }
    }

}
