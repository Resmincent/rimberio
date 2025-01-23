<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Panggil seeder lain jika diperlukan
        $this->call([
            AdminSeeder::class,
        ]);

        // Data kategori
        $categories = [
            ['name' => 'Makanan', 'slug' => Str::slug('Makanan')],
            ['name' => 'Minuman', 'slug' => Str::slug('Minuman')],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
