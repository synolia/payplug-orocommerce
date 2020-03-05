<?php

namespace Payplug\Bundle\PaymentBundle\Service;

use Psr\Log\LoggerAwareTrait;

class Logger
{
    use LoggerAwareTrait;

    /** @var Anonymizer */
    protected $anonymizer;

    /** @var bool */
    protected $debugMode = false;

    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    public function setDebugMode(bool $status): void
    {
        $this->debugMode = $status;
    }

    public function debug(string $message): void
    {
        if (false === $this->debugMode) {
            return;
        }

        $this->logger->debug($message);
    }

    public function error(string $message): void
    {
        $this->logger->error($message);
    }

    public function anonymizeAndJsonEncodeArray(array $data): ?string
    {
        $this->anonymizer->anonymizeArray($data);
        return json_encode($data);
    }
}
