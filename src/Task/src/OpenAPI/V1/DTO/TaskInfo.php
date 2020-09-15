<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class TaskInfo
{
    /**
     * @DTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $id;
    /**
     * @DTA\Data(field="timeout")
     * @DTA\Validator(name="Type", options={"type":"int"})
     * @var int
     */
    public $timeout;
    /**
     * @DTA\Data(field="stage")
     * @DTA\Strategy(name="Object", options={"type":\Task\OpenAPI\V1\DTO\Stage::class})
     * @DTA\Validator(name="Dictionary", options={"type":\Task\OpenAPI\V1\DTO\Stage::class})
     * @var \Task\OpenAPI\V1\DTO\Stage
     */
    public $stage;
    /**
     * @DTA\Data(field="status")
     * @DTA\Strategy(name="Object", options={"type":\Task\OpenAPI\V1\DTO\Status::class})
     * @DTA\Validator(name="Dictionary", options={"type":\Task\OpenAPI\V1\DTO\Status::class})
     * @var \Task\OpenAPI\V1\DTO\Status
     */
    public $status;
    /**
     * Task start time UTC
     * @DTA\Data(field="startTime", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $start_time;
    /**
     * @DTA\Data(field="result", nullable=true)
     * @DTA\Strategy(name="Object", options={"type":\Task\OpenAPI\V1\DTO\SummaryResult::class})
     * @DTA\Validator(name="Dictionary", options={"type":\Task\OpenAPI\V1\DTO\SummaryResult::class})
     * @var \Task\OpenAPI\V1\DTO\SummaryResult
     */
    public $result;
}
