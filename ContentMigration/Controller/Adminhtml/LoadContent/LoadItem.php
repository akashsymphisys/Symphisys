<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Controller\Adminhtml\LoadContent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class LoadItem extends Action
{
    /**
     * @var string
     */ 
    public $model;
    public $request;

    public function __construct(
        Context $context,
		\Magento\Framework\App\Request\Http $request,
        \Symphisys\ContentMigration\Model\ResourceModel\Data\Collection $model,
		\Magento\Framework\Controller\Result\RawFactory $RawFactory
    )
    {        
		$this->model = $model;
		$this->request = $request;
		$this->resultRawFactory = $RawFactory;
        parent::__construct($context);
    }

    public function execute()
    {
	   $req = $this->request->getParams();        
	   $resultRaw = $this->resultRawFactory->create();
       $resultRaw->setContents($this->model->getLayoutData($req['item_id']));
       return $resultRaw;
    }
}