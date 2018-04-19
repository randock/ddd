<?php

declare(strict_types=1);

namespace Randock\Ddd\Workflow;

use Randock\Ddd\Workflow\Exception\WorkflowException;

abstract class AbstractWorkflow
{
    /**
     * @return array
     */
    abstract public static function getTransitions(): array;

    /**
     * @param string $place
     * @param string $transition
     *
     * @throws WorkflowException
     *
     * @return string
     */
    public static function apply(string $place, string $transition)
    {
        $transitions = static::getTransitions();
        static::guardSchemaTransitions($transitions);
        static::guardTransition($transition, $transitions);

        $from = (array) $transitions[$transition]['from'];
        if (in_array($place, $from)) {
            return $place;
        }

        throw new WorkflowException(
            sprintf('The transition (%s) can not be applied from (%s)', $transition, $place)
        );
    }

    /**
     * @param array $transitions
     *
     * @throws WorkflowException
     */
    private static function guardSchemaTransitions(array $transitions)
    {
        foreach ($transitions as $transition) {
            if (!is_array($transition)) {
                throw new WorkflowException(
                    sprintf('The transition (%s) has to be of type array', $transition)
                );
            }

            if (!array_key_exists('from', $transition)) {
                throw new WorkflowException('One of the transitions does not contain the index "from"');
            }

            if (!array_key_exists('to', $transition)) {
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
    private static function guardTransition(string $transition, array $transitions)
    {
        if (!array_key_exists($transition, $transitions)) {
            throw new WorkflowException(
                sprintf('The transition (%s) does not exist', $transition)
            );
        }
    }
}
