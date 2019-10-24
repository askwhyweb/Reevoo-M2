<?php


namespace OrviSoft\Reevoo\Model\Config\Source;
use \Magento\Eav\Api\AttributeRepositoryInterface;
use \Magento\Framework\Api\SearchCriteriaBuilder;

class Attribute implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository, SearchCriteriaBuilder $searchCriteriaBuilder){
        $this->attributeRepository = $attributeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function toOptionArray()
    {
        $searchCriteria = $this->searchCriteriaBuilder
        //->addFilter('frontend_input', 'text')
        ->create();
        $attributeRepository = $this->attributeRepository->getList('catalog_product', $searchCriteria);
        $data = [];
        foreach ($attributeRepository->getItems() as $items) {
            $data[] = ['value' => $items->getAttributeCode(), 'label'=> __($items->getFrontendLabel())];
        }
        return $data;
    }

    public function toArray()
    {
        return ['a' => __('a'),'b' => __('b')];
    }
}
