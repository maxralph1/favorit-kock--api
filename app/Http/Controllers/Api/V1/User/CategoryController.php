<?php

namespace App\Http\Controllers\Api\V1\User;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

/**
 * @group User endpoints
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
        $categories = Category::latest()->paginate();

        return CategoryResource::collection($categories);
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
}
