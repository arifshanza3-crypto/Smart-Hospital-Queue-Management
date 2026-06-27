<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('Pages.home');
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
    public function Status(){
        return view('Pages.Status');
    }
    public function Token_form(){
        return view('Pages.Token_form');
    }
    public function Staff(){
        return view('Pages.Staff');
    }
    public function Doctor_management(){
        return view('Pages.Admin.Doctor_management');
    }
    public function report(){
        return view('Pages.Admin.report');
    }
    public function services_management(){
        return view('Pages.Admin.services_management');
    }
    public function settings(){
        return view('Pages.Admin.setting');
    }
    public function user_management(){
        return view('Pages.Admin.user_management');
    }
}

