<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Builders\SpaceMissionQueryBuilder;
use App\Http\Resources\V1\SpaceMissionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpaceMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $missions = new SpaceMissionQueryBuilder()
            ->applyFilters($request)
            ->orderByLaunchDate()
            ->paginate();

        return SpaceMissionResource::collection($missions);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
