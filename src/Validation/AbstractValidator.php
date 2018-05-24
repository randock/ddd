<?php

declare(strict_types=1);

namespace Randock\Ddd\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Randock\Ddd\Validation\Exception\DtoToArrayException;
use Randock\Ddd\Validation\Exception\ValidationException;
use Randock\Ddd\Validation\Interfaces\DtoToArrayInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    /**
     * @return array
     */
    abstract public static function getConstraints(): array;

    /**
     * @param array $data
     *
     * @return array
     */
    public static function validateModel(array $data): array
    {
        $validator = static::createValidator();
        $constraints = static::getConstraints();

        $errors = [];

        // loop fields and constraints and validate them
        foreach ($constraints as $field => $constraint) {
            if (array_key_exists($field, $data)) {
                if ($constraint instanceof self) {
                    $dataArray = self::transformDataToArray($data[$field]);
                    // $dataArray can return an associative array (['key' => $value, ...]),
                    // or an array with numeric index ([$item1, $item2, ...])
                    if (isset($dataArray[0])) {
                        $countDataArray = count($dataArray);
                        for ($i = 0; $i < $countDataArray; ++$i) {
                            $errorsAbstractValidatorConstraint = $constraint->validateModel(
                                self::transformDataToArray($dataArray[$i])
                            );
                            if (count($errorsAbstractValidatorConstraint) > 0) {
                                $errors[$field][] = $errorsAbstractValidatorConstraint;
                            }
                        }
                    } else {
                        $errorsAbstractValidatorConstraint = $constraint->validateModel($dataArray);
                        if (count($errorsAbstractValidatorConstraint) > 0) {
                            $errors[$field] = $errorsAbstractValidatorConstraint;
                        }
                    }
                } else {
                    /** @var ConstraintViolationList $errorList */
                    $errorList = $validator->validate(
                        $data[$field],
                        $constraint
                    );

                    if (count($errorList) > 0) {
                        /** @var ConstraintViolation $error */
                        foreach ($errorList->getIterator() as $error) {
                            $errors[$field] = $error->getMessage();
                        }
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
            throw new ValidationException($errors);
        }
    }

    /**
     * @return ValidatorInterface
     */
    private static function createValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    /**
     * @param mixed $item
     *
     * @throws DtoToArrayException
     *
     * @return array
     */
    private static function transformDataToArray($item)
    {
        if (is_array($item)) {
            return $item;
        }

        if ($item instanceof DtoToArrayInterface) {
            return $item->toArray();
        }

        throw new DtoToArrayException(get_class($item));
    }
}
