<?php

namespace Reliv\PipeRat2\RequestAttribute\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeUrlEncodedSkip implements WithRequestAttributeSkip
{
    const URL_KEY = 'skip';

    protected $getUrlEncodedFilterValue;

    /**
     * @param GetUrlEncodedFilterValue $getUrlEncodedFilterValue
     */
    public function __construct(
        GetUrlEncodedFilterValue $getUrlEncodedFilterValue
    ) {
        $this->getUrlEncodedFilterValue = $getUrlEncodedFilterValue;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface {
        $skip = $this->getUrlEncodedFilterValue->__invoke(
            $request,
            self::URL_KEY
        );

        return $request->withAttribute(self::ATTRIBUTE, $skip);
    }
}
