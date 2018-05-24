<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation;

use PHPUnit\Framework\TestCase;
use Randock\Ddd\Validation\Exception\DtoToArrayException;
use Randock\Ddd\Validation\Exception\ValidationException;
use Tests\Randock\Ddd\Unit\Validation\Validator\TestValidator;
use Tests\Randock\Ddd\Unit\Validation\Validator\UserValidator;
use Tests\Randock\Ddd\Unit\Validation\Dto\TestValidatorUserValid;
use Tests\Randock\Ddd\Unit\Validation\Dto\TestValidatorUserInvalid;

class AbstractValidationTest extends TestCase
{
    /**
     * @var string
     */
    public const VALUE_NAME = 'Test name';

    /**
     * @var int
     */
    public const VALUE_QUANTITY = 5;

    /**
     * @var string
     */
    public const SET_INVALID_PHONE_VALUE = '1203654128';

    /**
     * @var null
     */
    public const SET_INVALID_NAME_VALUE = null;

    public function testValidateModelValid()
    {
        $errors = TestValidator::validateModel(
            [
                TestValidator::FIELD_NAME => self::VALUE_NAME,
                TestValidator::FIELD_QUANTITY => self::VALUE_QUANTITY,
            ]
        );

        $this->assertSame(count($errors), 0);
    }

    public function testValidateModelInvalidName()
    {
        $errors = TestValidator::validateModel(
            [
                TestValidator::FIELD_NAME => self::SET_INVALID_NAME_VALUE,
            ]
        );

        $this->assertSame(count($errors), 1);
        $this->assertArrayHasKey(TestValidator::FIELD_NAME, $errors);
        $this->assertSame($errors[TestValidator::FIELD_NAME], TestValidator::ERROR_NAME);
    }

    public function testValidateModelCustomConstraintAbstractValidatorValid()
    {
        $errors = TestValidator::validateModel(
            [
                TestValidator::FIELD_USER => new TestValidatorUserValid(
                    TestValidatorUserValid::USER_PHONE
                ),
            ]
        );

        $this->assertSame(count($errors), 0);
    }

    public function testValidateModelCustomConstraintAbstractValidatorInvalid()
    {
        $errors = TestValidator::validateModel(
            [
                TestValidator::FIELD_USER => new TestValidatorUserValid(
                    self::SET_INVALID_PHONE_VALUE
                ),
            ]
        );

        $this->assertSame(count($errors), 1);
        $this->assertArrayHasKey(TestValidator::FIELD_USER, $errors);
        $this->assertArrayHasKey(UserValidator::FIELD_USER_PHONE, $errors[TestValidator::FIELD_USER]);
        $this->assertSame(UserValidator::ERROR_USER_PHONE, $errors[TestValidator::FIELD_USER][UserValidator::FIELD_USER_PHONE]);
    }

    public function testValidateModelDtoToArrayException()
    {
        $this->expectException(DtoToArrayException::class);
        TestValidator::validateModel(
            [
                TestValidator::FIELD_USER => new TestValidatorUserInvalid(
                    TestValidatorUserValid::USER_PHONE
                ),
            ]
        );
    }

    public function testValidateModelArrayConstraint()
    {
        $users = [
            new TestValidatorUserValid(
            TestValidatorUserValid::USER_PHONE
            ),
            new TestValidatorUserValid(
                self::SET_INVALID_PHONE_VALUE
            ),
        ];

        $errors = TestValidator::validateModel(
            [
                TestValidator::FIELD_USERS => $users,
            ]
        );
        $this->assertSame(count($errors), 1);
        $this->assertArrayHasKey(TestValidator::FIELD_USERS, $errors);
        $this->assertTrue(isset($errors[TestValidator::FIELD_USERS][0]));
        $this->assertArrayHasKey(UserValidator::FIELD_USER_PHONE, $errors[TestValidator::FIELD_USERS][0]);
        $this->assertSame(UserValidator::ERROR_USER_PHONE, $errors[TestValidator::FIELD_USERS][0][UserValidator::FIELD_USER_PHONE]);
    }

    public function testGuardWhitoutErrors()
    {
        TestValidator::guard(
            [
                TestValidator::FIELD_NAME => self::VALUE_NAME,
            ]
        );
        $this->addToAssertionCount(1);
    }

    public function testGuardValidationException()
    {
        $this->expectException(ValidationException::class);
        TestValidator::guard(
            [
                TestValidator::FIELD_NAME => self::SET_INVALID_NAME_VALUE,
            ]
        );
    }
}
