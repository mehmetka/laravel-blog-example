<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Article;
use App\Models\Rate;
use Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 8; $i++) {
            $userData = [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt($faker->password),
                'role' => rand(0, 3)
            ];

            $user = User::create($userData);

            for ($j = 0; $j < 10; $j++) {
                $articleData = [
                    'title' => $faker->words(rand(3, 10), true),
                    'content' => $faker->paragraphs(rand(1, 10), true),
                    'view_count' => rand(0, 300),
                    'user_id' => $user['id'],
                    'publish' => rand(0, 1)
                ];

                $article = Article::create($articleData);

                for ($k = 0; $k < 10; $k++) {
                    $roleData = [
                        'rate' => rand(1, 5),
                        'user_id' => $user['id'],
                        'article_id' => $article['id']
                    ];

                    Rate::create($roleData);
                }

            }
        }
    }
}
