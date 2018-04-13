<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Randock\Ddd\Domain\Shared\Validation\ValidationError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Randock\Ddd\Domain\Shared\Validation\Exception\ValidationException;

abstract class AbstractValidator
{
    /**
     * @return array
     */
    abstract public static function getConstraints(): array;

    /**
     * @param array $data
     *
     * @return ValidationError[]
     */
    public static function validateModel(array $data): array
    {
        $validator = static::createValidator();
        $constraints = static::getConstraints();

        /** @var ValidationError[] $errors */
        $errors = [];

        // loop fields and constraints and validate them
        foreach ($constraints as $field => $constraint) {
            if (array_key_exists($field, $data)) {
                /** @var ConstraintViolationList $errorList */
                $errorList = $validator->validate(
                    $data[$field],
                    $constraint
                );

                if (count($errorList) > 0) {
                    /** @var ConstraintViolation $error */
                    foreach ($errorList->getIterator() as $error) {
                        $errors[] = new ValidationError(
                            $field,
                            $error->getMessage()
                        );
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * @param array $data
     *
     * @throws ValidationException
     */
    public static function guard(array $data)
    {
        $errors = static::validateModel($data);
        if (count($errors) > 0) {
            throw new ValidationException(...$errors);
        }
    }

    /**
     * @return ValidatorInterface
     */
    private static function createValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }
}
