<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MLocation;

class MLocationController extends Controller
{
    public function getLocation(Request $request)
    {
        $query = $request->input('query');
        $location = MLocation::where('location_name', 'LIKE', "%{$query}%")->get();
        return response()->json($location);
    }
}
