<?php

namespace App\Service;

use App\Helper\LoggerTrait;
use Nexy\Slack\Client;

class SlackClient
{
    use LoggerTrait;

    /**
     * @var Client
     */
    private $slack;

    /**
     * @param Client $slack
     */
    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @param string $from
     * @param string $message
     * @throws \Http\Client\Exception
     */
    public function sendMessage(string $from, string $message)
    {
        $this->logInfo(
            'Send slack message',
            [
                'from' => $from,
                'message' => $message,
            ]
        );

        $slackMessage = $this->slack->createMessage();

        $slackMessage
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);

        $this->slack->sendMessage($slackMessage);
    }
}