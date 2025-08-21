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
                'name' => 'laravel',
                'description' => 'eloquent php framework',
            ],
            [
                'name' => 'python',
                'description' => 'python',
            ],
            [
                'name' => 'ruby',
                'description' => 'a programming language',
            ],
            [
                'name' => 'java',
                'description' => 'a programming language',
            ],
            [
                'name' => 'c',
                'description' => 'the best language',
            ],
            [
                'name' => 'c++',
                'description' => 'The best programming language',
            ],
            [
                'name' => 'javascript',
                'description' => 'js',
            ],
        ];

        Tag::upsert($data, ['name']);
    }
}
