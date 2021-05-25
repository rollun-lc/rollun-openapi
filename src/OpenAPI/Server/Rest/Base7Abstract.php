<?php


namespace OpenAPI\Server\Rest;


use OpenAPI\Server\Rest\Traits\No7Post;
use OpenAPI\Server\Rest\Traits\NoPost;

abstract class Base7Abstract extends BaseAbstract
{
    use No7Post, NoPost {
        No7Post::post insteadof NoPost;
    }
}