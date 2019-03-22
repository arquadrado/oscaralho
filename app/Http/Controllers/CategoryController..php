<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\CategoryService;
use App\Http\Requests\SaveCategory;
use App\Http\Requests\DeleteCategory;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
  private $categoryService;

  public function __construct(CategoryService $categoryService)
  {
    $this->middleware('auth');
    $this->categoryService = $categoryService;
  }

  public function save(SaveCategory $request) {
    $category = $this->categoryService->save($request->validated());
    return response()->json(['category' => $category], 200);
  }
  
  public function delete(DeleteCategory $request) {
    $this->categoryService->delete($request->validated());
    return response()->json(['message' => 'Category was deleted'], 200);
  }
}
