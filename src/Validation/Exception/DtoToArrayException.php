<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Exception;

class DtoToArrayException extends \Exception
{
    /**
     * @var string
     */
    private $className;

    /**
     * DtoToArrayException constructor.
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        parent::__construct($this->errorMessage());
    }

    /**
     * @return string
     */
    protected function errorMessage(): string
    {
        return \sprintf('object (%s) must implement the interface DtoToArrayInterface', $this->className);
    }
}
