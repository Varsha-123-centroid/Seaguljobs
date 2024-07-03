<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CJobOpening;
use App\Models\CJobDescription;
class CJobOpeningController extends Controller
{
    public function getMinimumExp()
    {
      //  $query = $request->input('query');
        $minimumExperience = CJobOpening::distinct()
        ->pluck('minimumexperince');
        return response()->json($minimumExperience);
    }
    public function getJobs(Request $request)
    {
      
   $validated = $request->validate([

    ]);

    $skils = $request->input('skils');
    $location = $request->input('location');
    $exp = $request->input('exp');

    // Query the job openings based on the request parameters
    $openings = CJobOpening::select('c_job_openings.*','c_employers.companyname','c_employers.company_logo','m_locations.location_name','m_industries.industryname')
                            ->join('c_employers', 'c_job_openings.employerid', '=', 'c_employers.id')
                            ->join('m_locations', 'c_job_openings.location_id', '=', 'm_locations.id')
                            ->join('m_industries', 'c_job_openings.industryid', '=', 'm_industries.id')
                            ->where('designationid', $skils)
                            ->where('location_id', $location) 
                            ->where('minimumexperince', $exp)
                            ->where('showstatus', '0')
                            ->get();
  
    // Check if openings are found
    if ($openings->isEmpty()) {
        return response()->json(['message' => 'No job openings found.'], 404);
    }

    // Return the found job openings as a JSON response
  return response()->json($openings);

    }
    public function getJobDetails(Request $request)
    {
      
      try {
        // Validate the incoming request
        $validated = $request->validate([
            'jobId' => 'required|integer',
            'designationId' => 'required|integer',
        ]);

    $id =$request->input('jobId');
    $designationid = $request->input('designationId');

    $openings = CJobOpening::select('c_job_openings.*','c_job_descriptions.*', 'c_employers.companyname','c_employers.*','m_locations.location_name','m_industries.industryname','m_departments.departmentname')
                            ->join('c_employers', 'c_job_openings.employerid', '=', 'c_employers.id')
                            ->join('m_locations', 'c_job_openings.location_id', '=', 'm_locations.id')
                            ->join('m_industries', 'c_job_openings.industryid', '=', 'm_industries.id')
                            ->join('c_job_descriptions', 'c_job_openings.id', '=', 'c_job_descriptions.jobopeningid')
                            ->join('m_departments', 'c_job_openings.departmentid', '=', 'm_departments.id')
                            ->where('c_job_openings.id', $id)
                            ->get();
  
                            if ($openings->isEmpty()) {
                              return response()->json(['message' => 'No job openings found.'], 404);
                          }
                  
                          // Return the found job openings as a JSON response
                          return response()->json($openings);
                  
                      } catch (\Exception $e) {
                          // Log the exception
                          \Log::error('Error fetching job details: ' . $e->getMessage());
                  
                          // Return a JSON response with the error message
                          return response()->json(['message' => $e->getMessage()], 500);
                      }

    }
}

