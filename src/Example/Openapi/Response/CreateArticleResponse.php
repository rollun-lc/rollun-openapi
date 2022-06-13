<?php

declare(strict_types=1);

namespace Example\Openapi\Response;

use Example\Openapi\Schema\ArticleResult;
use Example\Openapi\Schema\PendingResult;

class CreateArticleResponse
{
    public function __construct(
        private readonly array $headers,
        private readonly string $statusCode,
        private readonly PendingResult|ArticleResult $content
    )
    {
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @return ArticleResult|PendingResult
     */
    public function getContent(): ArticleResult|PendingResult
    {
        return $this->content;
    }
}
