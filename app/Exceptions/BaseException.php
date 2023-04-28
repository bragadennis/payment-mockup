<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    /**
     * @var int
     */
    private int $httpCode;

    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var string
     */
    protected string $errorCode;

    /**
     * @var array
     */
    protected array $payload;

    public function __construct(string $message = '')
    {
        // Fallback message that prevents that no message at all being cast.
        $this->setErrorMessage($message);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public abstract function render();

    /**
     * @return array
     */
    public function getPayload(): array
    {
        $this->payload = [
            'errorMessage' => $this->getErrorMessage(),
            'errorCode' => $this->getErrorCode()
        ];

        return $this->payload;
    }

    ####
    #   Private Methods Area
    ####

    /**
     * @param int $code
     *
     * @return self
     */
    protected function setHTTPCode(int $code): self
    {
        $this->httpCode = $code;

        return $this;
    }

    /**
     * @return int
     */
    protected function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param string $errorMessage
     *
     * @return self
     */
    protected function setErrorMessage(string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    protected function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorCode
     *
     * @return self
     */
    protected function setErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return string
     */
    protected function getErrorCode()
    {
        return $this->errorCode;
    }
}
