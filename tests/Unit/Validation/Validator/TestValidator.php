<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation\Validator;

use Randock\Ddd\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestValidator extends AbstractValidator
{
    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const FIELD_USER = 'user';

    /**
     * @var string
     */
    public const FIELD_USERS = 'users';

    /**
     * @var string
     */
    public const ERROR_NAME = 'Invalid name';

    /**
     * @var string
     */
    public const ERROR_TYPE_QUANTITY = 'Invalid quantity';

    /**
     * @return array
     */
    public static function getConstraints(): array
    {
        return [
            self::FIELD_NAME => [
                new NotBlank(
                    [
                        'message' => self::ERROR_NAME,
                    ]
                ),
            ],
            self::FIELD_QUANTITY => [
                new Type(
                    [
                        'type' => 'integer',
                        'message' => self::ERROR_TYPE_QUANTITY,
                    ]
                ),
            ],
            self::FIELD_USER => new UserValidator(),
            self::FIELD_USERS => new UserValidator(),
        ];
    }
}
