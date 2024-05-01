<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CategoryController extends AppBaseController
{
    private $categoryService;

    /**
     * Controller constructor.
     *
     * @param  \App\Services\CategoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getAll()
    {
        $categories = $this->categoryService->getAll();

        return $this->sendResponse($categories, 'List category', Response::HTTP_OK);
    }

    public function getById()
    {
        $category = $this->categoryService->getById();

        return $this->sendResponse($category, 'Get category by id', Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required',
            'name' => 'required',
            'getBy' => ['required', Rule::in(['id', 'class']),]
        ]);

        $categories = $this->categoryService->create($request);

        return $this->sendResponse($categories, 'Sync category', Response::HTTP_OK);
    }

    public function updateById()
    {
        $categories = $this->categoryService->updateById();

        return $this->sendResponse($categories, 'Update a category', Response::HTTP_OK);
    }

    public function delete()
    {
        $categories = $this->categoryService->delete();

        return $this->sendResponse($categories, 'Delete category successfully', Response::HTTP_OK);
    }
}
