<?php

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{

    public function run()
    {
        $randomSteatment = (DB::connection()->getName()=='mysql')?'RAND()':((DB::connection()->getName()=='sqlite')?'RANDOM()':'');

        $faker = Faker\Factory::create();
//        $faker->seed(1234);

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Article::truncate();

        foreach (range(1, 5) as $index) {
            $userId = User::orderBy(DB::raw($randomSteatment))->first()->id;
            $tag = Tag::orderBy(DB::raw($randomSteatment))->first();

            $article = new Article;
            $newArticle = $article->create([
                'user_id' => $userId,
                'title' => $faker->sentence(5),
                'body' => $faker->paragraph(3),
                'published_at' => $faker->date()
            ]);
            $newArticle->tags()->sync([$tag->id,$tag->name]);
        }

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}