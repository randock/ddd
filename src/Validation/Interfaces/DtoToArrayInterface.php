<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Interfaces;

interface DtoToArrayInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}
