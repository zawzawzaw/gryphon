<?php

class Magestore_Giftvoucher_Adminhtml_GiftvoucherController extends Mage_Adminhtml_Controller_Action {

    public function exportCsvAction() {
        $fileName = 'giftcode.csv';
        $content = $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'giftcode.xml';
        $content = $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function importAction() {
        $this->loadLayout()
                ->_setActiveMenu('giftvoucher/giftvoucher')
                ->_addContent($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_import'));
        $this->_title($this->__('Gift Code'))
                ->_title($this->__('Import Gift Codes'));
        $this->renderLayout();
    }

    public function downloadSampleAction() {
        $filename = Mage::getBaseDir('media') . DS . 'giftvoucher' . DS . 'import_giftcode_sample.csv';
        $this->_prepareDownloadResponse('import_giftcode_sample.csv', file_get_contents($filename));
    }

    public function processImportAction() {
        if (isset($_FILES['filecsv'])) {
            try {
                $fileName = $_FILES['filecsv']['tmp_name'];
                $csvObject = new Varien_File_Csv();
                $dataFile = $csvObject->getData($fileName);

                $count = array();
                $fields = array();
                $giftVoucherImport = array();
                foreach ($dataFile as $row => $cols) {
                    if ($row == 0) {
                        $fields = $cols;
                    } else {
                        $giftVoucherImport[] = array_combine($fields, $cols);
                    }
                }
                foreach ($giftVoucherImport as $giftVoucherData) {
                    $giftVoucher = Mage::getModel('giftvoucher/giftvoucher');
                    if (isset($giftVoucherData['gift_code']) && $giftVoucherData['gift_code']) {
                        $giftVoucher->loadByCode($giftVoucherData['gift_code']);
                        if ($giftVoucher->getId()) {
                            Mage::getSingleton('adminhtml/session')->addError($this->__('Gift code %s already existed', $giftVoucher->getGiftCode()));
                            continue;
                        } else {
                            Mage::helper('giftvoucher')->createBarcode($giftVoucherData['gift_code']);
                        }
                    }

                    $statuses = array(
                        '1' => 1, 'pending' => 1,
                        '2' => 2, 'active' => 2,
                        '3' => 3, 'disabled' => 3,
                        '4' => 4, 'used' => 4,
                        '5' => 5, 'expired' => 5,
                    );
                    if (isset($giftVoucherData['status']) && $giftVoucherData['status'])
                        $giftVoucherData['status'] = $statuses[$giftVoucherData['status']];
                    unset($giftVoucherData['order_increment_id']);
                    if (isset($giftVoucherData['history_amount']) && $giftVoucherData['history_amount'])
                        $giftVoucherData['amount'] = $giftVoucherData['history_amount'];
                    if (isset($giftVoucherData['extra_content']) && $giftVoucherData['extra_content'])
                        $giftVoucherData['extra_content'] = str_replace('\n', chr(10), $giftVoucherData['extra_content']);
                    else
                        $giftVoucherData['extra_content'] = Mage::helper('giftvoucher')->__('Imported by %s', Mage::getSingleton('admin/session')->getUser()->getUsername());
                    $giftVoucherData['recipient_address'] = str_replace('\n', chr(10), $giftVoucherData['recipient_address']);
                    $giftVoucherData['message'] = str_replace('\n', chr(10), $giftVoucherData['message']);
                    if (!isset($giftVoucherData['currency']))
                        $giftVoucherData['currency'] = Mage::app()->getStore($giftVoucherData['store_id'])->getBaseCurrencyCode();
                    if (!isset($giftVoucherData['giftcard_template_id'])) {
                        $template = Mage::getModel('giftvoucher/gifttemplate')->getCollection()->getFirstItem();
                        $images = explode(',', $template->getImages()); 
                        
                        $giftVoucherData['giftcard_template_image'] = $images[0];
                        $giftVoucherData['giftcard_template_id'] = $template->getId();
                    }
                    try {
                        $giftVoucher->setData($giftVoucherData)
                                ->setIncludeHistory(true)
                                ->setId(null)
                                ->save();
                        $count[] = $giftVoucher->getId();
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    }
                }
                if (count($count)) {
                    $successMessage = $this->__('Imported total %d Gift Code(s)', count($count));
                    if ($this->getRequest()->getParam('print')) {
                        $url = $this->getUrl('*/*/massPrint', array(
                            'giftvoucher' => implode(',', $count)
                        ));
                        $successMessage .= "<script type='text/javascript'>document.observe('dom:loaded',function(){
                        var bob=window.open('','_blank');bob.location='" . $url . "';    
                        });</script>";
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess($successMessage);
                    $this->_redirect('*/*/index');
                    return $this;
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('No gift code imported'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('No uploaded files'));
        }
        $this->_redirect('*/*/import');
    }

    public function printAction() {
        $this->loadLayout();
        $this->renderLayout();
//        $giftCardId = $this->getRequest()->getParam('id');
//        $pdf = Mage::getModel('giftvoucher/pdf_giftcard')->getPdf(array($giftCardId));
//        $this->_prepareDownloadResponse('giftcode_' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s') . '.pdf', $pdf->render(), 'application/pdf');
    }

    public function massPrintAction() {
//        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
//        if ($giftvoucherIds && is_string($giftvoucherIds))
//            $giftvoucherIds = explode(',', $giftvoucherIds);
//        if (!is_array($giftvoucherIds) || !count($giftvoucherIds)) {
//            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Code(s)'));
//            $this->_redirect('*/*/index');
//        } else {
//            $pdf = Mage::getModel('giftvoucher/pdf_giftcard')->getPdf($giftvoucherIds);
//            $this->_prepareDownloadResponse('giftcode_' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s') . '.pdf', $pdf->render(), 'application/pdf');
//        }
        /**
         * update giftcard print
         * @return \Magestore_Giftvoucher_Adminhtml_GiftvoucherController
         */
        $this->loadLayout();
        $this->renderLayout();
        //  $this->_redirect('*/*/');
    }

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('giftvoucher/giftvoucher')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Code Manager'), Mage::helper('adminhtml')->__('Gift Code Manager'));

        return $this;
    }

    public function indexAction() {
        if (!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)) {
            return;
        }
        $this->_title($this->__('Gift Code'))
                ->_title($this->__('Manage Gift Code'));
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        if (!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)) {
            return;
        }
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('giftvoucher/giftvoucher')->load($id);
        // die($model->getMessage());
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            $this->_title($this->__('Gift Code'));
            if ($model->getId()) {
                $this->_title($model->getGiftCode());
            } else {
                $this->_title($this->__('New Gift Code'));
            }

            $model->getConditions()->setJsFormObject('giftvoucher_conditions_fieldset');
            $model->getActions()->setJsFormObject('giftvoucher_actions_fieldset');
            Mage::register('giftvoucher_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('giftvoucher/giftvoucher');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Code Manager'), Mage::helper('adminhtml')->__('Gift Code Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Code News'), Mage::helper('adminhtml')->__('Gift Code News'));

            $this->getLayout()->getBlock('head')
                    ->setCanLoadExtJs(true)
                    ->setCanLoadRulesJs(true);
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true)
                    ->addItem('js', 'tiny_mce/tiny_mce.js')
                    ->addItem('js', 'mage/adminhtml/wysiwyg/tiny_mce/setup.js')
                    ->addJs('mage/adminhtml/browser.js')
                    ->addJs('prototype/window.js')
                    ->addJs('lib/flex.js')
                    ->addJs('mage/adminhtml/flexuploader.js');
            $this->_addContent($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit'))
                    ->_addLeft($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('Gift Code does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('giftvoucher/giftvoucher');

            $data = $this->_filterDates($data, array('expired_at'));
            if (!$data['expired_at'])
                $data['expired_at'] = null;
            unset($data['order_increment_id']);
            $data['status'] = $data['giftvoucher_status'];
            $data['comments'] = $data['giftvoucher_comments'];
            $data['amount'] = $data['balance'];
            if (isset($data['rule'])) {
                $rules = $data['rule'];
                if (isset($rules['conditions'])) {
                    $data['conditions'] = $rules['conditions'];
                }
                if (isset($rules['actions'])) {
                    $data['actions'] = $rules['actions'];
                }
                unset($data['rule']);
            }

            if ($this->getRequest()->getParam('id')) {
                $data['action'] = Magestore_Giftvoucher_Model_Actions::ACTIONS_UPDATE;
                $data['extra_content'] = Mage::helper('giftvoucher')->__('Updated by %s', Mage::getSingleton('admin/session')->getUser()->getUsername());
            } else {
                $data['extra_content'] = Mage::helper('giftvoucher')->__('Created by %s', Mage::getSingleton('admin/session')->getUser()->getUsername());
            }
            $increment_id = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->joinHistory()
                            ->addFieldToFilter('history.giftvoucher_id', $this->getRequest()->getParam('id'))->getFirstItem()->getOrderIncrementId();

            if (!$data['giftcard_template_id']) {
                $template = Mage::getModel('giftvoucher/gifttemplate')->getCollection()->getFirstItem();
                $template_images = explode(',', $template->getImages());

                $data['giftcard_template_id'] = $template->getId();
                $data['giftcard_template_image'] = $template_images[0];
            }

            $model->setData($data)
                    ->setIncludeHistory(true)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                $model->loadPost($data);
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('giftvoucher')->__('Gift Code was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('sendemail')) {
                        $emailSent = (int) $model->sendEmail()->getEmailSent();
                        if ($emailSent) {
                            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('giftvoucher')->__('and (%d) email(s) were sent.', $emailSent));
                        } else {
                            $allowStatus = explode(',', Mage::helper('giftvoucher')->getEmailConfig('only_complete', $model->getStoreId()));
                            if (!$model->getRecipientEmail()) {
                                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('There is no email address to send.'));
                            } else {
                                $options = Mage::getModel('giftvoucher/status')->getOptionArray();
                                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('gift card is %s should not send an email, %s', $options[$model->getStatus()], '<a href="' . $this->getUrl('adminhtml/system_config/edit/section/giftvoucher') . '">' . Mage::helper('giftvoucher')->__(' view config select status of gift card when sending e-mail to friend') . '</a>'));
                            }
                        }
                    }
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('Unable to find Gift Code to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('giftvoucher/giftvoucher');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Gift Code was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if (!is_array($giftvoucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Code(s)'));
        } else {
            try {
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getModel('giftvoucher/giftvoucher')->load($giftvoucherId);
                    $giftvoucher->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($giftvoucherIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * gift template image update
     */
    public function giftimagesAction() {
        $template_id = $this->getRequest()
                ->getParam('gift_template_id');
        $current_image = $this->getRequest()
                ->getParam('current_image');
        $customer_upload_image = $this->getRequest()
                ->getParam('custom_image');
        if (!$template_id && $customer_upload_image == 'false') {
            echo '';
            return;
        }
        $template = Mage::getModel('giftvoucher/gifttemplate')->load($template_id);
        $images = $template->getImages();
        if ($customer_upload_image == 'true')
            $images = $current_image;
        $str = '';
        if ($images) {
            $str.='<div class="carousel" id="gift-image-carosel">
                            <a href="javascript:" class="carousel-control next" rel="next">›</a>
                            <a href="javascript:" class="carousel-control prev" rel="prev">‹</a>
                            <div class="gift-middle" id="carousel-wrapper">
                                <div class="inner" style="width: 3000px;">
                  ';
            $type = '';
            switch ($template->getDesignPattern()) {
                case Magestore_Giftvoucher_Model_Designpattern::PATTERN_LEFT:
                    $type = 'left/';
                    break;
                case Magestore_Giftvoucher_Model_Designpattern::PATTERN_TOP:
                    $type = 'top/';
                    break;
                case Magestore_Giftvoucher_Model_Designpattern::PATTERN_CENTER:
                    $type = '';
                    break;
            }
            $images = explode(',', $images);
            $count = 0;
            $select_image = 0;
            foreach ($images as $image) {
                $str.='<div id="div-image-for-' . $template_id . '-' . $count . '" style="position:relative; float: left;border: 2px solid white;">';
                $str.='<img src="' . Mage::getBaseUrl("media") . 'giftvoucher/template/images/' . $type . $image . '" alt="" style="width:80px;height:80px"
                    onclick="changeSelectImages(' . $count . ',\'' . $image . '\')">';
                $str.= '<div class="egcSwatch-arrow" style="display:none"></div>';
                $str.='</div>';
                if ($image == $current_image) {
                    $select_image = $count;
                }
                $count++;
            }
            if ($current_image) {
                $str.='<input type="hidden" id="current_image" value=' . $current_image . '>';

                $str.='<input type="hidden" id="selected_image" value=' . $select_image . '>';
            } else {
                $str.='<input type="hidden" id="current_image" value=' . $images[0] . '>';

                $str.='<input type="hidden" id="selected_image" value="0">';
            }
            $str.='</div>
                </div>
               </div>';
        }
        $this->getResponse()->setBody($str);
        return;
    }

    public function massStatusAction() {
        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if (!is_array($giftvoucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Gift Code(s)'));
        } else {
            try {
                $cnt = 0;
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getSingleton('giftvoucher/giftvoucher')
                            ->load($giftvoucherId);
                    if ($giftvoucher->getStatus() < Magestore_Giftvoucher_Model_Status::STATUS_EXPIRED) {
                        $giftvoucher->setStatus($this->getRequest()->getParam('status'));
                        $giftvoucher->setIsMassupdate(true)
                                ->setAction(Magestore_Giftvoucher_Model_Actions::ACTIONS_MASS_UPDATE)
                                ->setExtraContent(Mage::helper('giftvoucher')->__('Mass status updated by %s', Mage::getSingleton('admin/session')->getUser()->getUsername()))
                                ->setIncludeHistory(true)
                                ->save();
                        $cnt++;
                    }
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', $cnt)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function historygridAction() {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tab_history')->setGiftvoucher($this->getRequest()->getParam('id'))->toHtml()
        );
    }

    public function massEmailAction() {
        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if (!is_array($giftvoucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Code(s)'));
        } else {
            try {
                $totalEmailSent = 0;
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getModel('giftvoucher/giftvoucher')->load($giftvoucherId);
                    $emailSent = (int) $giftvoucher->sendEmail()->getEmailSent();
                    if ($emailSent)
                        $totalEmailSent += $emailSent;
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d Gift Code with %d email(s) were successfully sent.', count($giftvoucherIds), $totalEmailSent
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function historyAction() {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('giftvoucher/adminhtml_customer_tab_history')->toHtml()
        );
    }

}
