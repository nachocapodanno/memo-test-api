<?php

namespace App\GraphQL\Mutations;

use App\Enums\GameSessionStateEnum;
use App\Models\GameSession;
use GraphQL\Error\Error;

final readonly class EndGameSession
{
    public function __invoke($_, array $args)
    {
        $sessionId = $args['id'];
        $gameSession = GameSession::find($sessionId);

        if (!$gameSession) {
            throw new Error("Game Session with ID {$args['id']} not found.");
        }

        $gameSession->state = GameSessionStateEnum::COMPLETED;
        $gameSession->save();

        return $gameSession;
    }
}
