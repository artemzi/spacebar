<?php

namespace App\Service;


use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackClient
{
    /**
     * @var Client
     */
    private $slack;

    /** @var LoggerInterface|null */
    private $logger;

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
        if ($this->logger) {
            $this->logger->info('Beaming a message to Slack!');
        }

        $message = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);
        $this->slack->sendMessage($message);
    }

    /** @required
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}