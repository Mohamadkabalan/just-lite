<?php

namespace App\Http\Controllers;


use DB;

class DietPlanController extends Controller
{



    function fetchDiet($id)
    {
        $diet =\App\WeeklyDietPlan::findOrFail($id);
        return view('diet-plan.plan-details', compact('diet'));
    }
}