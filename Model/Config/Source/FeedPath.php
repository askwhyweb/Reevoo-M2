<?php


namespace OrviSoft\Reevoo\Model\Config\Source;

class FeedPath implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [['value' => 'local', 'label' => __('Local')],['value' => 'ftp', 'label' => __('FTP')]];
    }

    public function toArray()
    {
        return ['local' => __('Local'),'ftp' => __('FTP')];
    }
}
