<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\Server\V1\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\ResultInterface;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;
use rollun\dic\InsideConstruct;

/**
 * Class User
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class User extends BaseAbstract
{
    /**
     * @var DataStoreInterface
     */
    protected $dataStore;

    /**
     * User constructor.
     *
     * @param DataStoreInterface $dataStore
     *
     * @throws \ReflectionException
     */
    public function __construct(DataStoreInterface $dataStore = null)
    {
        InsideConstruct::init(['dataStore' => 'exampleUserDataStore']);
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserDELETEQueryData $queryData
     */
    public function delete($queryData = null): ResultInterface
    {
        if (method_exists($this->controllerObject, 'delete')) {
            $queryDataArray = (array)$queryData;

            return $this->controllerObject->delete($queryDataArray);
        }

        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserGETQueryData $queryData
     */
    public function get($queryData = null): ResultInterface
    {
        echo '<pre>';
        print_r('123');
        die();
        if (method_exists($this->controllerObject, 'get')) {
            $queryDataArray = (array)$queryData;

            return $this->controllerObject->get($queryDataArray);
        }

        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\UserPATCHQueryData $queryData
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\User               $bodyData
     */
    public function patch($queryData, $bodyData): ResultInterface
    {
        if (method_exists($this->controllerObject, 'patch')) {
            $bodyDataArray = (array)$bodyData;
            $queryDataArray = (array)$queryData;

            return $this->controllerObject->patch($queryDataArray, $bodyDataArray);
        }

        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\PostUser[] $bodyData
     */
    public function post($bodyData): ResultInterface
    {
        // prepare input data
        $inputData = [];
        foreach ($bodyData as $item) {
            $inputData[] = (array)$item;
        }

        $result = $this->dataStore->multiCreate($inputData);

        echo '<pre>';
        print_r($result);
        die();
        if (method_exists($this->controllerObject, 'post')) {
            $bodyDataArray = (array)$bodyData;

            return $this->controllerObject->post($bodyDataArray);
        }

        throw new \Exception('Not implemented method');
    }


    /**
     * @inheritDoc
     */
    public function deleteById($id): ResultInterface
    {
        if (method_exists($this->controllerObject, 'deleteById')) {
            return $this->controllerObject->deleteById($id);
        }

        throw new \Exception('Not implemented method');
    }


    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        if (method_exists($this->controllerObject, 'getById')) {
            return $this->controllerObject->getById($id);
        }

        throw new \Exception('Not implemented method');
    }


    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\User $bodyData
     */
    public function patchById($id, $bodyData): ResultInterface
    {
        if (method_exists($this->controllerObject, 'patchById')) {
            $bodyDataArray = (array)$bodyData;

            return $this->controllerObject->patchById($id, $bodyDataArray);
        }

        throw new \Exception('Not implemented method');
    }


    /**
     * @inheritDoc
     *
     * @param \DataStoreExample\OpenAPI\Server\V1\DTO\PutUser $bodyData
     */
    public function putById($id, $bodyData): ResultInterface
    {
        if (method_exists($this->controllerObject, 'putById')) {
            $bodyDataArray = (array)$bodyData;

            return $this->controllerObject->putById($id, $bodyDataArray);
        }

        throw new \Exception('Not implemented method');
    }
}
