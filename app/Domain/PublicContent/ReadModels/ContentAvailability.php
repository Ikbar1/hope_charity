<?php

namespace App\Domain\PublicContent\ReadModels;

enum ContentAvailability: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
}
