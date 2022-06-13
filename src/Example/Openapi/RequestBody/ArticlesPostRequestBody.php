<?php

declare(strict_types=1);

namespace Example\Openapi\RequestBody;

use Example\Openapi\Schema\ArticlePostRequest;

class ArticlesPostRequestBody
{
    public function __construct(
        private readonly string $mediaType,
        private readonly ArticlePostRequest $content
    ) {
        if ($mediaType !== 'application/json') {
            throw new \RuntimeException('Unsupported mediaType');
        }
    }

    /**
     * @return string
     */
    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * @return mixed
     */
    public function getContent(): ArticlePostRequest
    {
        return $this->content;
    }
}
