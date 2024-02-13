<?php

namespace App\Services\Entities;

interface BotFunctionInterface
{
    public static function getHandleFunction(): callable;
}
