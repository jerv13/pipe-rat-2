<?php

namespace Reliv\PipeRat2\Acl\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Acl\Api\IsAllowed;
use Reliv\PipeRat2\Core\Api\BuildFailDataResponse;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\DataResponse;
use Reliv\PipeRat2\Core\DataResponseBasic;
use Reliv\PipeRat2\Core\Http\MiddlewareWithConfigOptionsServiceOptionAbstract;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAcl extends MiddlewareWithConfigOptionsServiceOptionAbstract
{
    const OPTION_NOT_ALLOWED_STATUS_CODE = 'not-allowed-status-code';
    const OPTION_NOT_ALLOWED_STATUS_MESSAGE = 'not-allowed-status-message';

    const DEFAULT_NOT_ALLOWED_STATUS_CODE = 401;
    const DEFAULT_NOT_ALLOWED_STATUS_MESSAGE = 'Not Authorized: ACL';

    /**
     * Provide a unique config key
     *
     * @return string
     */
    public static function configKey(): string
    {
        return 'request-acl';
    }

    protected $buildFailDataResponse;
    protected $defaultFailStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE;
    protected $defaultFailStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE;

    /**
     * @param GetOptions                         $getOptions
     * @param GetServiceFromConfigOptions        $getServiceFromConfigOptions
     * @param GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions
     * @param BuildFailDataResponse              $buildFailDataResponse
     * @param int                                $defaultFailStatusCode
     * @param string                             $defaultFailStatusMessage
     */
    public function __construct(
        GetOptions $getOptions,
        GetServiceFromConfigOptions $getServiceFromConfigOptions,
        GetServiceOptionsFromConfigOptions $getServiceOptionsFromConfigOptions,
        BuildFailDataResponse $buildFailDataResponse,
        int $defaultFailStatusCode = self::DEFAULT_NOT_ALLOWED_STATUS_CODE,
        string $defaultFailStatusMessage = self::DEFAULT_NOT_ALLOWED_STATUS_MESSAGE
    ) {
        $this->buildFailDataResponse = $buildFailDataResponse;
        $this->defaultFailStatusCode = $defaultFailStatusCode;
        $this->defaultFailStatusMessage = $defaultFailStatusMessage;
        parent::__construct(
            $getOptions,
            $getServiceFromConfigOptions,
            $getServiceOptionsFromConfigOptions
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return DataResponse
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $options = $this->getOptions->__invoke(
            $request,
            self::configKey()
        );

        /** @var IsAllowed $isAllowedApi */
        $isAllowedApi = $this->getServiceFromConfigOptions->__invoke(
            $options,
            IsAllowed::class
        );

        $isAllowedOptions = $this->getServiceOptionsFromConfigOptions->__invoke(
            $options
        );

        $isAllowed = $isAllowedApi->__invoke(
            $request,
            $isAllowedOptions
        );

        if (!$isAllowed) {
            $failStatusCode = Options::get(
                $options,
                self::OPTION_NOT_ALLOWED_STATUS_CODE,
                $this->defaultFailStatusCode
            );

            $failMessage = Options::get(
                $options,
                self::OPTION_NOT_ALLOWED_STATUS_MESSAGE,
                $this->defaultFailStatusMessage
            );

            return $this->buildFailDataResponse->__invoke(
                $request,
                $failMessage,
                $failStatusCode,
                [],
                $failMessage
            );
        }

        return $next($request, $response);
    }
}
