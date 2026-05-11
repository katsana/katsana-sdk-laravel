<?php

namespace Katsana\Http\Requests;

use Katsana\Manager;
use Katsana\Sdk\Signature;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Webhook extends Request
{
    /**
     * The SDK Client Manager.
     *
     * @var \Katsana\Manager
     */
    protected $manager;

    /**
     * The request signature key.
     *
     * @var string|null
     */
    protected $signatureKey;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'device_id' => ['required'],
            'event' => ['required'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     *
     * @return array|mixed
     */
    public function validated($key = null, $default = null)
    {
        $signature = new Signature($this->getSignatureKey());
        $header = $this->header('X_SIGNATURE') ?? '';

        $threshold = $this->getClientManager()->config('webhook.threshold') ?? 3600;

        if (! $signature->verify($header, $this->getContent(), $threshold)) {
            throw new HttpException(419, 'Unable to verify X-Signature.');
        }

        $data = $this->post();

        if (is_null($key)) {
            return $data;
        }

        return data_get($data, $key, $default);
    }

    /**
     * Get signature key value for the request.
     *
     * @return string|null
     */
    protected function getSignatureKey(): ?string
    {
        return $this->signatureKey ?? $this->getClientManager()->config('webhook.signature');
    }

    /**
     * Set signature key value for the request.
     *
     * @param string|null $signatureKey
     *
     * @return $this
     */
    final public function setSignatureKey(?string $signatureKey): self
    {
        $this->signatureKey = $signatureKey;

        return $this;
    }

    /**
     * Get SDK Client Manager.
     *
     * @return \Katsana\Manager
     */
    final public function getClientManager(): Manager
    {
        if (! isset($this->manager)) {
            $this->manager = $this->container->make('katsana.manager');
        }

        return $this->manager;
    }
}
