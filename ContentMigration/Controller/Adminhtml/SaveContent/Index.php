<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Controller\Adminhtml\SaveContent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var string
     */
    public $resultFactory;
    public $resultRawFactory;
    public $request;
    public $model;
    protected $authSession;


    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $RawFactory,
		\Magento\Framework\App\Request\Http $request,
        PageFactory $pageFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Symphisys\ContentMigration\Model\ResourceModel\Data\Collection $model
    )
    {
        $this->resultPageFactory = $pageFactory;
        $this->resultRawFactory = $RawFactory;
		$this->request = $request;
        $this->model = $model;
        $this->authSession = $authSession;
        parent::__construct($context);
    }

    public function execute()
    {
        
        $resultPage = $this->resultPageFactory->create(); 
		$param = $this->request->getParams();
        $adminUser = $this->authSession->getUser()->getEmail();
        $data = array(
            'version' => $param['version'],
            'storeId' => $param['storeId'],
            'admin' => $adminUser,
            'content' => $param['content'],
            'refId' => $param['refId'],
            'refType' => $param['refType']
        );       
        $this->model->saveLayout($data);        
    }
}