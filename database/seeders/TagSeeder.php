<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'php',
                'description' => 'PHP Hypertext Preprocessor',
            ],
            [
                'name' => 'python',
                'description' => 'python',
            ],
            [
                'name' => 'cpp',
                'description' => 'C++',
            ],
            [
                'name' => 'javascript',
                'description' => 'js',
            ],
        ];

        Tag::upsert($data, ['name']);
    }
}
