<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LokerMentor;
use Illuminate\Database\Seeder;
use App\Models\LokerMentorBidang;
use App\Models\LokerMentorBidangKualifikasi;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
        ]);

        $bidangLoker = LokerMentorBidang::factory(2)->create();
        $kualifikasiBidangLoker = LokerMentorBidangKualifikasi::factory(10)
            ->state(
                new Sequence(
                    fn(Sequence $sequence) => [
                        'loker_mentor_bidang_id' => $bidangLoker->random()->id,
                    ]
                )
            )
            ->create();
        $calonMentor = LokerMentor::factory(10)
            ->state(
                new Sequence(
                    fn(Sequence $sequence) => [
                        'loker_mentor_bidang_id' => $bidangLoker->random()->id,
                    ]
                )
            )
            ->create();
    }
}
