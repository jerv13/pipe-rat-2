<?php

namespace Reliv\PipeRat2\RequestFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetOptions;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestFormatJson extends RequestFormatAbstract
{
    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-format-json';
    }

    /**
     * @var array
     */
    protected $defaultContentTypes
        = [
            'application/json',
        ];

    /**
     * @param GetOptions $getOptions
     */
    public function __construct(GetOptions $getOptions)
    {
        parent::__construct($getOptions);
    }

    /**
     * If the request is of type application/json, this middleware
     * decodes the json in the body and puts it in the "body" attribute
     * in the request.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param null|callable          $next
     *
     * @return null|ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        if (!$this->isValidMethod($request)) {
            return $next($request, $response);
        }

        if ($this->isValidContentType($request)) {
            $body = json_decode($request->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $body = $response->getBody();
                $body->write(
                    'Invalid JSON in request body for MIME types: ' . implode(', ', $this->defaultContentTypes)
                );

                return $response->withStatus(400)->withBody($body);
            }

            $request = $request->withParsedBody($body);
        }

        return $next($request, $response);
    }
}