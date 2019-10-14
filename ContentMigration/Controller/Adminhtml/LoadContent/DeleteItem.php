<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Controller\Adminhtml\LoadContent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class DeleteItem extends Action
{
    /**
     * @var string
     */ 
    public $model;
    public $request;

    public function __construct(
        Context $context,
		\Magento\Framework\App\Request\Http $request,
        \Symphisys\ContentMigration\Model\ResourceModel\Data\Collection $model
    )
    {        
		$this->model = $model;
		$this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
	   $req = $this->request->getParams();
       echo $this->model->deleteItem($req['item_id']);	
    }
}