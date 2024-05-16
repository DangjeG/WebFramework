<?php
declare(strict_types = 1);

namespace Middlewares;

use ArrayAccess;
use Dangje\WebFramework\Factory\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;

abstract class HttpAuthentication
{
    protected $users;

    protected $realm = 'Login';

    protected $attribute;

    protected $responseFactory;

    public function __construct($users, ResponseFactoryInterface $responseFactory = null)
    {
        if (!is_array($users) && !($users instanceof ArrayAccess)) {
            throw new InvalidArgumentException(
                'The users argument must be an array or implement the ArrayAccess interface'
            );
        }

        $this->users = $users;
        $responseFactoryNew = new ResponseFactory();
        $this->responseFactory = $responseFactory ?: $responseFactoryNew->createResponse();
    }

    public function realm(string $realm): self
    {
        $this->realm = $realm;

        return $this;
    }
    
    public function attribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }
}