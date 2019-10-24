<?php


namespace OrviSoft\Reevoo\Block\Product\View;

class Reviews extends \Magento\Framework\View\Element\Template
{
    protected $_helper;
    protected $_registry;
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \OrviSoft\Reevoo\Helper\Productreviews $helper,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_registry = $registry;
    }

    /**
     * @return string
     */
    public function displayReviews()
    {
        //Your block code
        return __('Hello Developer! This how to get the storename: %1 and this is the way to build a url: %2', $this->_storeManager->getStore()->getName(), $this->getUrl('contacts'));
    }

    public function getHelper(){
        return $this->_helper;
    }

    public function getCurrentProduct()
    {       
        return $this->_registry->registry('current_product');
    }
}
