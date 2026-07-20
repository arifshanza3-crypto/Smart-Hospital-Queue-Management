<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;

class PageController extends Controller
{
    public function home()
    {
        // ✅ Get all tokens with status 'serving' from all departments
        $servingTokens = Token::where('status', 'serving')
                               ->orderBy('department', 'asc')
                               ->get();
        
        // ✅ Get user's own token from session
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
        
        return view('Pages.home', compact('servingTokens', 'userToken', 'allDepartments'));
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
        return view('Pages.Doctors');
    }
    
    public function Status()
    {
        return view('Pages.Status');
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