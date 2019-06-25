<?php

declare(strict_types=1);

namespace Randock\Ddd\Workflow;

use Randock\Ddd\Workflow\Exception\WorkflowException;

abstract class AbstractWorkflow
{
    /**
     * @var string
     */
    private const METHOD_UPDATE = 'update';

    /**
     * AbstractWorkflow constructor.
     */
    public function __construct()
    {
        self::guardSchemaTransitions();
    }

    /**
     * @return array
     */
    abstract public static function getTransitions(): array;

    /**
     * @return string
     */
    abstract public static function getProperty(): string;

    /**
     * @param mixed  $subject
     * @param string $transition
     *
     * @return bool
     */
    public function can($subject, string $transition): bool
    {
        self::guardApplyTransition($subject);

        $transitions = static::getTransitions();
        self::guardTransition($transition, $transitions);

        $from = (array) $transitions[$transition]['from'];

        return \in_array($this->getPlace($subject), $from);
    }

    /**
     * @param mixed  $subject
     * @param string $transition
     *
     * @throws WorkflowException
     */
    public function apply($subject, string $transition): void
    {
        if (!self::can($subject, $transition)) {
            throw new WorkflowException(
                \sprintf('The transition (%s) can not be applied from (%s)', $transition, $this->getPlace($subject))
            );
        }

        $transitions = static::getTransitions();
        $subject->update(
            [
                static::getProperty() => $transitions[$transition]['to'],
            ]
        );
    }

    /**
     * @param mixed $subject
     *
     * @throws WorkflowException
     */
    private function guardApplyTransition($subject): void
    {
        $reflectionClass = new \ReflectionClass(\get_class($subject));

        if (!$reflectionClass->hasMethod($this->getMethodNameProperty())) {
            throw new WorkflowException(
                \sprintf('there is no method get to (%s) property in the object', static::getProperty())
            );
        }

        if (!$reflectionClass->hasMethod(self::METHOD_UPDATE)) {
            throw new WorkflowException(
                \sprintf('there is no method (%s) in the object', self::METHOD_UPDATE)
            );
        }
    }

    /**
     * @param mixed $subject
     *
     * @return string
     */
    private function getPlace($subject): string
    {
        $getMethodNameProperty = $this->getMethodNameProperty();

        return $subject->$getMethodNameProperty();
    }

    /**
     * @return string
     */
    private function getMethodNameProperty(): string
    {
        return \sprintf('get%s', \ucfirst(static::getProperty()));
    }

    /**
     * @throws WorkflowException
     */
    private function guardSchemaTransitions(): void
    {
        foreach (static::getTransitions() as $transition) {
            if (!\is_array($transition)) {
                throw new WorkflowException(
                    \sprintf('The transition (%s) has to be of type array', $transition)
                );
            }

            if (!\array_key_exists('from', $transition)) {
                throw new WorkflowException('One of the transitions does not contain the index "from"');
            }

            if (!\array_key_exists('to', $transition)) {
                throw new WorkflowException('One of the transitions does not contain the index "to"');
            }
        }
    }

    /**
     * @param string $transition
     * @param array  $transitions
     *
     * @throws WorkflowException
     */
    private function guardTransition(string $transition, array $transitions): void
    {
        if (!\array_key_exists($transition, $transitions)) {
            throw new WorkflowException(
                \sprintf('The transition (%s) does not exist', $transition)
            );
        }
    }
}
