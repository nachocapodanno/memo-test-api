<?php

namespace App\GraphQL\Mutations;

use App\Models\GameSession;
use App\Models\MemoTest;
use GraphQL\Error\Error;

class CreateGameSession
{
    public function __invoke($_, array $args)
    {
        $memoTest = MemoTest::find($args['memoTestId']);
        if (!$memoTest) {
            throw new Error("MemoTest with ID {$args['memoTestId']} not found.");
        }

        $gameSession = new GameSession([
            'memo_test_id' => $args['memoTestId'],
            'number_of_pairs' => count(json_decode($memoTest->images)),
        ]);

        $gameSession->save();
        return GameSession::find($gameSession->id);
    }
}
