<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Exception;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return ValidationException
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
