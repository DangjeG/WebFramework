<?php

namespace Dangje\WebFramework\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest implements ServerRequestInterface
{
    private string $protocolVersion = '1.0';
    private array $headers;
    private StreamInterface $body;
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;
    private array $serverParams;
    private array $cookieParams;
    private array $uploadedFiles;
    private array $attributes;
    private array $parsedBody;


    public function __construct(
        string $requestTarget,
        string $method,
        UriInterface $uri,
        array $serverParams,
        ?array $headers = [],
        ?StreamInterface $body = null,
        array $cookieParams = [],
        array $uploadedFiles = [],
        array $attributes = [],
    ) {
        $this->headers = $headers;
        $this->body = $body;
        $this->requestTarget = $requestTarget;
        $this->method = $method;
        $this->uri = $uri;
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->attributes = $attributes;
        $this->parsedBody = json_decode($this->body->getContents(), true);
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $newRequest = clone $this;
        $newRequest->cookieParams = $cookies;
        return $newRequest;
    }

    public function getQueryParams(): array
    {
        parse_str($this->getUri()->getQuery(), $query);
        return $query;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $newRequest = clone $this;
        $newRequest->uri = $newRequest->uri->withQuery(http_build_query($query));
        return $newRequest;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $newRequest = clone $this;
        $newRequest->uploadedFiles = $uploadedFiles;
        return $newRequest;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $newRequest = clone $this;
        $newRequest->parsedBody = $data;
        return $newRequest;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $newRequest = clone $this;
        $newRequest->attributes[$name] = $value;
        return $newRequest;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $newRequest = clone $this;
        unset($newRequest->attributes[$name]);
        return $newRequest;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = [$value];
        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}