<?php

namespace RamanandaPanda\Jsksms\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 918 characters.
     *
     * @param $count
     * @return static
     */
    public static function contentLengthLimitExceeded($count): self
    {
        return new static("Notification was not sent. Content length may not be greater than {$count} characters.", 422);
    }

    /**
     * Thrown when we're unable to communicate with jsksms.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function jsksmsRespondedWithAnError(ClientException $exception): self
    {
        if (! $exception->hasResponse()) {
            return new static('jsksms responded with an error but no response body found');
        }

        return new static("jsksms responded with an error '{$exception->getCode()} : {$exception->getMessage()}'", $exception->getCode(), $exception);
    }

    /**
     * Thrown when we're unable to communicate with jsksms.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithjsksms(Exception $exception): self
    {
        return new static("The communication with jsksms failed. Reason: {$exception->getMessage()}", $exception->getCode(), $exception);
    }
}
