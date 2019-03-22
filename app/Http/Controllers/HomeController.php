<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\BudgetService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
  private $budgetService;
  private $categoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BudgetService $budgetService, CategoryService $categoryService)
    { 
        $this->budgetService = $budgetService;
        $this->categoryService = $categoryService;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Auth::user();
      if (is_null($user)) {
          return view('error');
      }
      
      $token = csrf_token();
      
      $categories = $this->categoryService->getUserCategories();

      $this->budgetService->initializeCurrentMonthBudget();
      
      $budgets = $this->budgetService->getUserBudgets();
      $bounds = $this->budgetService->getUserBudgetsBounds();

      return view('home', [
          'user' => $user,
          'token' => $token,
          'budgets' => $budgets,
          'bounds' => $bounds,
          'categories' => $categories
      ]);
    }
}
