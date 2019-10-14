<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Controller\Adminhtml\LoadContent;

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

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $RawFactory,
		\Magento\Framework\App\Request\Http $request,
        PageFactory $pageFactory
    )
    {
        $this->resultPageFactory = $pageFactory;
        $this->resultRawFactory = $RawFactory;
		$this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create(); 
		$req = $this->request->getParams();
		$filter['store'] = $req['store_id'];
		$filter['type'] = $req['search_type'];		
        $block = $resultPage->getLayout()
            ->createBlock('Symphisys\ContentMigration\Block\Adminhtml\LoadContent')
			->setData('filter', $filter)
            ->setTemplate('Symphisys_ContentMigration::loadContent.phtml')
            ->toHtml();

        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($block);
        return $resultRaw;
    }
}