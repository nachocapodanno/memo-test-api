<?php

namespace App\GraphQL\Queries;

use App\Models\MemoTest;

class MemoTestQuery
{
    public function __invoke($root, array $args)
    {
        return MemoTest::all();
    }

    public function images($root)
    {
        return json_decode($root->images);
    }
}
