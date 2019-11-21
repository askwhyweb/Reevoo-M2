<?php


namespace OrviSoft\Reevoo\Cron;

class CronProductExport
{

    protected $logger;
    protected $_helper;
    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger,\OrviSoft\Reevoo\Helper\Feed $data)
    {
        $this->logger = $logger;
        $this->_helper = $data;
    }

    /**
     * Export the products
     *
     * @return void
     */
    public function export()
    {
        $this->logger->addInfo("Cronjob CronProductExport begin.");
        $this->_helper->generateProductFeed();
        $this->logger->addInfo("Cronjob CronProductExport finished.");
    }
}
