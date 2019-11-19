<?php


namespace OrviSoft\Reevoo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Feed extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

     /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_productCollection;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $imageHelperFactory;
    
    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $_category;

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
    protected $_dir;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Catalog\Helper\Image $imageHelperFactory,
        \Magento\Catalog\Model\Category $category,
        \Magento\Framework\Filesystem\DirectoryList $dir
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->_productCollection = $productCollection;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->_category = $category;
        $this->_dir = $dir;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $status = (boolean)$this->getConfig(self::PATH_REEVOO_STATUS);
        if($status && strlen($this->getConfig('revoo/option/partner_id')) > 0){
            return true;
        }
        $this->logger->addInfo(__("Inactive due to status or partner id missing."));
        return false;
    }

    public function getConfig($config){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue($config, $storeScope);
    }

    public function geAttribute(){
        return $this->getConfig('revoo/option/attribute');
    }

    public function getCategory($id){
        $id = $this->getLevel1Category($id);
        return $this->_category->load($id);
    }

    public function getLevel1Category($id){
        $category = $this->_category->load($id);
        if($category){
            if($category->getParentCategories()){
                foreach ($category->getParentCategories() as $parent) {
                    if ($parent->getLevel() == 1) {
                        // reurns the level 1 category id;
                        return $parent->getId();
                    }
                }
            }
        }
        return null;
    }


    public function generateProductFeed($debug = false){
        if(!$this->isEnabled()){
            return __("Module isn't active.");
        }

        if(!$this->getConfig('revoo/option/enable_product_feed') || !$this->getConfig('revoo/feed_settings/enable')){
            return __("Product Feeds aren't active.");
        }
        
        $feed = $this->getProductsFeed($debug);
        if(!$debug):
            $feed_dir = $this->_dir->getPath('var').DIRECTORY_SEPARATOR.'revoo_feed' ;
            if(!is_dir($feed_dir)){
                mkdir($feed_dir);
            }
            $saveFileName = $feed_dir.DIRECTORY_SEPARATOR.date('m-d-Y').".csv";
            $fopen = fopen($saveFileName, 'w');
            $csvHeader = array("sku", 'Product_Group_Name' ,'Product_Group_ID' , 'name', "model" , 'manufacturer' , 'product_category' , 'ean', 'mpn', 'description', 'image_url');// Add the fields you need to export
            fputcsv( $fopen , $csvHeader,",");
            foreach($feed as $key => $value){
                fputcsv($fopen, $value, ",");
            }
        else:
            echo '<pre>';
            print_r($feed);
            echo '</pre>';
        endif;
    }

    public function getProductsFeed($debug = false){
        $_products = $this->_productCollection->addAttributeToSelect ( '*' );
        $_products->addAttributeToFilter ( 'visibility', array (
            'eq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH 
        ) );
        $_products->addAttributeToSort ( 'entity_id', 'DESC' );
        $_products->setFlag('has_stock_status_filter', false);
        $output = [];
        $increment = 0;
        $sku = $this->geAttribute();
        foreach($_products as $_product){
            if($debug && $increment > 10){
                break 1;
            }
            $image = $this->imageHelperFactory->init($_product, 'product_thumbnail_image')
                                                ->setImageFile($_product->getFile())
                                                ->constrainOnly(true)
                                                ->keepAspectRatio(true)
                                                ->keepTransparency(true)
                                                ->keepFrame(false)
                                                ->resize(200, 300)->getUrl();
            $attribute_brand = $_product->getResource()->getAttribute('brand');
            if ($attribute_brand){
                $brandvalue = $attribute_brand ->getFrontend()->getValue($_product);
            }
            $attribute = $_product->getResource()->getAttribute('ean');
            if ($attribute){
                $ean = $attribute->getFrontend()->getValue($_product);
                $ean = $ean == 'No' ? '' : $ean;
            }
            $attribute_mpn = $_product->getResource()->getAttribute('mpn');
            if ($attribute_mpn){
                $mpn = $attribute_mpn->getFrontend()->getValue($_product);
                $mpn = $mpn == 'No' ? '' : $mpn;
            }
            $categories = $_product->getCategoryIds();
            foreach($categories as $catID){
                $_category = $this->getCategory($catID)->getName();
                break 1;
            }
            $_sku = $_product->getData($sku);
            $groupID = explode("-", $_sku);
            $output[] = [
                'sku' => $_sku,
                'Product_Group_Name' => $_product->getName(),
                'Product_Group_ID' => $groupID[0],
                'name' => $_product->getName(),
                'model' => $_product->getModel(),
                'manufacturer' => $brandvalue,
                'product_category' => $_category,
                'ean' => $ean,
                'mpn' => $groupID[0],
                'description' => $_product->getName(),
                'image_url' => $image
            ];
            $increment++;
        }
        return $output;
    }

}