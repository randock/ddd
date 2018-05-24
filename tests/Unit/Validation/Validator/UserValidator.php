<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation\Validator;

use Randock\Ddd\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints\Length;

class UserValidator extends AbstractValidator
{
    /**
     * @var string
     */
    public const FIELD_USER_PHONE = 'phone';

    /**
     * @var string
     */
    public const ERROR_USER_PHONE = 'Invalid user phone';

    /**
     * @return array
     */
    public static function getConstraints(): array
    {
        return [
            self::FIELD_USER_PHONE => [
                new Length(
                    [
                        'max' => 9,
                        'maxMessage' => self::ERROR_USER_PHONE,
                    ]
                ),
            ],
        ];
    }
}
