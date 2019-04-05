<?php

namespace Katsana\Http\Requests;

use Katsana\Sdk\Signature;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Webhook extends Request
{
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
     * @return array
     */
    public function validated(): array
    {
        $config = $this->container->make('katsana.manager')->config('webhook');

        $signature = new Signature($config['signature']);
        $header = $this->header('X_SIGNATURE');

        if ($signature->verify($header, $this->getContent(), ($config['threshold'] ?? 3600))) {
            throw new HttpException(419, 'Unable to verify X-Signature.');
        }

        return $this->post();
    }
}
