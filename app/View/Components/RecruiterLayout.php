<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RecruiterLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('layouts.recruiter');
    }
}
