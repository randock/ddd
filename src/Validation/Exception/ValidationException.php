<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation\Exception;

use Randock\Ddd\Validation\ValidationError;

class ValidationException extends \Exception
{
    /**
     * @var ValidationError[]
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param ValidationError ...$errors
     */
    public function __construct(ValidationError ...$errors)
    {
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
