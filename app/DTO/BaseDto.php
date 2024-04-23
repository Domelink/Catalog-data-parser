<?php

namespace App\DTO;

abstract class BaseDto
{
    /**
     * @param array<mixed, mixed> $params
     */
    abstract public static function fromArray(array $params): self;
}
