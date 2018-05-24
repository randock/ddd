<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation\Dto;

class TestValidatorUserInvalid
{
    /**
     * @var string
     */
    private $phone;

    /**
     * TestValidatorUserInvalid constructor.
     *
     * @param string $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }
}
