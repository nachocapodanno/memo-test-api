<?php

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateMemoTest
{
    public function __invoke($rootValue, array $args, GraphQLContext $context): MemoTest
    {
        $input = $args['input'];

        $memoTest = new MemoTest([
            'name' => $input['name'],
            'images' => json_encode($input['images']),
        ]);

        $memoTest->save();

        return $memoTest;
    }
}
