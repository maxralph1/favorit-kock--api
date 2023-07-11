<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

/**
 * @group Admin endpoints
 */
class CategoryController extends Controller
{
    /**
     * GET Categories
     *
     * Returns paginated list of categories.
     * 
     * @authenticated
     *
     * @queryParam page integer Page number. Example: 1
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First category","description":"This is the first category"}, ...}
     */
    public function index()
    {
        $categories = Category::withTrashed()
            ->latest()
            ->paginate();

        return CategoryResource::collection($categories);
    }

    /**
     * POST Category
     *
     * Creates a new Category record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First category","description":"This is the first category"}, ...}
     * @response 422 {"message":"The title field is required.","errors":{"title":["The title field is required."]}, ...}
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * GET Category
     *
     * Returns a Category record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First category","description":"This is the first category"}, ...}
     * @response 404 {"message":"Record not found."}
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * PUT Category
     *
     * Updates Category record.
     *
     * @authenticated
     *
     * @response {"data":{"id":"01h3hkhxrh15atksjr11hrck0d","title":"First category","description":"This is the first category"}, ...}
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * DELETE Category
     *
     * Deletes Category record.
     * 
     * @authenticated
     *
     * @response 204 {}
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
