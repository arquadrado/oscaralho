<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\BudgetService;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
  private $budgetService;

  public function __construct(BudgetService $budgetService)
  {
    $this->middleware('auth');
    $this->budgetService = $budgetService;
  }

  public function save(Request $request) {
    
    $budget = $this->budgetService->save($request->all());
    
    return response()->json(['budget' => $budget, 'bounds' => $budget->bounds], 200);
  }
    
  public function delete(Request $request) {

    $budget = $this->budgetService->delete($request->all());
    
    return response()->json(['message' => 'Budget was deleted'], 200);
  }
}
