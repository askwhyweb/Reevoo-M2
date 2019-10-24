<?php


namespace OrviSoft\Reevoo\Cron;

class CronProductExport
{

    protected $logger;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Export the products
     *
     * @return void
     */
    public function export()
    {
        $this->logger->addInfo("Cronjob CronProductExport is executed.");
    }
}
