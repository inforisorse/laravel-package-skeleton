<?php

declare(strict_types=1);

namespace Inforisorse\SmsGateway\Traits;

use Inforisorse\SmsGateway\Exceptions\CantAddParamsToEmptyUrlException;
use Inforisorse\SmsGateway\Exceptions\CurlHandleNotInitializadException;

/**
 * common utilities for API call via cURL
 */
trait UseCurlForApi
{
    use NamespaceUtils;

    private mixed $curlHandle = false; // cURL handler

    private bool|string $response; // http request response

    private int $httpStatusCode; // http request return status code

    private mixed $responseInfo; // http request response info

    private string $requestUrl = '';

    /**
     * Return last request response.
     */
    public function getResponse(): bool|string
    {
        return $this->response;
    }

    /**
     * Init the cURL handler
     *
     * @return $this
     */
    protected function initHandler(): self
    {
        $this->curlHandle = curl_init();

        return $this;
    }

    /**
     * Close curlHandle
     *
     * @return $this
     */
    protected function closeHandle(): self
    {
        curl_close($this->curlHandle);
        $this->curlHandle = false;

        return $this;
    }

    protected function setRequestUrl(string $apiFunction): self
    {
        if (! $this->curlHandle) {
            throw new CurlHandleNotInitializadException($this->cleanClassName(), $apiFunction);
        }
        $this->requestUrl = $this->getApiBaseUrl().$apiFunction;
        curl_setopt($this->curlHandle, CURLOPT_URL, $this->requestUrl);

        return $this;
    }

    /**
     * Set request to return the transfer as a string of the return value of curl_exec() instead of
     * outputting it directly
     *
     * @return $this
     */
    protected function setReturnTransfer(): self
    {
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);

        return $this;
    }

    /**
     * Send request to provider and close cURL handler. Set response, responseInfo and httpStatusCode
     *
     * @return $this
     */
    protected function sendRequestToProvider(): self
    {
        $this->response = curl_exec($this->curlHandle);

        $this->responseInfo = curl_getinfo($this->curlHandle);
        $this->httpStatusCode = $this->responseInfo['http_code'];

        return $this;
    }

    /**
     * Add params to GET request.
     *
     * @return $this
     *
     * @throws CantAddParamsToEmptyUrlException
     */
    protected function addParamsToUrl(array $getParams): self
    {
        if (empty($this->requestUrl)) {
            throw new CantAddParamsToEmptyUrlException($this->cleanClassName(), 'GET');
        }
        $this->requestUrl .= $this->buildParamsString($getParams);

        return $this;

    }

    /**
     * Build params string for GET request.
     */
    protected function buildParamsString(array $getParams): string
    {
        $paramString = strpos($this->requestUrl, '?') !== false ? '&' : '?';
        foreach ($getParams as $name => $value) {
            $paramString .= sprintf('%s=%s&', $name, $value);
        }

        return substr($paramString, 0, -1); // strip '?' if $getParams empty, '&' otherwise
    }

    /**
     * Set cURL request headers with authentication credentials for use with API
     *
     * @return $this
     */
    protected function setHeaders(bool $isPost = false): self
    {
        $curlHeaders = array_merge(
            $this->apiAuthHeaders(),
            [
                'Accept: application/json',
            ]);
        if ($isPost) {
            $curlHeaders[] = 'Content-type: application/json';
        }

        if (! curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $curlHeaders)) {
            // throw new CurlSetHeadersException();
        }

        return $this;
    }

    protected function setPostMethod(): self
    {
        curl_setopt($this->curlHandle, CURLOPT_POST, 1);

        return $this;
    }

    /**
     * Set the POST body for the request
     *
     * @return $this
     */
    protected function setPostFields($message): self
    {
        if (empty($this->requestUrl)) {
            throw new CantAddParamsToEmptyUrlException($this->cleanClassName(), 'POST');
        }

        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, json_encode($message));

        return $this;
    }
}
