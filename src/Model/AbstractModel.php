<?php

declare(strict_types=1);

namespace Randock\Ddd\Model;

use BornFree\TacticianDomainEvent\Recorder\ContainsRecordedEvents;
use BornFree\TacticianDomainEvent\Recorder\EventRecorderCapabilities;

abstract class AbstractModel implements ContainsRecordedEvents
{
    use EventRecorderCapabilities;
}
