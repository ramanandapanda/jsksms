<?php

namespace NotificationChannels\jsksms;

use Illuminate\Notifications\Notification;
use NotificationChannels\jsksms\Exceptions\CouldNotSendNotification;

class JsksmsChannel
{
    /**
     * The jsksms client instance.
     *
     * @var JsksmsApi
     */
    protected $jsksms;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $sender;

    /**
     * @var int
     * The message body content count should be no longer than 6 message parts(918).
     */
    protected $character_limit_count = 918;

    public function __construct(JsksmsApi $jsksms)
    {
        $this->jsksms = $jsksms;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|void
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('jsksms', $notification)) {
            return;
        }

        $message = $notification->toJsksms($notifiable);

        if (is_string($message)) {
            $message = new JsksmsMessage($message);
        }

        if (mb_strlen($message->content) > $this->character_limit_count) {
            throw CouldNotSendNotification::contentLengthLimitExceeded($this->character_limit_count);
        }

        return $this->jsksms->send(trim($message->content),$to);
    }
}
