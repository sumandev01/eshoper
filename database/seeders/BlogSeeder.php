<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fashion Trends',
            'Style Guides',
            'Shopping Tips',
            'New Arrivals',
        ];

        foreach ($categories as $categoryName) {
            $category = BlogCategory::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'status' => 1,
            ]);

            // Create some blogs for each category
            for ($i = 1; $i <= 3; $i++) {
                $title = "Awesome {$categoryName} Article {$i}";
                Blog::create([
                    'blog_category_id' => $category->id,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'content' => "<p>This is a sample blog content for {$title}. It covers all the essential details about the latest trends in the fashion industry. Stay tuned for more amazing content!</p>",
                    'status' => 1,
                    'meta_title' => $title,
                    'meta_description' => "Read all about {$title} and discover the best style tips.",
                    'meta_keyword' => "fashion, style, {$categoryName}",
                ]);
            }
        }
    }
}
