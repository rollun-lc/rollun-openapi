<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample;

use rollun\Callables\Task\Async\Result\Data\Stage;
use rollun\Callables\Task\Async\Result\Data\Status;
use rollun\Callables\Task\Result\TaskInfoInterface;
use rollun\Callables\Task\Async\TaskInterface;
use rollun\Callables\Task\Result\TaskInfo as Result;
use rollun\Callables\Task\Result\Message;
use rollun\Callables\Task\ResultInterface;
use rollun\Callables\TaskExample\Model\CreateTaskParameters;
use rollun\Callables\TaskExample\Result\Data\FileSummaryDelete;
use rollun\Callables\TaskExample\Result\Data\FileSummaryResult;
use rollun\Callables\Task\Async\Result\Data\TaskInfo;

/**
 * Class FileSummary
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummary implements TaskInterface
{
    const DIR_PATH = 'data/file-summary';

    /**
     * @inheritDoc
     */
    public function getTaskInfoById(string $taskId): TaskInfoInterface
    {
        if (!file_exists($this->getFilePath((int)$taskId))) {
            return new Result(null, [new Message('Error', 'No such task')]);
        }

        $data = $this->getFileData((int)$taskId);

        // prepare all stages
        $stages = [];
        $i = 1;
        while ($i <= (int)$taskId) {
            $stages[] = 'writing ' . $i;
            $i++;
        }
        $stages[] = 'summary calculating';
        $stages[] = 'done';

        if (!empty($data['summary'])) {
            // prepare task info
            $taskInfo = new TaskInfo($taskId, 'FileSummary', 3, new Stage($stages, 'done'), new Status(), new Result(new FileSummaryResult((int)$data['summary'])));
            $taskInfo->getStatus()->toFulfilled();

            return new Result($taskInfo);
        }

        // get current stage
        $numbers = $data['numbers'];
        $stage = 'writing ' . (array_pop($numbers) + 1);
        if (!in_array($stage, $stages)) {
            $stage = 'summary calculating';
        }

        // prepare task info
        $taskInfo = new TaskInfo($taskId, 'FileSummary', 3, new Stage($stages, $stage), new Status(), new Result(new FileSummaryResult(array_sum($data['numbers']))));

        return new Result($taskInfo);
    }

    /**
     * @inheritDoc
     *
     * @param CreateTaskParameters $taskParam
     */
    public function runTask(object $taskParam): ResultInterface
    {
        // prepare n
        $n = $taskParam->getN();

        if ($n < 1) {
            return new Result(null, [new Message('Error', 'n param should be more than 1')]);
        }

        if (file_exists($this->getFilePath($n))) {
            $data = $this->getFileData($n);
            if (empty($data['summary'])) {
                return new Result(null, [new Message('Error', 'Such task is already exists')]);
            }
        }

        $data = $this->getBaseData();

        // set numbers
        $i = 1;
        while ($i <= $n) {
            $data['numbers'][] = $i;
            $this->saveFile($n, $data);
            $i++;
        }

        // calc summary
        $data['summary'] = array_sum($data['numbers']);
        $this->saveFile($n, $data);

        return $this->getTaskInfoById((string)$n);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(string $id): ResultInterface
    {
        if (!file_exists($this->getFilePath((int)$id))) {
            return new Result(new FileSummaryDelete(false), [new Message('Error', 'No such task')]);
        }

        $data = $this->getFileData((int)$id);
        if (empty($data['summary'])) {
            return new Result(new FileSummaryDelete(false), [new Message('Error', 'Task is running and can not be deleted')]);
        }

        unlink($this->getFilePath((int)$id));

        return new Result(new FileSummaryDelete(true));
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function getFilePath(int $id): string
    {
        // create dir if not exists
        if (!file_exists(self::DIR_PATH)) {
            mkdir(self::DIR_PATH, 0777, true);
            sleep(1);
        }

        return self::DIR_PATH . '/' . $id . '.json';
    }

    /**
     * @param int $id
     *
     * @return array
     */
    protected function getFileData(int $id): array
    {
        $filePath = $this->getFilePath($id);

        return !file_exists($filePath) ? $this->getBaseData() : \json_decode(file_get_contents($filePath), true);
    }

    /**
     * @return array
     */
    protected function getBaseData(): array
    {
        return [
            'numbers' => [],
            'summary' => null
        ];
    }

    /**
     * @param int   $id
     * @param array $data
     */
    protected function saveFile(int $id, array $data): void
    {
        file_put_contents($this->getFilePath($id), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        sleep(1);
    }
}
