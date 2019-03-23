<?php

namespace App\Http\Controllers;

use Auth;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    private $exportService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ExportService $exportService)
    {
        $this->middleware('auth');
        $this->exportService = $exportService;
    }

  public function export($year = null, $month = null) 
	{
	    if (is_null($year)) {
        return $this->exportService->exportAll();
      }
      
      if (is_null($month)) {
        return $this->exportService->exportYear($year);
      }

      return $this->exportService->exportMonth($year, $month);
	}

}
