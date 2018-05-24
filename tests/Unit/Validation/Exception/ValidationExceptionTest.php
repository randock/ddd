<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Validation\Exception;

use PHPUnit\Framework\TestCase;
use Randock\Ddd\Validation\Exception\ValidationException;
use Tests\Randock\Ddd\Unit\Validation\AbstractValidationTest;
use Tests\Randock\Ddd\Unit\Validation\Validator\TestValidator;

class ValidationExceptionTest extends TestCase
{
    public function testGetErrors()
    {
        try {
            TestValidator::guard(
                [
                    TestValidator::FIELD_NAME => AbstractValidationTest::SET_INVALID_NAME_VALUE,
                ]
            );
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertSame(count($errors), 1);
        }
    }
}
