<?php

namespace App\Services;

use Auth;
use App\Models\Category;
use Carbon\Carbon;

class CategoryService
{
  public function getUserCategories() {
    $user = Auth::user();
    return Category::where('user_id', $user->id)->get();
  }

  public function save($data) {
    $requestData = $data;
    $requestData['default_bound_in_cents'] = (int)$requestData['default_bound'] * 100;
    if (!isset($data['id'])) {
      
      $category = Category::create(array_merge(['user_id' => Auth::user()->id], $requestData));
      return $category;
    }
    
    $category = Category::find($data['id']);

    if (is_null($category)) {
      abort(404, 'Could not find category to update');
    }

    $category->update($requestData);

    return $category;
  }
  
  public function delete($data) {
    $category = Category::find($data['id']);
    $category->delete();
    
  }
}