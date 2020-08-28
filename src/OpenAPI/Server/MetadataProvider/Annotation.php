<?php
declare(strict_types=1);

namespace OpenAPI\Server\MetadataProvider;

use Articus\PathHandler\Exception as PHException;
use Articus\PathHandler\MetadataProvider\Annotation as Base;
use OpenAPI\Server\Producer\Transfer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Annotation
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Annotation extends Base
{
    /**
     * @inheritDoc
     *
     * @throws PHException\HttpCode
     */
    public function executeHandlerMethod(string $handlerName, string $httpMethod, $handler, ServerRequestInterface $request)
    {
        try {
            $result = parent::executeHandlerMethod($handlerName, $httpMethod, $handler, $request);
        } catch (\Throwable $e) {
            // prepare code
            $code = !empty($e->getCode()) ? $e->getCode() : 500;

            throw new PHException\HttpCode($code, $e->getMessage(), Transfer::getSingleErrorMessages($e->getMessage()));
        }

        return $result;
    }
}