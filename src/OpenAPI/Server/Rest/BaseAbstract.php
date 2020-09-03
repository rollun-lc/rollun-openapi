<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest;

use OpenAPI\Server\Rest\Traits;

/**
 * Abstract class BaseAbstract
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
abstract class BaseAbstract implements RestInterface
{
    use Traits\NoPost;
    use Traits\NoPatch;
    use Traits\NoDelete;
    use Traits\NoGet;
    use Traits\NoPutById;
    use Traits\NoPatchById;
    use Traits\NoDeleteById;
    use Traits\NoGetById;
}
