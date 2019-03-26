<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\BudgetService;
use App\Http\Requests\SaveBudget;
use App\Http\Requests\DeleteBudget;
use App\Http\Requests\AddExpense;
use App\Http\Requests\DeleteExpense;
use App\Http\Requests\UpdateBound;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
  private $budgetService;

  public function __construct(BudgetService $budgetService)
  {
    $this->middleware('auth');
    $this->budgetService = $budgetService;
  }

  public function save(SaveBudget $request) {
    $budget = $this->budgetService->save($request->validated());
    return response()->json(['budget' => $budget, 'bounds' => $budget->bounds], 200);
  }

  public function delete(DeleteBudget $request) {
    $this->budgetService->delete($request->validated());
    return response()->json(['message' => 'Budget was deleted'], 200);
  }

  public function addExpense(AddExpense $request) {
    $expense = $this->budgetService->addExpense($request->validated());
    return response()->json(['expense' => $expense], 200);
  }

  public function deleteExpense(DeleteExpense $request) {
    $this->budgetService->deleteExpense($request->validated());
    return response()->json(['message' => 'Expense was deleted'], 200);
  }

  public function updateBound(UpdateBound $request) {
    $bound = $this->budgetService->updateBound($request->validated());
    return response()->json(['bound' => $bound], 200);
  }
}
