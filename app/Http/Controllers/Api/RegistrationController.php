<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'mobileNumber' => 'required|string|max:20',
            'workStatus' => 'required|in:experienced,fresher',
            'resume' => 'required|file|mimes:doc,docx,pdf,rtf|max:2048',
             //'whatsappUpdates' => 'boolean',
        ]);

        // Handle file upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobileNumber,
            'work_status' => $request->workStatus,
            'resume_path' => $resumePath,
            'whatsapp_updates' => $request->whatsappUpdates,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
