<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function create()
    {
        return view('website.applications.create');
    }

    public function store(ApplicationStoreRequest $request)
    {
        $request->storeApplication();

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully.',
        ]);
    }
}
