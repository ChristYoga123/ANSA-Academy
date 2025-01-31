<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LokerMentor>
 */
class LokerMentorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'no_hp' => fake()->phoneNumber(),
            'universitas' => fake()->company(),
            'semester' => fake()->randomElement(['6', '7', '8', '9', 'Fresh Graduate']),
            'alasan_mendaftar' => fake()->sentence(),
            'mahasiswa_berprestrasi' => fake()->randomElement(['Fakultas', 'Universitas', 'Wilayah', 'Nasional']),
            'pencapaian' => [fake()->sentence(), fake()->sentence(), fake()->sentence(), fake()->sentence(), fake()->sentence()],
            'drive_portofolio' => fake()->url(),
            'drive_cv' => fake()->url(),
            'status_penerimaan' => 'Menunggu',
            'linkedin' => fake()->url(),
            'instagram' => fake()->url(),
        ];
    }
}
