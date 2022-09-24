<?php

namespace a3330\pro_php_v2\src\Request;

use HttpException;

class Request
{
    public function __construct(
        private array $get = [],
        private array $post = [],
        private array $server = [],
        private array $cookies = []
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function path()
    {
        if(!array_key_exists('REQUEST_URI', $this->server))
        {
            throw new \Exception('Cannot get path the request');
        }

        $components = parse_url($this->server['REQUEST_URI']);

        if(!is_array($components) || !array_key_exists('path', $components ))
        {
            throw new \Exception('Cannot get path from the request');
        }

        return $components['path'];
    }

    /**
     * @throws HttpException
     * @throws \Exception
     */
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            throw new \Exception(
                "No such query param in the request: $param"
            );
        }

        $value = trim($this->get[$param]);
        if (empty($value)) {

            throw new HttpException(
                "Empty query param in the request: $param"
            );
        }
        return $value;
    }

    /**
     * @throws HttpException
     */
    public function header(string $header): string
    {
        $headerName = mb_strtoupper("http_". str_replace('-', '_', $header));
        if (!array_key_exists($headerName, $this->server)) {

            throw new HttpException("No such header in the request: $header");
        }
        $value = trim($this->server[$headerName]);
        if (empty($value)) {
            throw new HttpException("Empty header in the request: $header");
        }
        return $value;
    }
}