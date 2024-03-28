<?php

namespace App\Http\Controllers\communication\email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Communication\Email\EmailMenualService;

class MenualEmailController extends Controller
{

     public function __construct(private EmailMenualService $emailMenualService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return $this->emailMenualService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->emailMenualService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->emailMenualService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->emailMenualService->edit($id);
    }


}
