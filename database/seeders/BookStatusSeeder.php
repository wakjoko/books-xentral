<?php

namespace Database\Seeders;

use App\Traits\WithBookStatuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookStatusSeeder extends Seeder
{
    use WithoutModelEvents, WithBookStatuses;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createBookStatuses();
    }
}
