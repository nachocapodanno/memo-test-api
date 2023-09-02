<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class MemoTestTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function test_memoTests_query()
    {

        // exercise
        $response = $this->graphQL('
            query {
                memoTests(first: 1) {
                    data {
                    id
                    name
                    images
                    }
                    paginatorInfo {
                    currentPage
                    lastPage
                    }
                }
            }
        ');

        // asserts
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'memoTests' => [
                    'data' => [['id', 'name']],
                ],
            ],
        ]);
    }


    public function test_memoTest_findById_query()
    {
        // exercise
        $response = $this->graphQL('
            query {
                memoTest(id: 1) {
                    id
                    name
                }
            }
        ');

        // asserts
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'memoTest' => [
                    'id' => 1,
                    'name' => 'Pokemon Memo',
                ],
            ],
        ]);
    }

    public function test_createMemoTest_mutation()
    {
        // set up
        $mutationData = [
            'input' => [
                'name' => 'Nuevo Memo Test',
                'images' => [
                    'https://example.com/image1.jpg',
                    'https://example.com/image2.jpg',
                ],
            ],
        ];

        // exercise
        $response = $this->graphQL('
            mutation CreateMemoTest($input: CreateMemoTestInput!) {
                createMemoTest(input: $input) {
                    id
                    name
                    images
                }
            }
        ', $mutationData);

        // asserts
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'createMemoTest' => [
                    'id' => 3,
                    'name' => 'Nuevo Memo Test',
                    'images' => [
                        'https://example.com/image1.jpg',
                        'https://example.com/image2.jpg',
                    ],
                ],
            ],
        ]);
    }

    public function test_createMemoTest_mutation_withWrongURLFormat_throwsError()
    {
        // set up
        $expectedMessage = 'Validation failed for the field [createMemoTest].';
        $mutationData = [
            'input' => [
                'name' => 'Nuevo Memo Test',
                'images' => [
                    'wrongURLFormat',
                    'https://example.com/image2.jpg',
                ],
            ],
        ];

        // exercise
        $response = $this->graphQL('
            mutation CreateMemoTest($input: CreateMemoTestInput!) {
                createMemoTest(input: $input) {
                    id
                    name
                    images
                }
            }
        ', $mutationData);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_deleteMemoTest_mutation()
    {
        // set up
        $memoTestIdToDelete = 1;

        // exercise
        $response = $this->graphQL('
            mutation DeleteMemoTest($id: ID!) {
                deleteMemoTest(id: $id)
            }
        ', [
            'id' => $memoTestIdToDelete,
        ]);

        // asserts
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'deleteMemoTest' => true,
            ],
        ]);
        $this->assertDatabaseMissing('memo_tests', [
            'id' => $memoTestIdToDelete,
        ]);
    }

    public function test_deleteNonExistentMemoTest_mutation()
    {
        // set up
        $nonExistentMemoTestId = 999;
        $expectedMessage = 'MemoTest not found';

        // exercise
        $response = $this->graphQL('
            mutation DeleteMemoTest($id: ID!) {
                deleteMemoTest(id: $id)
            }
        ', [
            'id' => $nonExistentMemoTestId,
        ]);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_addImagesToMemoTest_mutation_with_invalid_memoTest()
    {
        // set up   
        $invalidMemoTestId = 999;
        $expectedMessage = 'MemoTest not found';
        $imagesToAdd = [
            'https://example.com/image1.jpg',
            'https://example.com/image2.jpg',
        ];

        // exercise
        $response = $this->graphQL('
            mutation AddImagesToMemoTest($id: ID!, $images: [String!]!) {
                addImagesToMemoTest(id: $id, images: $images) {
                    id
                    name
                    images
                }
            }
        ', [
            'id' => $invalidMemoTestId,
            'images' => $imagesToAdd,
        ]);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }


    public function test_addImagesToMemoTest_mutation_with_invalid_urls_memoTest()
    {
        // set up   
        $memoTestId = 1;
        $expectedMessage = 'Validation failed for the field [addImagesToMemoTest].';
        $imagesToAdd = [
            'example.com/image1.jpg',
        ];

        // exercise
        $response = $this->graphQL('
            mutation AddImagesToMemoTest($id: ID!, $images: [String!]!) {
                addImagesToMemoTest(id: $id, images: $images) {
                    id
                    name
                    images
                }
            }
        ', [
            'id' => $memoTestId,
            'images' => $imagesToAdd,
        ]);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_addImagesToMemoTest_mutation()
    {
        // set up
        $memoTestId = 1;
        $imagesToAdd = [
            'https://example.com/image3.jpg',
            'https://example.com/image4.jpg',
        ];

        // exercise
        $response = $this->graphQL('
            mutation AddImagesToMemoTest($id: ID!, $images: [String!]!) {
                addImagesToMemoTest(id: $id, images: $images) {
                    id
                    name
                    images
                }
            }
        ', [
            'id' => $memoTestId,
            'images' => $imagesToAdd,
        ]);

        // asserts
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'addImagesToMemoTest' => [
                    'id' => $memoTestId,
                    'name' => 'Pokemon Memo',
                    'images' => [
                        'https://heytutor-memotest.s3.amazonaws.com/bulbasaur.png',
                        'https://heytutor-memotest.s3.amazonaws.com/charmander.png',
                        'https://heytutor-memotest.s3.amazonaws.com/squirtle.png',
                        'https://heytutor-memotest.s3.amazonaws.com/pikachu.png',
                        'https://example.com/image3.jpg',
                        'https://example.com/image4.jpg',
                    ],
                ],
            ],
        ]);
    }

    public function test_removeImagesFromMemoTest_mutation_with_invalid_memoTest()
    {
        // set up
        $invalidMemoTestId = 999;
        $expectedMessage = 'MemoTest not found';
        $imagesToRemove = [
            'https://heytutor-memotest.s3.amazonaws.com/pikachu.png'
        ];

        // exercise
        $response = $this->graphQL('
        mutation RemoveImagesFromMemoTest($id: ID!, $images: [String!]!) {
            removeImagesFromMemoTest(id: $id, images: $images) {
                id
                name
                images
            }
        }
    ', [
            'id' => $invalidMemoTestId,
            'images' => $imagesToRemove,
        ]);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_removeImagesFromMemoTest_mutation_with_invalid_urls_memoTest()
    {
        // set up
        $memoTestId = 1;
        $expectedMessage = 'Validation failed for the field [removeImagesFromMemoTest].';
        $imagesToRemove = [
            '://heytutor-memotest.s3.amazonaws/pikachu.png'
        ];

        // exercise
        $response = $this->graphQL('
        mutation RemoveImagesFromMemoTest($id: ID!, $images: [String!]!) {
            removeImagesFromMemoTest(id: $id, images: $images) {
                id
                name
                images
            }
        }
    ', [
            'id' => $memoTestId,
            'images' => $imagesToRemove,
        ]);

        // asserts
        $response->assertGraphQLErrorMessage($expectedMessage);
    }

    public function test_removeImagesFromMemoTest_mutation()
    {
        // set up
        $memoTestId = 1;
        $imagesToRemove = [
            'https://heytutor-memotest.s3.amazonaws.com/pikachu.png'
        ];

        // exercise
        $response = $this->graphQL('
            mutation RemoveImagesFromMemoTest($id: ID!, $images: [String!]!) {
                removeImagesFromMemoTest(id: $id, images: $images) {
                    id
                    name
                    images
                }
            }
        ', [
            'id' => $memoTestId,
            'images' => $imagesToRemove,
        ]);

        // asserts
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'removeImagesFromMemoTest' => [
                    'id' => $memoTestId,
                    'name' => 'Pokemon Memo',
                    'images' => [
                        'https://heytutor-memotest.s3.amazonaws.com/bulbasaur.png',
                        'https://heytutor-memotest.s3.amazonaws.com/charmander.png',
                        'https://heytutor-memotest.s3.amazonaws.com/squirtle.png',
                    ],
                ],
            ],
        ]);

        $this->assertDatabaseMissing('memo_tests', [
            'id' => $memoTestId,
            'images' => json_encode($imagesToRemove),
        ]);
    }
}
