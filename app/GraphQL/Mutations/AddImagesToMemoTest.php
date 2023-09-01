<?php 

namespace App\GraphQL\Mutations;

use App\Models\MemoTest;
use GraphQL\Error\Error;

class AddImagesToMemoTest
{
    public function __invoke($rootValue, array $args)
    {
        $id = $args['id'];
        $imagesToAdd = $args['images'];

        $memoTest = MemoTest::find($id);

        if (!$memoTest) {
            throw new Error('MemoTest not found');
        }

        // Add images to the current images
        $existingImages = json_decode($memoTest->images, true);
        $updatedImages = array_merge($existingImages, $imagesToAdd);

        // Update the images in the database
        $memoTest->images = json_encode($updatedImages);
        $memoTest->save();

        return $memoTest;
    }
}
