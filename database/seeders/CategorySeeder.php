<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $categories = [
      // Top level categories (depth 0)
      [
        'name' => 'Electronics',
        'slug' => 'electronics',
        'department_id' => 1,
        'parent_id' => null,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Fashion',
        'slug' => 'fashion',
        'department_id' => 2,
        'parent_id' => null,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      // Subcategories of electronics
      [
        'name' => 'Computers',
        'slug' => Str::slug('Computers'),
        'department_id' => 1,
        'parent_id' => 1,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Smartphones',
        'slug' => Str::slug('Smartphones'),
        'department_id' => 1,
        'parent_id' => 1,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      // Subcategories of computers
      [
        'name' => 'Laptops',
        'slug' => Str::slug('Laptops'),
        'department_id' => 1,
        'parent_id' => 3,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Desktops',
        'slug' => Str::slug('Desktops'),
        'department_id' => 1,
        'parent_id' => 3,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      // Subcategories of Smartphones
      [
        'name' => 'Android',
        'slug' => Str::slug('Android'),
        'department_id' => 1,
        'parent_id' => 4,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'iPhones',
        'slug' => Str::slug('iPhones'),
        'department_id' => 1,
        'parent_id' => 4,
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
      ],
    ];

    DB::table('categories')->insert($categories);
  }
}
