<?php 

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;
use GraphQL\Error\Error;

class RemoveImagesFromMemoTest
{
    public function __invoke($rootValue, array $args)
    {
        $id = $args['id'];
        $imagesToDelete = $args['images'];

        $memoTest = MemoTest::find($id);

        if (!$memoTest) {
            throw new Error('MemoTest not found');
        }

        // Remove images from the current images
        $existingImages = json_decode($memoTest->images, true);
        $updatedImages = array_diff($existingImages, $imagesToDelete);

        // Update the images in the database
        $memoTest->images = json_encode(array_values($updatedImages));
        $memoTest->save();

        return $memoTest;
    }
}