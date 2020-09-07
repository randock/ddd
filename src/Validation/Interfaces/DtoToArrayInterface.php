<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Interfaces;

interface DtoToArrayInterface
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;
}
