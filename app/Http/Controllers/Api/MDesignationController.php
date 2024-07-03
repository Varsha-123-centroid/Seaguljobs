<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MDesignation;

class MDesignationController extends Controller
{
    public function getDesignation(Request $request)
    {
        $query = $request->input('query');
        $designations = MDesignation::where('designation', 'LIKE', "%{$query}%")->get();
        return response()->json($designations);
    }
}
