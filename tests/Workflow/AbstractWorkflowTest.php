<?php

declare(strict_types=1);

namespace Tests\Randock\VignetteApi\Unit\Domain\Order\Model;

use PHPUnit\Framework\TestCase;
use Randock\Ddd\Workflow\AbstractWorkflow;
use Randock\Ddd\Workflow\Exception\WorkflowException;

class TestWorkflow extends AbstractWorkflow
{
    /**
     * @return array
     */
    public static function getTransitions(): array
    {
        return [
            'to_paid' => [
                'from' => 'draft',
                'to' => 'paid',
            ],
            'to_cancelled' => [
                'from' => ['draft', 'paid'],
                'to' => 'cancelled',
            ],
        ];
    }
}

class TestInvalidTransitionFromWorkflow extends AbstractWorkflow
{
    /**
     * TestInvalidTransitionFromWorkflow constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public static function getTransitions(): array
    {
        return [
            'to_paid' => [
                'fro' => 'draft',
                'to' => 'paid',
            ],
        ];
    }
}

class TestInvalidTransitionToWorkflow extends AbstractWorkflow
{
    /**
     * @return array
     */
    public static function getTransitions(): array
    {
        return [
            'to_paid' => [
                'from' => 'draft',
                'tot' => 'paid',
            ],
        ];
    }
}

class TestInvalidTransitionArrayWorkflow extends AbstractWorkflow
{
    /**
     * @return array
     */
    public static function getTransitions(): array
    {
        return [
            'to_paid' => 5,
        ];
    }
}

class AbstractWorkflowTest extends TestCase
{
    /**
     * @var string
     */
    public const PLACE_DRAFT = 'draft';

    /**
     * @var string
     */
    public const PLACE_PAID = 'paid';

    /**
     * @var string
     */
    public const TRANSITION_TO_PAID = 'to_paid';

    /**
     * @var string
     */
    public const TRANSITION_INVALID = 'invalid';

    /**
     * Test the constructor.
     */
    public function testInvalidTransitionFromConstruct()
    {
        $this->expectException(WorkflowException::class);
        new TestInvalidTransitionFromWorkflow();
    }

    /**
     * Test the constructor.
     */
    public function testInvalidTransitionToConstruct()
    {
        $this->expectException(WorkflowException::class);
        new TestInvalidTransitionToWorkflow();
    }

    /**
     * Test the constructor.
     */
    public function testInvalidTransitionArrayConstruct()
    {
        $this->expectException(WorkflowException::class);
        new TestInvalidTransitionArrayWorkflow();
    }

    /**
     * Test the constructor.
     */
    public function testCanTrue()
    {
        $testWorkflow = $this->getTestWorflow();
        $canToPaid = $testWorkflow->can(self::PLACE_DRAFT, self::TRANSITION_TO_PAID);
        $this->assertTrue($canToPaid);
    }

    /**
     * Test the constructor.
     */
    public function testCanFalse()
    {
        $testWorkflow = $this->getTestWorflow();
        $canToPaid = $testWorkflow->can(self::PLACE_PAID, self::TRANSITION_TO_PAID);
        $this->assertFalse($canToPaid);
    }

    /**
     * Test the constructor.
     */
    public function testCanGuardTransition()
    {
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->can(self::PLACE_PAID, self::TRANSITION_INVALID);
    }

    /**
     * Test the constructor.
     */
    public function testApplyCorrect()
    {
        $testWorkflow = $this->getTestWorflow();
        $place = $testWorkflow->apply(self::PLACE_DRAFT, self::TRANSITION_TO_PAID);
        $this->assertSame(self::PLACE_DRAFT, $place);
    }

    /**
     * Test the constructor.
     */
    public function testApplyNoCorrect()
    {
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply(self::PLACE_PAID, self::TRANSITION_TO_PAID);
    }

    private function getTestWorflow()
    {
        return new TestWorkflow();
    }
}
