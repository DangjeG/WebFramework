<?php

namespace Dangje\WebFramework\Message;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface {

    private const array DEFAULT_PORTS = [
        'http' => 80,
        'https' => 443
    ];
    private const string CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';
    private const string CHAR_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private string $port;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';
    private string $uriString;

    public function __construct($uri = '') {
        if (!is_string($uri)) {
            throw new InvalidArgumentException(sprintf(
                'URI is not a string; received "%s"',
                (is_object($uri) ? get_class($uri) : gettype($uri))
            ));
        }

        if (! empty($uri)) {
            $this->parseUri($uri);
        }
    }

    public function getScheme(): string {
        return $this->scheme;
    }
    
    public function getAuthority(): string {
        if (empty($this->host)) {
            return '';
        }

        $authority = $this->host;
        if (! empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->isNonStandardPort($this->scheme, $this->host, $this->port)) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string {
        return $this->userInfo;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function getPort(): ?int {
        return $this->isNonStandardPort($this->scheme, $this->host, $this->port)
            ? $this->port
            : null;
    }

    public function getPath() : string {
        return $this->path;
    }

    public function getQuery() : string {
        return $this->query;
    }

    public function getFragment() : string {
        return $this->fragment;
    }

    public function withScheme($scheme): UriInterface {
        $scheme = $this->filterScheme($scheme);

        if ($scheme === $this->scheme) {
            return clone $this;
        }

        $new = clone $this;
        $new->scheme = $scheme;

        return $new;
    }

    public function withUserInfo($user, $password = null): UriInterface {
        $info = $user;
        if ($password) {
            $info .= ':' . $password;
        }

        if ($info === $this->userInfo) {
            return clone $this;
        }

        $new = clone $this;
        $new->userInfo = $info;

        return $new;
    }

    public function withHost($host): UriInterface {
        if ($host === $this->host) {
            return clone $this;
        }

        $new = clone $this;
        $new->host = $host;

        return $new;
    }

    public function withPort($port): UriInterface {
        if (! (is_integer($port) || (is_string($port) && is_numeric($port)))) {
            throw new InvalidArgumentException(sprintf(
                'Invalid port "%s" specified; must be an integer or integer string',
                (is_object($port) ? get_class($port) : gettype($port))
            ));
        }

        $port = (int) $port;

        if ($port === $this->port) {
            return clone $this;
        }

        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException(sprintf(
                'Invalid port "%d" specified; must be a valid TCP/UDP port',
                $port
            ));
        }

        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    public function withPath($path): UriInterface {
        if (! is_string($path)) {
            throw new InvalidArgumentException(
                'Invalid path provided; must be a string'
            );
        }

        if (str_contains($path, '?')) {
            throw new InvalidArgumentException(
                'Invalid path provided; must not contain a query string'
            );
        }

        if (str_contains($path, '#')) {
            throw new InvalidArgumentException(
                'Invalid path provided; must not contain a URI fragment'
            );
        }

        $path = $this->filterPath($path);

        if ($path === $this->path) {
            return clone $this;
        }

        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    public function withQuery($query): UriInterface {
        if (! is_string($query)) {
            throw new InvalidArgumentException(
                'Query string must be a string'
            );
        }

        if (str_contains($query, '#')) {
            throw new InvalidArgumentException(
                'Query string must not include a URI fragment'
            );
        }

        $query = $this->filterQuery($query);

        if ($query === $this->query) {
            return clone $this;
        }

        $new = clone $this;
        $new->query = $query;

        return $new;
    }

    public function withFragment($fragment): UriInterface {
        $fragment = $this->filterFragment($fragment);

        if ($fragment === $this->fragment) {
            return clone $this;
        }

        $new = clone $this;
        $new->fragment = $fragment;

        return $new;
    }

    public function __toString(): string
    {
        if ($this->uriString !== null) {
            return $this->uriString;
        }

        $this->uriString = static::createUriString(
            $this->scheme,
            $this->getAuthority(),
            $this->getPath(),
            $this->query,
            $this->fragment
        );

        return $this->uriString;
    }

    private static function createUriString($scheme, $authority, $path, $query, $fragment): string
    {
        $uri = '';

        if (! empty($scheme)) {
            $uri .= sprintf('%s://', $scheme);
        }

        if (! empty($authority)) {
            $uri .= $authority;
        }

        if ($path) {
            if (empty($path) || !str_starts_with($path, '/')) {
                $path = '/' . $path;
            }

            $uri .= $path;
        }

        if ($query) {
            $uri .= sprintf('?%s', $query);
        }

        if ($fragment) {
            $uri .= sprintf('#%s', $fragment);
        }

        return $uri;
    }

    private function parseUri($uri): void
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException(
                'Uri is not in the correct format'
            );
        }

        $this->scheme    = isset($parts['scheme'])   ? $this->filterScheme($parts['scheme']) : '';
        $this->userInfo  = $parts['user'] ?? '';
        $this->host      = $parts['host'] ?? '';
        $this->port      = $parts['port'] ?? null;
        $this->path      = isset($parts['path'])     ? $this->filterPath($parts['path']) : '';
        $this->query     = isset($parts['query'])    ? $this->filterQuery($parts['query']) : '';
        $this->fragment  = isset($parts['fragment']) ? $this->filterFragment($parts['fragment']) : '';

        if (isset($parts['pass'])) {
            $this->userInfo .= ':' . $parts['pass'];
        }
    }

    private function isNonStandardPort($scheme, $host, $port): bool {
        if (! $scheme) {
            return true;
        }

        if (! $host || ! $port) {
            return false;
        }
        return ! isset(self::DEFAULT_PORTS[$scheme]) || $port !== self::DEFAULT_PORTS[$scheme];
    }

    private function filterScheme($scheme): array|string
    {
        $scheme = strtolower($scheme);
        $scheme = preg_replace('#:(//)?$#', '', $scheme);

        if (empty($scheme)) {
            return '';
        }

        if (! array_key_exists($scheme, self::DEFAULT_PORTS)) {
            throw new InvalidArgumentException(sprintf(
                'The scheme "%s" was not included in the set (%s)',
                $scheme,
                implode(', ', array_keys(self::DEFAULT_PORTS))
            ));
        }

        return $scheme;
    }

    private function filterPath($path): array|string|null
    {
        return preg_replace_callback(
            '/(?:[^' . self::CHAR_UNRESERVED . ':@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            [$this, 'urlEncodeChar'],
            $path
        );
    }

    private function urlEncodeChar(array $matches): string
    {
        return rawurlencode($matches[0]);
    }

    private function filterQuery($query): string
    {
        if (! empty($query) && str_starts_with($query, '?')) {
            $query = substr($query, 1);
        }

        $parts = explode('&', $query);
        foreach ($parts as $index => $part) {
            list($key, $value) = $this->splitQueryValue($part);
            if ($value === null) {
                $parts[$index] = $this->filterQueryOrFragment($key);
                continue;
            }
            $parts[$index] = sprintf(
                '%s=%s',
                $this->filterQueryOrFragment($key),
                $this->filterQueryOrFragment($value)
            );
        }

        return implode('&', $parts);
    }

    private function splitQueryValue($value): array
    {
        $data = explode('=', $value, 2);
        if (count($data) === 1) {
            $data[] = null;
        }
        return $data;
    }

    private function filterFragment($fragment): array|string|null
    {
        if ($fragment === null) {
            $fragment = '';
        }

        if (! empty($fragment) && str_starts_with($fragment, '#')) {
            $fragment = substr($fragment, 1);
        }

        return $this->filterQueryOrFragment($fragment);
    }

    private function filterQueryOrFragment($value): array|string|null
    {
        return preg_replace_callback(
            '/(?:[^' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . '%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            [$this, 'urlEncodeChar'],
            $value
        );
    }

}
