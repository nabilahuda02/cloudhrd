<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class TaskTableSeeder extends Seeder {

    public function run()
    {
        DB::table('todo_followers')->truncate();
        DB::table('todo_histories')->truncate();
        DB::table('todo_orders')->truncate();
        DB::table('todo_tags')->truncate();
        DB::table('tags')->truncate();
        DB::table('tag_categories')->truncate();
        DB::table('todos')->truncate();

        foreach([
            [
                'name' => 'Status'
            ],
            [
                'name' => 'Priority'
            ]
        ] as $tag) {
            Task__Category::create($tag);
        }

       foreach([
            [
                'name' => 'New',
                'tag_category_id' => 1,
                'label' => 'default'
            ],
            [
                'name' => 'Doing',
                'tag_category_id' => 1,
                'label' => 'info'
            ],
            [
                'name' => 'Done',
                'tag_category_id' => 1,
                'label' => 'success'
            ],
            [
                'name' => 'Low',
                'tag_category_id' => 2,
                'label' => 'success'
            ],
            [
                'name' => 'Medium',
                'tag_category_id' => 2,
                'label' => 'warning'
            ],
            [
                'name' => 'High',
                'tag_category_id' => 2,
                'label' => 'danger'
            ]
        ] as $tag) {
            Task__Tag::create($tag);
        }

    }
}