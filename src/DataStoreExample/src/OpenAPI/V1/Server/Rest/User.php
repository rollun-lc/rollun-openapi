<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\V1\Server\Rest;

use Articus\PathHandler\Exception\HttpCode;
use Articus\PathHandler\Exception\NotFound;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;
use rollun\datastore\Rql\RqlParser;
use rollun\datastore\Rql\RqlQuery;
use rollun\dic\InsideConstruct;
use Xiag\Rql\Parser\Node\LimitNode;
use Xiag\Rql\Parser\Node\SortNode;

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
     * @param DataStoreInterface|null $dataStore
     *
     * @throws \ReflectionException
     */
    public function __construct(DataStoreInterface $dataStore = null)
    {
        InsideConstruct::init(['dataStore' => 'exampleUserDataStore']);
    }

    /**
     * @inheritDoc
     */
    public function delete($queryData = [])
    {
        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);

        $result = $this->dataStore->queriedDelete($query);
        if (empty($result)) {
            throw new HttpCode(404, "No records exists for such query");
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function get($queryData = [])
    {
        $limit = empty($queryData->limit) ? 1000 : $queryData->limit;
        $offset = empty($queryData->offset) ? 0 : $queryData->offset;

        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);
        $query->setLimit(new LimitNode($limit, $offset));

        if (!empty($queryData->sort_by)) {
            $query->setSort(new SortNode([$queryData->sort_by => $queryData->sort_order == 'asc' ? 1 : -1]));
        }

        // get result from dataStore
        $result = $this->dataStore->query($query);

        // prepare result fields types
        foreach ($result as $k => $row) {
            $result[$k] = $this->prepareResultFieldsTypes($row);
        }

        return ['data' => $result];
    }

    /**
     * @inheritDoc
     */
    public function patch($queryData, $bodyData)
    {
        $query = empty($queryData->rql) ? new RqlQuery() : RqlParser::rqlDecode($queryData->rql);

        $data = [];
        foreach ((array)$bodyData as $name => $value) {
            if ($value !== null) {
                $data[$name] = $value;
            }
        }

        $result = $this->dataStore->queriedUpdate($data, $query);

        return $this->getUpdateResult($result);
    }

    /**
     * @inheritDoc
     */
    public function post($bodyData)
    {
        // prepare input data
        $inputData = [];
        foreach ($bodyData as $item) {
            $inputData[] = (array)$item;
        }

        $result = $this->dataStore->multiCreate($inputData);

        return $this->getUpdateResult($result);
    }


    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        $result = $this->dataStore->delete($id);

        if (empty($result)) {
            throw new NotFound();
        }

        return [];
    }


    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        $result = $this->dataStore->read($id);

        if (empty($result)) {
            throw new NotFound();
        }

        // prepare result fields types
        $result = $this->prepareResultFieldsTypes($result);

        return ['data' => $result];
    }


    /**
     * @inheritDoc
     */
    public function patchById($id, $bodyData)
    {
        $result = $this->dataStore->read($id);

        if (empty($result)) {
            throw new NotFound();
        }

        foreach ((array)$bodyData as $name => $value) {
            if ($value !== null && $name != 'id') {
                $result[$name] = $value;
            }
        }

        // prepare result fields types
        $result = $this->prepareResultFieldsTypes($result);

        $this->dataStore->rewrite($result);

        return ['data' => $result];
    }


    /**
     * @inheritDoc
     */
    public function putById($id, $bodyData)
    {
        return $this->patchById($id, $bodyData);
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    protected function getUpdateResult(array $data)
    {
        if (!empty($data)) {
            $ids = [];
            foreach ($data as $id) {
                $ids[] = "eq(id,$id)";
            }

            $queryData = new \stdClass();
            $queryData->rql = "or(eq(id,nosuchid)," . implode(",", $ids) . ")";

            return $this->get($queryData);
        }

        return [];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareResultFieldsTypes(array $data): array
    {
        $data['id'] = (string)$data['id'];
        $data['active'] = (bool)$data['active'];

        return $data;
    }
}
