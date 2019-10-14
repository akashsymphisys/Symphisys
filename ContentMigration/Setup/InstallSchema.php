<?php

/**
 * sk
 * Copyright (C) 2018 bkumarjyoti
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category bkumarjyoti
 * @package Symphisys_Productattach
 * @copyright Copyright (c) 2018 bkumarjyoti
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author sk
 */

namespace Symphisys\ContentMigration\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Jr\Productattach\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /*
         * Drop tables if exists
         */

        $installer->getConnection()->dropTable($installer->getTable('cms_migration_data'));
        $installer->getConnection()->dropTable($installer->getTable('block_migration_data'));
		$installer->getConnection()->dropTable($installer->getTable('product_migration_data'));
		$installer->getConnection()->dropTable($installer->getTable('category_migration_data'));
		$installer->getConnection()->dropTable($installer->getTable('post_migration_data'));
		$installer->getConnection()->dropTable($installer->getTable('content_migration'));
		
		/**
         * Creating table for content_migration
         */
		
        $table_content_migration = $installer->getConnection()->newTable($installer->getTable('content_migration'));
        $table_content_migration->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Content Id'
        );
		
		$table_content_migration->addColumn(
            'version_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Version Name'
        );
		
		$table_content_migration->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'Store Id'
        );
		
		
		$table_content_migration->addColumn(
            'ref_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ref Type'
        );
		
		$table_content_migration->addColumn(
            'admin_user',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Admin User'
        );
		
		$table_content_migration->addColumn(
            'preview_image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Preview Image'
        );
		
		$table_content_migration->addColumn(
            'created_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created Date'
        );
		
		$installer->getConnection()->createTable($table_content_migration);
		
		
		
        /**
         * Creating table cms_migration_data
         */
        $table_cms_migration_data = $installer->getConnection()->newTable($installer->getTable('cms_migration_data'));
        
        $table_cms_migration_data->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Data Content Id'
        );

        $table_cms_migration_data->addColumn(
            'content_migration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['unsigned' => true, 'nullable' => false],
            'Content Id'
        );
		
		$table_cms_migration_data->addColumn(
            'ref_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'ref Id'
        );

        $table_cms_migration_data->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Content'
        );

        $table_cms_migration_data->addColumn(
            'layout',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Layout'
        );

        $table_cms_migration_data->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        );
		
        $table_cms_migration_data->addForeignKey(
			$installer->getFkName(
				'cms_migration_data',
				'content_migration_id',
				'content_migration',
				'id'
			),
			'content_migration_id',
			$installer->getTable('content_migration'), 
			'id',
			\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		);
		$table_cms_migration_data->setComment('cms table');
        $installer->getConnection()->createTable($table_cms_migration_data);
		
		
		/**
         * Creating table block_migration_data
         */

        $table_block_migration_data= $installer->getConnection()->newTable($installer->getTable('block_migration_data'));
        
        $table_block_migration_data->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Data Content Id'
        );

        $table_block_migration_data->addColumn(
            'content_migration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['unsigned' => true, 'nullable' => false],
            'Content Id'
        );
		
		$table_block_migration_data->addColumn(
            'ref_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'ref Id'
        );

        $table_block_migration_data->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Content'
        );

        $table_block_migration_data->addColumn(
            'layout',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Layout'
        );

        $table_block_migration_data->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        );
		
        $table_block_migration_data->addForeignKey(
			$installer->getFkName(
				'block_migration_data',
				'content_migration_id',
				'content_migration',
				'id'
			),
			'content_migration_id',
			$installer->getTable('content_migration'), 
			'id',
			\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		);
		$table_block_migration_data->setComment('block table');
        $installer->getConnection()->createTable($table_block_migration_data);
		
		
		/**
         * Creating table product_migration_data
         */

        $table_product_migration_data= $installer->getConnection()->newTable($installer->getTable('product_migration_data'));
        
        $table_product_migration_data->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Data Content Id'
        );

        $table_product_migration_data->addColumn(
            'content_migration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['unsigned' => true, 'nullable' => false],
            'Content Id'
        );
		
		$table_product_migration_data->addColumn(
            'ref_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'ref Id'
        );

        $table_product_migration_data->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Content'
        );

        $table_product_migration_data->addColumn(
            'layout',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Layout'
        );

        $table_product_migration_data->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        );
		
        $table_product_migration_data->addForeignKey(
			$installer->getFkName(
				'product_migration_data',
				'content_migration_id',
				'content_migration',
				'id'
			),
			'content_migration_id',
			$installer->getTable('content_migration'), 
			'id',
			\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		);
		$table_product_migration_data->setComment('product table');
        $installer->getConnection()->createTable($table_product_migration_data);
		
		
		
		/**
         * Creating table category_migration_data
         */

        $table_category_migration_data= $installer->getConnection()->newTable($installer->getTable('category_migration_data'));
        
        $table_category_migration_data->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Data Content Id'
        );

        $table_category_migration_data->addColumn(
            'content_migration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
			['unsigned' => true, 'nullable' => false],
            'Content Id'
        );
		
		$table_category_migration_data->addColumn(
            'ref_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'ref Id'
        );

        $table_category_migration_data->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Content'
        );

        $table_category_migration_data->addColumn(
            'layout',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Layout'
        );

        $table_category_migration_data->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        );
		
        $table_category_migration_data->addForeignKey(
			$installer->getFkName(
				'category_migration_data',
				'content_migration_id',
				'content_migration',
				'id'
			),
			'content_migration_id',
			$installer->getTable('content_migration'), 
			'id',
			\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		);
		$table_category_migration_data->setComment('category table');
        $installer->getConnection()->createTable($table_category_migration_data);
		
		
		/**
         * Creating table post_migration_data
         */

        $table_post_migration_data= $installer->getConnection()->newTable($installer->getTable('post_migration_data'));
        
        $table_post_migration_data->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Data Content Id'
        );

        $table_post_migration_data->addColumn(
            'content_migration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['unsigned' => true, 'nullable' => false],
            'Content Id'
        );
		
		$table_post_migration_data->addColumn(
            'ref_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true,'default' => 0],
            'ref Id'
        );

        $table_post_migration_data->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Content'
        );

        $table_post_migration_data->addColumn(
            'layout',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => true,'default' => null],
            'Layout'
        );

        $table_post_migration_data->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        );
		
        $table_post_migration_data->addForeignKey(
			$installer->getFkName(
				'post_migration_data',
				'content_migration_id',
				'content_migration',
				'id'
			),
			'content_migration_id',
			$installer->getTable('content_migration'), 
			'id',
			\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		);
		$table_post_migration_data->setComment('post table');
        $installer->getConnection()->createTable($table_post_migration_data);
		
    }
}
