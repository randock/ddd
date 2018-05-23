<?php

declare(strict_types=1);

namespace Tests\Randock\Ddd\Unit\Workflow;

use PHPUnit\Framework\TestCase;
use Randock\Ddd\Workflow\AbstractWorkflow;
use Randock\Ddd\Workflow\Exception\WorkflowException;

class TestSubject
{
    /**
     * @var string
     */
    private $status;

    /**
     * TestObject constructor.
     *
     * @param $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        if (array_key_exists('status', $data)) {
            $this->setStatus($data['status']);
        }
    }

    /**
     * @param string $status
     */
    private function setStatus(string $status)
    {
        $this->status = $status;
    }
}

class TestSubjectWhitoutStatusProperty
{
}

class TestSubjectWhitoutStatusGetMethod
{
    /**
     * @var string
     */
    private $status;

    /**
     * TestObject constructor.
     *
     * @param $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }
}

class TestSubjectWhitoutUpdateMethod
{
    /**
     * @var string
     */
    private $status;

    /**
     * TestObject constructor.
     *
     * @param $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}

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

    /**
     * @return string
     */
    public static function getProperty(): string
    {
        return 'status';
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

    /**
     * @return string
     */
    public static function getProperty(): string
    {
        return 'status';
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

    /**
     * @return string
     */
    public static function getProperty(): string
    {
        return 'status';
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

    /**
     * @return string
     */
    public static function getProperty(): string
    {
        return 'status';
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
    public function testCanGuardApplyTransitionThereIsNotProperty()
    {
        $subject = new TestSubjectWhitoutStatusProperty();
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply($subject, self::TRANSITION_TO_PAID);
    }

    /**
     * Test the constructor.
     */
    public function testCanGuardApplyTransitionThereIsNotGetPropertyMethod()
    {
        $subject = new TestSubjectWhitoutStatusGetMethod(self::PLACE_DRAFT);
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply($subject, self::TRANSITION_TO_PAID);
    }

    /**
     * Test the constructor.
     */
    public function testCanGuardApplyTransitionThereIsNotUpdateMethod()
    {
        $subject = new TestSubjectWhitoutUpdateMethod(self::PLACE_DRAFT);
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply($subject, self::TRANSITION_TO_PAID);
    }

    /**
     * Test the constructor.
     */
    public function testCanGuardTransitionInvalid()
    {
        $subject = new TestSubject(self::PLACE_DRAFT);
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply($subject, self::TRANSITION_INVALID);
    }

    /**
     * Test the constructor.
     */
    public function testApplyCorrect()
    {
        $subject = new TestSubject(self::PLACE_DRAFT);
        $testWorkflow = $this->getTestWorflow();
        $testWorkflow->apply($subject, self::TRANSITION_TO_PAID);
        $this->assertSame(self::PLACE_PAID, $subject->getStatus());
    }

    /**
     * Test the constructor.
     */
    public function testApplyNoCorrect()
    {
        $subject = new TestSubject(self::PLACE_PAID);
        $testWorkflow = $this->getTestWorflow();
        $this->expectException(WorkflowException::class);
        $testWorkflow->apply($subject, self::TRANSITION_TO_PAID);
    }

    private function getTestWorflow()
    {
        return new TestWorkflow();
    }
}
