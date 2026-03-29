<?php

namespace Bookings\Http\Controllers;

use App\Http\Controllers\Controller;
use Bookings\Application\Services\BookingService;
use Bookings\Http\Requests\StoreBookingRequest;
use Bookings\Http\Resources\BookingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;

class BookingsController extends Controller
{
    use ApiResponse;

    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return $this->sendResponse(new BookingResource($booking), "Booking confirmed successfully", 201);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * Show booking details.
     */
    public function show(int $id): JsonResponse
    {
        $booking = $this->bookingService->getBookingById($id);
        if (!$booking) {
            return $this->sendError("Booking not found", 404);
        }
        return $this->sendResponse(new BookingResource($booking), "Booking details retrieved successfully");
    }

    /**
     * Cancel a booking.
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->bookingService->cancelBooking($id);
        if (!$success) {
            return $this->sendError("Booking not found", 404);
        }
        return $this->sendResponse(null, "Booking cancelled successfully");
    }
}
