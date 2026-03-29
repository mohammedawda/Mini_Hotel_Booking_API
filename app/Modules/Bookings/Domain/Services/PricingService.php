<?php

namespace Bookings\Domain\Services;

use Carbon\Carbon;

class PricingService
{
    /**
     * Calculate the total price based on base price, stay duration, and weekend rules.
     */
    public function calculateTotalPrice(float $basePrice, string $checkIn, string $checkOut, int $roomsCount = 1): float
    {
        $startDate = Carbon::parse($checkIn);
        $endDate   = Carbon::parse($checkOut);
        $totalPrice = 0;

        $nights = $startDate->diffInDays($endDate);

        // If stay duration is 0 (same day check-in/out), treat as 1 night (or as per hotel policy, here 1)
        if ($nights === 0) {
            $nights = 1;
        }

        for ($i = 0; $i < $nights; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dailyRate = $basePrice;

            // Weekend surcharge: +20% (Carbon uses 6 for Saturday, 0 for Sunday by default)
            // Carbon's isWeekend() checks if it's Sat or Sun.
            if ($currentDate->isWeekend()) {
                $dailyRate *= 1.2;
            }

            $totalPrice += $dailyRate;
        }

        // Long stay discount: 10% for stays >= 5 nights
        if ($nights >= 5) {
            $totalPrice *= 0.9;
        }

        return round($totalPrice * $roomsCount, 2);
    }
}
