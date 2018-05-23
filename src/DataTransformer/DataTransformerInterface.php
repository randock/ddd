<?php

declare(strict_types=1);

namespace Randock\Ddd\DataTransformer;

interface DataTransformerInterface
{
    /**
     * @param object $object
     *
     * @return mixed
     */
    public function transform(object $object);
}
