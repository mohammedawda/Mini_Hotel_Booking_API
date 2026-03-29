<?php

namespace RoomTypes\Http\Controllers;

use App\Http\Controllers\Controller;
use RoomTypes\Application\Services\RoomTypeService;
use RoomTypes\Http\Requests\StoreRoomTypeRequest;
use RoomTypes\Http\Requests\UpdateRoomTypeRequest;
use RoomTypes\Http\Resources\RoomTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomTypesController extends Controller
{
    public function __construct(
        private RoomTypeService $roomTypeService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return RoomTypeResource::collection($this->roomTypeService->getAllRoomTypes());
    }

    public function show(int $id): RoomTypeResource|JsonResponse
    {
        $roomType = $this->roomTypeService->getRoomTypeById($id);
        if (!$roomType) {
            return response()->json(["message" => "Room Type not found"], 404);
        }
        return new RoomTypeResource($roomType);
    }

    public function store(StoreRoomTypeRequest $request): RoomTypeResource
    {
        $roomType = $this->roomTypeService->createRoomType($request->validated());
        return new RoomTypeResource($roomType);
    }

    public function update(UpdateRoomTypeRequest $request, int $id): RoomTypeResource|JsonResponse
    {
        $success = $this->roomTypeService->updateRoomType($id, $request->validated());
        if (!$success) {
            return response()->json(["message" => "Room Type not found"], 404);
        }
        return new RoomTypeResource($this->roomTypeService->getRoomTypeById($id));
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->roomTypeService->deleteRoomType($id);
        if (!$success) {
            return response()->json(["message" => "Room Type not found"], 404);
        }
        return response()->json(["message" => "Room Type deleted successfully"]);
    }
}
