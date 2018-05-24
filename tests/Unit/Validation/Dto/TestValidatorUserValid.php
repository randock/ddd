<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation\Dto;

use Randock\Ddd\Validation\Interfaces\DtoToArrayInterface;

class TestValidatorUserValid implements DtoToArrayInterface
{
    /**
     * @var string
     */
    public const USER_PHONE = '632014523';

    /**
     * @var string
     */
    private $phone;

    /**
     * TestValidatorUserCorrect constructor.
     *
     * @param string $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
        ];
    }
}
