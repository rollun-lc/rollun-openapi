<?php
declare(strict_types=1);

namespace OpenAPI\Server\Handler;

use OpenAPI\Server\Rest\RestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractHandler
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
abstract class AbstractHandler
{
    /**
     * @var RestInterface
     */
    protected $restObject;

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $method
     *
     * @return array
     */
    protected function runAction(ServerRequestInterface $request, string $method): array
    {
        switch ($method) {
            case 'Post()':
                $result = $this->restObject->post($request->getAttribute('bodyData'));
                break;
            case 'Put()':
                $result = $this->restObject->putById($request->getAttribute('id'), $request->getAttribute('bodyData'));
                break;
            case 'Patch()':
                if (!empty($request->getAttribute('id'))) {
                    $result = $this->restObject->patchById($request->getAttribute('id'), $request->getAttribute('bodyData'));
                } else {
                    $result = $this->restObject->patch($request->getAttribute('queryData'), $request->getAttribute('bodyData'));
                }
                break;
            case 'Delete()':
                if (!empty($request->getAttribute('id'))) {
                    $result = $this->restObject->deleteById($request->getAttribute('id'));
                } else {
                    $result = $this->restObject->delete($request->getAttribute('queryData'));
                }
                break;
            case 'Get()':
                if (!empty($request->getAttribute('id'))) {
                    $result = $this->restObject->getById($request->getAttribute('id'));
                } else {
                    $result = $this->restObject->get($request->getAttribute('queryData'));
                }
                break;
            default:
                throw new \InvalidArgumentException('Unknown http method');
        }

        return $result;
    }
}
