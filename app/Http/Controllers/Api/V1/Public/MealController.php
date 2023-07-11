<?php

namespace App\Http\Controllers\Api\V1\Public;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;

/**
 * @group Public endpoints
 */
class MealController extends Controller
{
    /**
     * GET Meals
     *
     * Returns paginated list of meals.
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     */
    public function index()
    {
        $meals = Meal::latest()->paginate();

        return MealResource::collection($meals);
    }

    /**
     * GET Meal
     *
     * Returns a Meal record.
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First meal","description":"This is the first meal","price":"20.00","active":1,"category":{'id":"","title":"First meal","description":"This is the first meal"}}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(Meal $meal)
    {
        return new MealResource($meal);
    }
}
