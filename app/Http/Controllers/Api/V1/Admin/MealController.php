<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;

/**
 * @group Admin endpoints
 */
class MealController extends Controller
{
    /**
     * GET Meals
     *
     * Returns paginated list of meals.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     */
    public function index()
    {
        $meals = Meal::withTrashed()
            ->latest()
            ->paginate();

        return MealResource::collection($meals);
    }

    /**
     * POST Meal
     *
     * Creates a new Meal record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     * @response 422 {"message":"The title field is required.","errors":{"title":["The title field is required."]}, ...}
     */
    public function store(StoreMealRequest $request)
    {
        $meal = Meal::create($request->validated());

        return new MealResource($meal);
    }

    /**
     * GET Meal
     *
     * Returns a Meal record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(Meal $meal)
    {
        return new MealResource($meal);
    }

    /**
     * PUT Meal
     *
     * Updates Meal record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $meal->update($request->validated());

        return new MealResource($meal);
    }

    /**
     * DELETE Meal
     *
     * Deletes Meal record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return response()->noContent();
    }
}
