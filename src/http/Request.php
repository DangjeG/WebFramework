<?php

namespace Dangje\WebFramework;

use Psr\Http\Message\ServerRequestInterface;

class Request implements ServerRequestInterface
{
    private $method;
    private $uri;
    private $queryParams;
    private $parserBody;

    public function __construct(string $method, $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getRequestUri(): string
    {
        return $this->uri;
    }

    public function withQueryParams(array $query): self {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function withParserBody($data): self {
        $new = clone $this;
        $new->parserBody = $data;
        return $new;
    }

    public function getParserBody() {

        if($this->parserBody != null) {
            return $this->parserBody;
        }
        $entityBody = file_get_contents('php://input');
        
        foreach ($_POST as $key => $value) {
            $entityBody = [
                $key => $value
            ]
        }

        return $this->parserBody;
    }
}
