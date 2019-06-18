<?php

namespace Katsana\Tests\Http\Requests;

use Carbon\Carbon;
use Katsana\Http\Requests\Webhook;
use Katsana\Tests\TestCase;

class WebhookTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['router']->post('webhook/checkpoint/{secret?}', function (Webhook $request, $secret = null) {
            if (! empty($secret)) {
                $request->setSignatureKey($secret);
            }

            $request->validated();

            return \response('OK', 200);
        });
    }

    /** @test */
    public function it_can_handle_checkpoint_webhook()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546300800));

        config(['services.katsana' => [
            'webhook' => [
                'signature' => 'secret',
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint', $data, ['Content-Type' => 'application/json', 'X-Signature' => 't=1546300800,v1=633da88fcf432a0dafe2282e8a65eeea9ce6b570070b12a4db403b6767450b03']
        )->assertStatus(200);
    }

    /** @test */
    public function it_cant_handle_checkpoint_webhook_when_signature_invalid()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546300800));

        config(['services.katsana' => [
            'webhook' => [
                'signature' => 'secret!!!',
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint', $data, ['Content-Type' => 'application/json', 'X-Signature' => 't=1546300800,v1=633da88fcf432a0dafe2282e8a65eeea9ce6b570070b12a4db403b6767450b03']
        )->assertStatus(419);
    }

    /** @test */
    public function it_can_handle_checkpoint_webhook_using_custom_signature()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546300800));

        config(['services.katsana' => [
            'webhook' => [
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint/secret', $data, ['Content-Type' => 'application/json', 'X-Signature' => 't=1546300800,v1=633da88fcf432a0dafe2282e8a65eeea9ce6b570070b12a4db403b6767450b03']
        )->assertStatus(200);
    }

    /** @test */
    public function it_cant_handle_checkpoint_webhook_when_signature_invalid_using_custom_signature()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546300800));

        config(['services.katsana' => [
            'webhook' => [
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint/rahsia', $data, ['Content-Type' => 'application/json', 'X-Signature' => 't=1546300800,v1=633da88fcf432a0dafe2282e8a65eeea9ce6b570070b12a4db403b6767450b03']
        )->assertStatus(419);
    }

    /** @test */
    public function it_cant_handle_checkpoint_webhook_when_signed_header_is_missing()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546300800));

        config(['services.katsana' => [
            'webhook' => [
                'signature' => 'secret!!!',
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint', $data, ['Content-Type' => 'application/json']
        )->assertStatus(419);
    }

    /** @test */
    public function it_cant_handle_checkpoint_webhook_when_signature_expired()
    {
        Carbon::setTestNow($now = Carbon::createFromTimestamp(1546310800));

        config(['services.katsana' => [
            'webhook' => [
                'signature' => 'secret',
                'threshold' => 3600,
            ],
        ]]);

        $data = [
            'policy_id' => 42,
            'message' => "WXG3365 has entered 'Somewhere'",
            'checkpoint_name' => 'Somewhere',
            'checkpoint_type' => 'enter',
            'ping' => [
                'latitude' => 3.1619,
                'longitude' => 101.6157113,
                'speed' => 12.3,
            ],
            'device_id' => 123,
            'event' => 'Checkpoint triggered',
        ];

        return $this->postJson(
            'webhook/checkpoint', $data, ['Content-Type' => 'application/json', 'X-Signature' => 't=1546300800,v1=633da88fcf432a0dafe2282e8a65eeea9ce6b570070b12a4db403b6767450b03']
        )->assertStatus(419);
    }
}
