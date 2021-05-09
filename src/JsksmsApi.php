<?php

namespace RamanandaPanda\Jsksms;

use DomainException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use RamanandaPanda\Jsksms\Exceptions\CouldNotSendNotification;

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

        $this->endpoint = config('services.jsksms.endpoint');
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
            $params = [
                'msg' => $message,
                'contacts' => $to
            ];
            $params = array_merge($params, $this->params);
            $res =  $this->client->request('GET', $this->endpoint, [
                'query' => $params
            ]);
            $response = json_decode((string) $res->getBody(), true);
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
