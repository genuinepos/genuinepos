<?php

namespace App\Http\Controllers\communication\sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Communication\Sms\SmsMenualService;

class MenualSmsController extends Controller
{

     public function __construct(private SmsMenualService $smsMenualService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return $this->smsMenualService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->smsMenualService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->smsMenualService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->smsMenualService->edit($id);
    }


}
