<?php

namespace Bookings\Domain\Enums;

enum BookingStatus: string
{
    case PENDING   = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
