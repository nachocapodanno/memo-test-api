<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\GameSession;
use GraphQL\Error\Error;

final readonly class UpdateGameSessionRetries
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $sessionId = $args['id'];
        $gameSession = GameSession::find($sessionId);

        if (!$gameSession) {
            throw new Error("Game Session with ID {$args['id']} not found.");
        }

        $gameSession->retries += $args['retries'];
        $gameSession->save();

        return $gameSession;
    }
}
