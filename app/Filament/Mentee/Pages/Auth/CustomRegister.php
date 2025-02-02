<?php

namespace App\Filament\Mentee\Pages\Auth;

use App\Models\User;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Filament\Http\Responses\Auth\RegistrationResponse;

class CustomRegister extends Register
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        ...$this->getCustomFormComponents(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama')
            ->required()
            ->unique(ignoreRecord: true);
    }

    protected function getCustomFormComponents(): array
    {
        return [
            PhoneInput::make('custom_fields.no_hp')
                ->label('No. HP')
                ->required()
                ->unique(),
            Select::make('custom_fields.status_pelajar')
                ->label('Status Pelajar')
                ->options([
                    'Siswa' => 'Siswa',
                    'Mahasiswa' => 'Mahasiswa',
                ])
                ->live()
                ->default('Siswa')
                ->required()
                ->selectablePlaceholder(false),

            TextInput::make('custom_fields.sekolah')
                ->label('Sekolah')
                ->live()
                ->visible(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Siswa')
                ->required(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Siswa'),

            TextInput::make('custom_fields.kelas')
                ->label('Kelas')
                ->live()
                ->visible(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Siswa')
                ->required(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Siswa')
                ->numeric()
                ->minValue(10)
                ->maxValue(12),

            TextInput::make('custom_fields.universitas')
                ->label('Universitas')
                ->live()
                ->visible(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Mahasiswa')
                ->required(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Mahasiswa'),
                
            TextInput::make('custom_fields.semester')
                ->label('Semester')
                ->live()
                ->visible(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Mahasiswa')
                ->required(fn(Get $get) => $get('custom_fields.status_pelajar') === 'Mahasiswa')
                ->numeric()
                ->minValue(1)
                ->maxValue(14),
        ];
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();
        
        // Extract custom fields
        $customFields = Arr::get($data, 'custom_fields', []);
        
        // Remove custom_fields from main data array
        unset($data['custom_fields']);
        
        // Add custom_fields back as JSON
        $data['custom_fields'] = $customFields;
        
        // Begin transaction
        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'custom_fields' => $data['custom_fields'],
                'referral_code' => strtolower(Str::random(6)),
            ]);
            
            // Assign mentee role
            $user->assignRole('mentee');
            
            DB::commit();

            Notification::make()
                ->title('Registrasi Berhasil')
                ->body('Silahkan login untuk melanjutkan')
                ->success()
                ->send();

            // Hapus parent::register() karena kita sudah menangani registrasi secara manual
            return app(RegistrationResponse::class);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
