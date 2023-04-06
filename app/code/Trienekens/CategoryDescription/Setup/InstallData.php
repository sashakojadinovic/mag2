<?php
namespace Trienekens\CategoryDescription\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class InstallData implements InstallDataInterface
{
    /**
    * Category setup factory
    *
    * @var CategorySetupFactory
    */
    private $eavSetupFactory;
    /**
    * Init
    *
    * @param EavSetupFactory $eavSetupFactory
    */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
         $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        
        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
        \Magento\Catalog\Model\Category::ENTITY, 'trienekens_description', [
            'type' => 'text',
            'label' => 'Category Long Description',
            'input' => 'textarea',
            'required' => false,
            'sort_order' => 100,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'wysiwyg_enabled' => true,
            'group' => 'General Information',
            'is_html_allowed_on_front' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true
        ]
        );
        $setup->endSetup();
    }
}