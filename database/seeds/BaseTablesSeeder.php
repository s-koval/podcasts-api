<?php

use Illuminate\Database\Seeder;

class BaseTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeding 10 records to podcasts table.
     * Seeding 5 comment for each podcast in comments table.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Podcast::class, 10)->create()->each(function ($podcast) {
            for($i=0; $i<=5; $i++) {
                $podcast->comments()->create(factory(App\Comment::class)->make()->toArray());
            }
        });
    }
}
