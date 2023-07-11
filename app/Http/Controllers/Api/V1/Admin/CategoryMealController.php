<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryMealRequest;
use App\Http\Requests\UpdateCategoryMealRequest;
use App\Models\CategoryMeal;

class CategoryMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryMealRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryMeal $categoryMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryMealRequest $request, CategoryMeal $categoryMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryMeal $categoryMeal)
    {
        //
    }
}
