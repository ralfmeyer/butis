<?php

namespace App\Http\Controllers;

// use Illuminate\Auth\;


use Illuminate\Contracts\View\View;


class BeurteilungController extends Controller
{
	public function indexAction(){

	}
	public function edtiction(){

	}

    public function create($mid): View
    {
        return view('beurteilungcreate',
            ['mid' => $mid,
        ]);

	}



    public function show($id): View
    {

        return view('beurteilungshow',
            ['id' => $id,
        ]);
    }
}
