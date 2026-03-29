<?php

namespace RoomTypes\Domain\Enums;

enum RoomTypeName: string
{
    case SINGLE = "Single";
    case DOUBLE = "Double";
    case SUITE  = "Suite";
}
