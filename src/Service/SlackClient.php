<?php

namespace App\Service;


use Nexy\Slack\Client;

class SlackClient
{
    /**
     * @var Client
     */
    private $slack;

    /**
     * SlackClient constructor.
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
    public function sendMessage(string $from, string $message): void
    {
        $message = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);
        $this->slack->sendMessage($message);
    }
}