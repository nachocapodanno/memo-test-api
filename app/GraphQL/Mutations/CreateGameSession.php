<?php

namespace App\GraphQL\Mutations;

use App\Models\GameSession;
use App\Models\MemoTest;
use GraphQL\Error\Error;

class CreateGameSession
{
    public function __invoke($_, array $args)
    {
        // Verificar la autenticación del usuario si es necesario
        // $user = Auth::user();
        $memoTest = MemoTest::find($args['memoTestId']);
        if (!$memoTest) {
            throw new Error("MemoTest with ID {$args['memoTestId']} not found.");
        }

        $gameSession = new GameSession([
            'memo_test_id' => $args['memoTestId'],
        ]);

        $gameSession->save();
        return GameSession::find($gameSession->id);
    }
}
