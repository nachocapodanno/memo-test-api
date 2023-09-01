<?php 

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;
use GraphQL\Error\Error;

class DeleteMemoTest
{
    public function __invoke($rootValue, array $args): bool
    {
        $id = $args['id'];

        // Check if the MemoTest exists
        $memoTest = MemoTest::find($id);

        if (!$memoTest) {
            throw new Error('MemoTest not found');
        }

        $memoTest->delete();
        return true;

    }
}
