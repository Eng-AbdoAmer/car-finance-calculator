<?php
// app/View/Components/Navbar.php

namespace App\View\Components;

use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * حالة المستخدم (مسجل دخول أم لا)
     */
    public $isAuthenticated;
    
    /**
     * اسم المستخدم
     */
    public $userName;
    
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->isAuthenticated = auth()->check();
        $this->userName = $this->isAuthenticated ? auth()->user()->name : null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.navbar');
    }
}