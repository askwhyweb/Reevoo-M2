<?php


namespace OrviSoft\Reevoo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Productreviews extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
   
    /**
     * Recipient reevoo status config path
     */
    const PATH_REEVOO_STATUS = 'revoo/option/enable';

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $status = (boolean)$this->getConfig(self::PATH_REEVOO_STATUS);
        if($status && strlen($this->getPartner()) > 0){
            return true;
        }
        $this->logger->addInfo(__("Inactive due to status or partner id missing."));
        return false;
    }

    public function getConfig($config){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue($config, $storeScope);
    }

    public function getPartner(){
        return $this->getConfig('revoo/option/partner_id');
    }

    public function geAttribute(){
        return $this->getConfig('revoo/option/attribute');
    }

    public function gePerPage(){
        return $this->getConfig('revoo/option/per_page');
    }

    public function getReviewPerProduct($_product){
        $attribute = $this->geAttribute();
        $sku_value = $_product->getData($attribute);
        $html = '';
        if($sku_value > 0){
            $html = '<span class="revoo-col"><a class="reevoomark" href="https://mark.reevoo.com/partner/'.$this->getPartner().'/series:'.$sku_value.'">Read reviews</a></span>';
        }
        return $html;
    }
}
