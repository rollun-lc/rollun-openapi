<?php
declare(strict_types=1);

namespace OpenAPI\Server\MetadataProvider;

use Articus\PathHandler\Exception as PHException;
use Articus\PathHandler\MetadataProvider\Annotation as Base;
use OpenAPI\Server\Exception\ApiErrorException;
use OpenAPI\Server\Producer\Transfer;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

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
        } catch (ApiErrorException $e) {
            // because message writes to http reason where new lines not allowed
            $message = $this->deleteNewLines($e->getMessage());

            throw new PHException\HttpCode(
                $e->getHttpStatus(),
                $message,
                Transfer::getSingleErrorMessages($message, $e->getErrorType())
            );
        } catch (Throwable $e) {
            // because message writes to http reason where new lines not allowed
            $message = $this->deleteNewLines($e->getMessage());

            throw new PHException\HttpCode(500, $message, Transfer::getSingleErrorMessages($message));
        }

        return $result;
    }

    private function deleteNewLines(string $string): string
    {
        return preg_replace('/\R+/', " ", $string);
    }
}