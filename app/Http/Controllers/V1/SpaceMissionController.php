<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Builders\SpaceMissionQueryBuilder;
use App\Http\Requests\V1\StoreSpaceMissionRequest;
use App\Http\Resources\V1\SpaceMissionResource;
use App\Models\SpaceMission;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpaceMissionController extends Controller
{
    // public function index(Request $request): AnonymousResourceCollection
    // {
    //     $missions = new SpaceMissionQueryBuilder()
    //         ->applyFilters($request)
    //         ->orderByLaunchDate()
    //         ->paginate();

    //     return SpaceMissionResource::collection($missions);
    // }
    public function index(): AnonymousResourceCollection
    {
        $spaceMissions = SpaceMission::filtered()
            ->paginate();

        return SpaceMissionResource::collection($spaceMissions);
    }

    public function indexWithPipeline(): AnonymousResourceCollection
    {
        $missions = SpaceMission::filteredWithPipeline()
            ->paginate();

        return SpaceMissionResource::collection($missions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpaceMissionRequest $request): SpaceMissionResource
    {
        $spaceMission = SpaceMission::create($request->validated());

        return new SpaceMissionResource($spaceMission);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpaceMission $spaceMission): SpaceMissionResource
    {
        return new SpaceMissionResource($spaceMission);
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
