<?php

declare(strict_types=1);

namespace Randock\Ddd\Workflow;

use Randock\Ddd\Workflow\Exception\WorkflowException;

abstract class AbstractWorkflow
{
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
     * @param string $place
     * @param string $transition
     *
     * @return bool
     */
    public function can(string $place, string $transition)
    {
        $transitions = static::getTransitions();
        self::guardTransition($transition, $transitions);

        $from = (array) $transitions[$transition]['from'];

        return in_array($place, $from);
    }

    /**
     * @param string $place
     * @param string $transition
     *
     * @throws WorkflowException
     *
     * @return string
     */
    public function apply(string $place, string $transition)
    {
        if (!self::can($place, $transition)) {
            throw new WorkflowException(
                sprintf('The transition (%s) can not be applied from (%s)', $transition, $place)
            );
        }

        return $place;
    }

    /**
     * @throws WorkflowException
     */
    private function guardSchemaTransitions()
    {
        foreach (static::getTransitions() as $transition) {
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
    private function guardTransition(string $transition, array $transitions)
    {
        if (!array_key_exists($transition, $transitions)) {
            throw new WorkflowException(
                sprintf('The transition (%s) does not exist', $transition)
            );
        }
    }
}
