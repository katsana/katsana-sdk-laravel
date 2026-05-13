<?php

namespace Katsana\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends HttpException
{
    /**
     * The status code to use for the response.
     */
    private int $statusCode = 422;

    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string.                                    $errorBag
     *
     * @return void
     */
    public function __construct(
        public readonly \Illuminate\Contracts\Validation\Validator $validator,
        public readonly ?\Symfony\Component\HttpFoundation\Response $response = null,
        public readonly string $errorBag = 'default'
    ) {
        parent::__construct(422, 'The given data was invalid.');
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->validator->errors()->messages();
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function status(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set the error bag on the exception.
     *
     * @param string $errorBag
     *
     * @return $this
     */
    public function errorBag(string $errorBag): static
    {
        $this->errorBag = $errorBag;

        return $this;
    }

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse(): ?\Symfony\Component\HttpFoundation\Response
    {
        return $this->response;
    }

    /**
     * Get the response status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
