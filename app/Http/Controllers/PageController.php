<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Doctor;

class PageController extends Controller
{
    public function home()
    {
        // ✅ Get serving tokens
        $servingTokens = Token::where('status', 'serving')
                               ->orderBy('created_at', 'asc')
                               ->get();
        
        // ✅ Get user's own token
        $tokenNumber = session('current_token');
        $userToken = null;
        if ($tokenNumber) {
            $userToken = Token::where('token_number', $tokenNumber)
                              ->whereIn('status', ['waiting', 'calling', 'serving'])
                              ->first();
        }
        
        // ✅ All departments status
        $departments = ['OPD', 'Pharmacy', 'Radiology'];
        $allDepartments = [];
        foreach ($departments as $dept) {
            $allDepartments[$dept] = [
                'total' => Token::where('department', $dept)
                                ->whereIn('status', ['waiting', 'calling', 'serving'])
                                ->count(),
                'serving' => Token::where('department', $dept)
                                  ->where('status', 'serving')
                                  ->first()
            ];
        }
        
        // ✅ Get all doctors from database
        $doctors = Doctor::all();
        
        // ✅ Return view with all data
        return view('Pages.home', compact('servingTokens', 'userToken', 'allDepartments', 'doctors'));
    }
    
    public function about()
    {
        return view('Pages.about');
    }
    
    public function services()
    {
        return view('Pages.services');
    }
    
    public function contact()
    {
        return view('Pages.contact');
    }
    
    public function login()
    {
        return view('Pages.login');
    }
    
    public function sign()
    {
        return view('Pages.sign');
    }
    
    public function booking()
    {
        return view('Pages.booking');
    }
    
    public function Doctors()
    {
        $doctors = Doctor::all();
        return view('Pages.Doctors', compact('doctors'));
    }
    
    public function Status(Request $request)
    {
        // ✅ Get token from session or request
        $tokenNumber = session('current_token') ?? $request->query('token');
        
        $token = null;
        $nowServing = 'N/A';
        
        if ($tokenNumber) {
            $token = Token::where('token_number', $tokenNumber)->first();
            
            // ✅ Get currently serving token
            $servingToken = Token::where('status', 'serving')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($servingToken) {
                $nowServing = $servingToken->token_number;
            }
        }
        
        return view('Pages.Status', compact('token', 'nowServing'));
    }
    
    public function Token_form()
    {
        return view('Pages.Token_form');
    }
    
    public function Staff()
    {
        return view('Pages.Staff');
    }
    
    public function Doctor_management()
    {
        return view('Pages.Admin.Doctor_management');
    }
    
    public function report()
    {
        return view('Pages.Admin.report');
    }
    
    public function services_management()
    {
        return view('Pages.Admin.services_management');
    }
    
    public function settings()
    {
        return view('Pages.Admin.setting');
    }
    
    public function user_management()
    {
        return view('Pages.Admin.user_management');
    }
}