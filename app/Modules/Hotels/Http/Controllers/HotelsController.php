<?php

namespace Hotels\Http\Controllers;

use App\Http\Controllers\Controller;
use Hotels\Application\Services\HotelService;
use Hotels\Http\Requests\StoreHotelRequest;
use Hotels\Http\Requests\UpdateHotelRequest;
use Hotels\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HotelsController extends Controller
{
    public function __construct(
        private HotelService $hotelService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return HotelResource::collection($this->hotelService->getAllHotels());
    }

    public function show(int $id): HotelResource|JsonResponse
    {
        $hotel = $this->hotelService->getHotelById($id);
        if (!$hotel) {
            return response()->json(["message" => "Hotel not found"], 404);
        }
        return new HotelResource($hotel);
    }

    public function store(StoreHotelRequest $request): HotelResource
    {
        $hotel = $this->hotelService->createHotel($request->validated());
        return new HotelResource($hotel);
    }

    public function update(UpdateHotelRequest $request, int $id): HotelResource|JsonResponse
    {
        $success = $this->hotelService->updateHotel($id, $request->validated());
        if (!$success) {
            return response()->json(["message" => "Hotel not found"], 404);
        }
        return new HotelResource($this->hotelService->getHotelById($id));
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->hotelService->deleteHotel($id);
        if (!$success) {
            return response()->json(["message" => "Hotel not found"], 404);
        }
        return response()->json(["message" => "Hotel deleted successfully"]);
    }
}
