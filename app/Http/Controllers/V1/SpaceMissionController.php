<?php

namespace App\Http\Controllers\V1;

use App\Actions\V1\SpaceMission\CreateSpaceMissionAction;
use App\Actions\V1\SpaceMission\DeleteSpaceMissionAction;
use App\Actions\V1\SpaceMission\UpdateSpaceMissionAction;
use App\Http\Controllers\Controller;
use App\Builders\SpaceMissionQueryBuilder;
use App\Http\Requests\V1\StoreSpaceMissionRequest;
use App\Http\Requests\V1\UpdateSpaceMissionRequest;
use App\Http\Resources\V1\SpaceMissionResource;
use App\Models\SpaceMission;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;


class SpaceMissionController extends Controller
{
    public function __construct(
        private readonly CreateSpaceMissionAction $createSpaceMissionAction,
        private readonly UpdateSpaceMissionAction $updateSpaceMissionAction,
        private readonly DeleteSpaceMissionAction $deleteSpaceMissionAction
    ) {}

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
        $spaceMission = $this->createSpaceMissionAction->execute($request->validated());

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
    public function update(UpdateSpaceMissionRequest $request, SpaceMission $spaceMission): SpaceMissionResource
    {
        $spaceMission = $this->updateSpaceMissionAction->execute($spaceMission, $request->validated());

        return new SpaceMissionResource($spaceMission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpaceMission $spaceMission): JsonResponse
    {
        $this->deleteSpaceMissionAction->execute($spaceMission);

        return response()->json(null, 204);
    }
}
