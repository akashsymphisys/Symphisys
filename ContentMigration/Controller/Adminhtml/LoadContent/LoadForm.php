<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Symphisys\ContentMigration\Controller\Adminhtml\LoadContent;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class LoadForm extends Action
{
    /**
     * @var string
     */
    public $resultFactory;
    public $resultRawFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $RawFactory,
        PageFactory $pageFactory
    )
    {
        $this->resultPageFactory = $pageFactory;
        $this->resultRawFactory = $RawFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create(); 
        $block = $resultPage->getLayout()
            ->createBlock('Symphisys\ContentMigration\Block\Adminhtml\LoadContent')
            ->setTemplate('Symphisys_ContentMigration::loadForm.phtml')
            ->toHtml();

        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($block);
        return $resultRaw;
    }
}