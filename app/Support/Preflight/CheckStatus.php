<?php

namespace App\Support\Preflight;

enum CheckStatus: string
{
    case Pass = 'pass';
    case Fail = 'fail';
    case Unverified = 'unverified';
}
