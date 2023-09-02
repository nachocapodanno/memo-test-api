<?php

namespace Tests\Feature;

use App\Enums\GameSessionStateEnum;
use App\Models\GameSession;
use Tests\TestCase;
use App\Models\MemoTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameSessionTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function test_createGameSession_mutation()
    {
        // set up
        $memoTest = MemoTest::factory()->create();

        // exercise
        $response = $this->graphQL(
            "mutation {
                createGameSession(memoTestId: $memoTest->id) {
                    id
                    memoTestId
                    retries
                    numberOfPairs
                    state
                }
            }"
        );

        // asserts
        $response->assertJson([
            'data' => [
                'createGameSession' => [
                    'memoTestId' => $memoTest->id,
                    'state' => 'started',
                ],
            ],
        ]);

        $this->assertDatabaseHas('game_sessions', [
            'memo_test_id' => $memoTest->id,
        ]);
    }

    public function test_createGameSession_withInvalidMemoTest_mutation()
    {
        // set up
        $invalidMemoTestId = 99;
        $expectedMessage = "MemoTest with ID {$invalidMemoTestId} not found.";

        // exercise
        $response = $this->graphQL(
            "mutation {
                createGameSession(memoTestId: $invalidMemoTestId) {
                    id
                    memoTestId
                    retries
                    numberOfPairs
                    state
                }
            }"
        );

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_endGameSession_mutation()
    {
        // set up
        $gameSession = GameSession::factory()->create();

        // exercise
        $response = $this->graphQL("
            mutation {
                endGameSession(id: $gameSession->id) {
                    id
                    state
                }
            }
        ");

        // asserts
        $response->assertJson([
            'data' => [
                'endGameSession' => [
                    'id' => $gameSession->id,
                    'state' => GameSessionStateEnum::COMPLETED,
                ],
            ],
        ]);
    }

    public function test_endGameSession_with_invalid_gameSession_id_mutation()
    {
        // set up
        $invalidGameSession = 999;
        $expectedMessage = "Game Session with ID {$invalidGameSession} not found.";

        // exercise
        $response = $this->graphQL("
            mutation {
                endGameSession(id: $invalidGameSession) {
                    id
                    state
                }
            }
        ");

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_updateGameSessionRetries_mutation()
    {
        // set up
        $gameSession = GameSession::factory()->create();
        $retries = 5;
        $totalExpectedRetries = $retries + $gameSession->retries;

        // exercise
        $response = $this->graphQL("
            mutation {
                updateGameSessionRetries(id: $gameSession->id, retries: $retries) {
                    id
                    retries
                }
            }
        ");

        // asserts
        $response->assertJson([
            'data' => [
                'updateGameSessionRetries' => [
                    'id' => $gameSession->id,
                    'retries' => $totalExpectedRetries,
                ],
            ],
        ]);
    }

    public function test_updateGameSessionRetries_with_invalid_gameSession_id_mutation()
    {
        // set up
        $retries = 3;
        $invalidGameSession = 999;
        $expectedMessage = "Game Session with ID {$invalidGameSession} not found.";

        // exercise
        $response = $this->graphQL("
            mutation {
                updateGameSessionRetries(id: $invalidGameSession, retries: $retries) {
                    id
                    retries
                }
            }
        ");

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_gameSession_findById_query()
    {
        // set up
        $gameSession = GameSession::factory()->create();

        // exercise
        $response = $this->graphQL("
            query {
                gameSession(id: $gameSession->id) {
                    id
                    memoTestId
                    retries
                    numberOfPairs
                    state
                }
            }
        ");

        // asserts
        $response->assertJson([
            'data' => [
                'gameSession' => [
                    'id' => $gameSession->id,
                    'memoTestId' => $gameSession->memo_test_id,
                    'retries' => $gameSession->retries,
                    'numberOfPairs' => $gameSession->number_of_pairs,
                    'state' => $gameSession->state,
                ],
            ],
        ]);
    }
}
