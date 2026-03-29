<?php

namespace Hotels\Domain\Enums;

enum HotelStatus: string
{
    case ACTIVE = "active";
    case INACTIVE = "inactive";
}
