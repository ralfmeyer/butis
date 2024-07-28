<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;


class StellenController extends Controller
{
    public function edit($id): View
    {
        
        return view('stelleedit', 
            ['id' => $id, 
        ]);
    }
}
