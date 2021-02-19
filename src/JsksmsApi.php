<?php

namespace NotificationChannels\jsksms;

use DomainException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use NotificationChannels\jsksms\Exceptions\CouldNotSendNotification;

class JsksmsApi
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $token;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->client = $httpClient;

        $this->endpoint = config('services.jsksms.endpoint', 'http://jskbulkmarketing.in/app/smsapi/index.php');
        $this->params = config('services.jsksms');
    }

    /**
     * Send text message.
     *
     * <code>
     * $message = [
     *   'sender'   => '',
     *   'to'       => '',
     *   'message'  => '',
     *   'test'     => '',
     * ];
     * </code>
     *
     * @link https://jsksms.com/rest-api-documentation/send?version=2
     *
     * @param array $message
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws CouldNotSendNotification
     */
    public function send($message = "", $to = '')
    {
        try {
            // $response = $this->client->request('POST', $this->endpoint, [
            //     'headers' => [
            //         'Authorization' => "Bearer {$this->token}",
            //     ],
            //     'json' => [
            //         'sender' => Arr::get($message, 'sender'),
            //         'to' => Arr::get($message, 'to'),
            //         'message' => Arr::get($message, 'message'),
            //         'test' => Arr::get($message, 'test', false),
            //     ],
            // ]);

            $params = [
                'msg' => $message,
                'contacts' => $to
            ];
            $params = array_merge($params, $this->params);
            $response =  $this->client->request('GET', $this->endpoint, [
                'query' => $params
            ]);
            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (ClientException $e) {
            throw CouldNotSendNotification::jsksmsRespondedWithAnError($e);
        } catch (Exception $e) {
            throw CouldNotSendNotification::couldNotCommunicateWithjsksms($e);
        }
    }
}
