<?php

declare(strict_types=1);

namespace Randock\Ddd\Validator;

class ValidationError
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $message;

    /**
     * ValidationError constructor.
     *
     * @param string $field
     * @param string $message
     */
    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
