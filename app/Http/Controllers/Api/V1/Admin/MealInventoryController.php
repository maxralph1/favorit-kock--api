<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMealInventoryRequest;
use App\Http\Requests\UpdateMealInventoryRequest;
use App\Http\Resources\MealInventoryResource;
use App\Models\MealInventory;

/**
 * @group Admin endpoints
 */
class MealInventoryController extends Controller
{
    /**
     * GET Meal Inventories
     *
     * Returns paginated list of meal inventories.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","meal_id":"01h3hkhxrh15atksjr11hrck0d","plates_prepared":64,"active":null}, ...}
     */
    public function index()
    {
        $mealInventories = MealInventory::withTrashed()
            ->latest()
            ->paginate();

        return MealInventoryResource::collection($mealInventories);
    }

    /**
     * POST Meal Inventory
     *
     * Creates a new Meal Inventory record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","meal_id":"01h3hkhxrh15atksjr11hrck0d","plates_prepared":64,"active":null}, ...}
     */
    public function store(StoreMealInventoryRequest $request)
    {
        $mealInventory = MealInventory::create($request->validated());

        return new MealInventoryResource($mealInventory);
    }

    /**
     * GET Meal Inventory
     *
     * Returns a Meal Inventory record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","meal_id":"01h3hkhxrh15atksjr11hrck0d","plates_prepared":64,"active":null}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(MealInventory $mealInventory)
    {
        return new MealInventoryResource($mealInventory);
    }

    /**
     * PUT Meal Inventory
     *
     * Updates Meal Inventory record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","meal_id":"01h3hkhxrh15atksjr11hrck0d","plates_prepared":64,"active":null}, ...}
     */
    public function update(UpdateMealInventoryRequest $request, MealInventory $mealInventory)
    {
        $mealInventory->update($request->validated());

        return new MealInventoryResource($mealInventory);
    }

    /**
     * DELETE Meal Inventory
     *
     * Deletes Meal Inventory record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(MealInventory $mealInventory)
    {
        $mealInventory->delete();

        return response()->noContent();
    }
}
