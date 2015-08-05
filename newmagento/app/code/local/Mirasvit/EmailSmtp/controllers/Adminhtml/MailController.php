<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Follow Up Email
 * @version   1.0.2
 * @build     437
 * @copyright Copyright (C) 2015 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_EmailSmtp_Adminhtml_MailController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction ()
    {
        $this->loadLayout()->_setActiveMenu('email');

        return $this;
    }

    public function indexAction ()
    {
        $this->_title($this->__('Mail Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()
            ->createBlock('emailsmtp/adminhtml_mail'));
        $this->renderLayout();
    }

	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('email/email_system/emailsmtp');
	}
}