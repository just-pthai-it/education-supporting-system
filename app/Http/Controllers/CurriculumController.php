<?php

namespace App\Http\Controllers;

use App\Http\Requests\Curriculum\CreateCurriculumPostRequest;
use App\Services\Contracts\CurriculumServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    private CurriculumServiceContract $__curriculumService;

    /**
     * @param CurriculumServiceContract $__curriculumService
     */
    public function __construct(CurriculumServiceContract $__curriculumService)
    {
        $this->__curriculumService = $__curriculumService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCurriculumPostRequest $request
     * @return JsonResponse
     */
    public function store(CreateCurriculumPostRequest $request) : JsonResponse
    {
        return $this->__curriculumService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
