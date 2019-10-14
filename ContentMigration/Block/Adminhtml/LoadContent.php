<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Block\Adminhtml;

class LoadContent extends \Magento\Backend\Block\Template
{
	protected $context;
	protected $storeManager;
	protected $authSession;
	protected $model;
	public $filter;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Store\Model\StoreManagerInterface $storeManager,
			\Magento\Backend\Model\Auth\Session $authSession,
			\Magento\Framework\Registry $registry,
			\Symphisys\ContentMigration\Model\ResourceModel\Data\Collection $model,
			array $data = []
		){
		$this->_registry = $registry;
		$this->storeManager = $storeManager;
		$this->authSession = $authSession;
		$this->model = $model;
		parent::__construct($context, $data);
	}
	public function getFilters()
	{		
		return $this->filter;
	}
	public function setFilter($filter)
	{		
		$this->filter = $filter;
	}
	public function getCurrentUser()
	{
		return $this->authSession->getUser()->getEmail();
	}
	public function getCollection($filter){
		return $collection = $this->model->getCollection($filter);		
	}
	public function getStoreOptions()
	{		
		$storeManagerDataList = $this->storeManager->getStores();
		$options = array();
		 
		foreach ($storeManagerDataList as $key => $value) {
				   $options[] = ['label' => $value['name'].' - '.$value['code'], 'value' => $key];
		}
		return $options;		
	}
}