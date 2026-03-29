<?php

namespace RoomTypes\Http\Controllers;

use App\Http\Controllers\Controller;
use RoomTypes\Application\Services\RoomTypeService;
use RoomTypes\Http\Requests\StoreRoomTypeRequest;
use RoomTypes\Http\Requests\UpdateRoomTypeRequest;
use RoomTypes\Http\Resources\RoomTypeResource;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class RoomTypesController extends Controller
{
    use ApiResponse;

    public function __construct(
        private RoomTypeService $roomTypeService
    ) {}

    public function index(): JsonResponse
    {
        $roomTypes = $this->roomTypeService->getAllRoomTypes(request())->paginate(10);
        return $this->sendResponse(RoomTypeResource::collection($roomTypes)->response()->getData(true), "Room Types retrieved successfully");
    }

    public function show(int $id): JsonResponse
    {
        $roomType = $this->roomTypeService->getRoomTypeById($id);
        if (!$roomType) {
            return $this->sendError("Room Type not found", 404);
        }
        return $this->sendResponse(new RoomTypeResource($roomType), "Room Type retrieved successfully");
    }

    public function store(StoreRoomTypeRequest $request): JsonResponse
    {
        $roomType = $this->roomTypeService->createRoomType($request->validated());
        return $this->sendResponse(new RoomTypeResource($roomType), "Room Type created successfully", 201);
    }

    public function update(UpdateRoomTypeRequest $request, int $id): JsonResponse
    {
        $success = $this->roomTypeService->updateRoomType($id, $request->validated());
        if (!$success) {
            return $this->sendError("Room Type not found", 404);
        }
        return $this->sendResponse(new RoomTypeResource($this->roomTypeService->getRoomTypeById($id)), "Room Type updated successfully");
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->roomTypeService->deleteRoomType($id);
        if (!$success) {
            return $this->sendError("Room Type not found", 404);
        }
        return $this->sendResponse(null, "Room Type deleted successfully");
    }
}
