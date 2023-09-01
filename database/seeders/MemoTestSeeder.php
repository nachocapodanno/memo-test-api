<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemoTest;

class MemoTestSeeder extends Seeder
{
    public function run()
    {
        MemoTest::create([
            'name' => 'Pokemon Memo',
            'images' => json_encode([
                'https://heytutor-memotest.s3.amazonaws.com/bulbasaur.png', 
                'https://heytutor-memotest.s3.amazonaws.com/charmander.png', 
                'https://heytutor-memotest.s3.amazonaws.com/squirtle.png', 
                'https://heytutor-memotest.s3.amazonaws.com/pikachu.png', 
            ]),
        ]);

        MemoTest::create([
            'name' => 'Premier League Memo',
            'images' => json_encode([
                'https://heytutor-memotest.s3.amazonaws.com/united.png', 
                'https://heytutor-memotest.s3.amazonaws.com/city.png', 
                'https://heytutor-memotest.s3.amazonaws.com/chelsea.png', 
                'https://heytutor-memotest.s3.amazonaws.com/liverpool.png'
            ]),
        ]);
    }
}
