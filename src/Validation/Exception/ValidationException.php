<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Exception;

class ValidationException extends \Exception
{
    /**
     * @var mixed[]
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param mixed[] $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param mixed[] $errors
     *
     * @return ValidationException
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
