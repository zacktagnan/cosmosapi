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
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;

#[Group('Space Missions', 'Manage space missions - create, read, update and delete missions to different planets and destinations.')]
#[Authenticated()]
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
    /**
     * Listado paginado/filtrado simple
     *
     * Lista completa de SpaceMission disponibles.
     *
     * @return AnonymousResourceCollection
     *
     * @deprecated Este ENDPOINT dejará de ser útil en favor del que usa Pipeline.
     */
    public function index(): AnonymousResourceCollection
    {
        $spaceMissions = SpaceMission::filtered()
            ->paginate();

        return SpaceMissionResource::collection($spaceMissions);
    }

    /**
     * Listado paginado/filtrado con Pipeline
     *
     * Lista completa de SpaceMission disponibles.
     *
     * @return AnonymousResourceCollection
     */
    public function indexWithPipeline(): AnonymousResourceCollection
    {
        $missions = SpaceMission::filteredWithPipeline()
            ->paginate();

        return SpaceMissionResource::collection($missions);
    }

    /**
     * Crear
     *
     * Almacenar un nuevo registro en la base de datos.
     *
     * @param StoreSpaceMissionRequest $request
     * @return SpaceMissionResource
     */
    public function store(StoreSpaceMissionRequest $request): SpaceMissionResource
    {
        $spaceMission = $this->createSpaceMissionAction->execute($request->validated());

        return new SpaceMissionResource($spaceMission);
    }

    /**
     * Detalle
     *
     * Vista detallada de todas las claves del registro.
     *
     * @param SpaceMission $spaceMission
     * @return SpaceMissionResource
     */
    public function show(SpaceMission $spaceMission): SpaceMissionResource
    {
        return new SpaceMissionResource($spaceMission);
    }

    /**
     * Actualizar
     *
     * Modificar en la base de datos el registro seleccionado.
     *
     * @param UpdateSpaceMissionRequest $request
     * @param SpaceMission $spaceMission
     * @return SpaceMissionResource
     */
    public function update(UpdateSpaceMissionRequest $request, SpaceMission $spaceMission): SpaceMissionResource
    {
        $spaceMission = $this->updateSpaceMissionAction->execute($spaceMission, $request->validated());

        return new SpaceMissionResource($spaceMission);
    }

    /**
     * Eliminar
     *
     * Eliminar de la base de datos el registro especificado.
     *
     * @param SpaceMission $spaceMission
     * @return JsonResponse
     */
    public function destroy(SpaceMission $spaceMission): JsonResponse
    {
        $this->deleteSpaceMissionAction->execute($spaceMission);

        return response()->json(null, 204);
    }
}
