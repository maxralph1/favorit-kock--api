<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMealImageRequest;
use App\Http\Requests\UpdateMealImageRequest;
use App\Http\Resources\MealImageResource;
use App\Models\MealImage;

/**
 * @group Admin endpoints
 */
class MealImageController extends Controller
{
    /**
     * GET Meal Images
     *
     * Returns paginated list of meal images.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","image_url":"https://via.placeholder.com/133x422.png/00dd55?tex...","meal":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{"id":"","title":"First meal","description":"This is the first meal"}},"default":1}, ...}
     */
    public function index()
    {
        $mealImages = MealImage::withTrashed()
            ->latest()
            ->paginate();

        return MealImageResource::collection($mealImages);
    }

    /**
     * POST Meal Images
     *
     * Creates a new Meal Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{"id":"","title":"First meal","description":"This is the first meal"}}, ...}
     * @response 422 {"message":"The title field is required.","errors":{"title":["The title field is required."]}, ...}
     */
    public function store(StoreMealImageRequest $request)
    {
        $mealImage = MealImage::create($request->validated());

        return new MealImageResource($mealImage);
    }

    /**
     * GET Meal Image
     *
     * Returns a Meal Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{"id":"","title":"First meal","description":"This is the first meal"}}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(MealImage $mealImage)
    {
        return new MealImageResource($mealImage);
    }

    /**
     * PUT Meal Image
     *
     * Updates Meal Image record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{"id":"","title":"First meal","description":"This is the first meal"}}, ...}
     */
    public function update(UpdateMealImageRequest $request, MealImage $mealImage)
    {
        $mealImage->update($request->validated());

        return new MealImageResource($mealImage);
    }

    /**
     * DELETE Meal Image
     *
     * Deletes Meal Image record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(MealImage $mealImage)
    {
        $mealImage->delete();

        return response()->noContent();
    }
}
