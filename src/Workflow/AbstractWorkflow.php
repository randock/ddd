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
     * @param string $transiton
     *
     * @throws WorkflowException
     *
     * @return mixed
     */
    public static function apply(string $place, string $transiton)
    {
        $transitions = static::getTransitions();
        static::validateSchemaTransitions($transitions);
        static::validateTransition($transiton, $transitions);

        if (in_array($place, $transitions[$transiton]['from'])) {
            return $place;
        }

        throw new WorkflowException(
            sprintf('The transition (%s) can not be applied from (%s)', $transiton, $place)
        );
    }

    /**
     * @param array $transitions
     *
     * @throws WorkflowException
     */
    private static function validateSchemaTransitions(array $transitions)
    {
        foreach ($transitions as $transition) {
            if (!is_array($transition)) {
                throw new WorkflowException('The transition has to be of type array');
            }

            if (!array_key_exists('from', $transition)) {
                throw new WorkflowException('One of the transitions does not contain the index "from"');
            }

            if (!is_array($transition['from'])) {
                throw new WorkflowException('The index "from" has to be of type array');
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
    private static function validateTransition(string $transition, array $transitions)
    {
        if (!array_key_exists($transition, $transitions)) {
            throw new WorkflowException(
                sprintf('The transition (%s) does not exist', $transition)
            );
        }
    }
}
