<?php namespace Zero\Base\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /* Drop tables if exists */
        $installer->getConnection()->dropTable($installer->getTable('zero_custom_field'));

        /* Promo table */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('zero_custom_field'))
            ->addColumn('id', Table::TYPE_INTEGER, 11, ['identity' => true, 'nullable' => false, 'primary' => true], 'ID')
            ->addColumn('entity_id', Table::TYPE_INTEGER, 11, ['nullable' => false], 'Entity ID')
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Name')
            ->addColumn('value', Table::TYPE_TEXT, 512, ['nullable' => false, 'default' => ''], 'Value')
            ;

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
