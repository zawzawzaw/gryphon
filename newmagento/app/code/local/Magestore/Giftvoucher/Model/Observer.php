<?php

class Magestore_Giftvoucher_Model_Observer {

    public function couponPostAction($observer) {
        $action = $observer->getEvent()->getControllerAction();
        $session = Mage::getSingleton('checkout/session');
        $quote = $session->getQuote();
        $code = trim($action->getRequest()->getParam('coupon_code'));

        if (!$code) {
            return $this;
        }

        if (!Mage::helper('magenotification')->checkLicenseKey('Giftvoucher')) {
            return;
        }

        if (!Mage::helper('giftvoucher')->isAvailableToAddCode()) {
            return $this;
        }
        $giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);

        if ($giftVoucher->getId() && $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBaseBalance() > 0 && $giftVoucher->validate($quote->setQuote($quote))
        ) {
            if ($quote->getCouponCode() && !Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon')) {
                $session->addNotice(Mage::helper('giftvoucher')->__('A coupon code has been used. You cannot apply gift codes with the coupon to get discount.'));
            } else {
                $count = 0;
                $items = $quote->getAllItems();
                foreach ($items as $item) {
                    $data = $item->getData();
                    if ($data['product_type'] == 'giftvoucher') {
                        $count++;
                    }
                }
                if ($count == count($items)) {
                    $session->addNotice(Mage::helper('giftvoucher')->__('Gift Cards cannot be used to purchase Gift Card products'));
                } else {
                    $giftVoucher->addToSession($session);
                    $session->setUseGiftCard(1);
                    $session->addSuccess(Mage::helper('giftvoucher')->__('Gift code "%s" was applied successfully.', Mage::helper('giftvoucher')->getHiddenCode($giftVoucher->getGiftCode())));
                }
            }
            $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $action->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
        } else {
            if (!Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($session->getUseGiftCreditAmount() > 0 || $session->getGiftVoucherDiscount() > 0)) {
                if ($session->getUseGiftCardCredit() && $session->getUseGiftCard()) {
                    $session->addNotice(Mage::helper('giftvoucher')->__('You cannot apply a coupon code with either gift codes or Gift Card credit at once to get discount.'));
                } elseif ($session->getUseGiftCard()) {
                    $session->addNotice(Mage::helper('giftvoucher')->__('The gift code(s) has been used. You cannot apply a coupon code with gift codes to get discount.'));
                } elseif ($session->getUseGiftCardCredit()) {
                    $session->addNotice(Mage::helper('giftvoucher')->__('An amount in your Gift Card credit has been used. You cannot apply a coupon code with Gift Card credit to get discount.'));
                }
                $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $action->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
            }
        }
        return $this;
    }

    public function collectTotalsAfter($observer) {
        if ($code = trim(Mage::app()->getRequest()->getParam('coupon_code'))) {
            $quote = $observer->getEvent()->getQuote();
            if ($code != $quote->getCouponCode()) {
                $codes = Mage::getSingleton('giftvoucher/session')->getCodes();
                $codes[] = $code;
                $codes = array_unique($codes);
                Mage::getSingleton('giftvoucher/session')->setCodes($codes);
            }
        }
    }

    public function collectTotalsBefore($observer) {
        $session = Mage::getSingleton('checkout/session');
        $quote = $observer->getEvent()->getQuote();
        if ($quote->getCouponCode() && !Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($session->getUseGiftCreditAmount() > 0 || $session->getGiftVoucherDiscount() > 0)) {
            if ($session->getUseGiftCard())
                $session->setUseGiftCard(null)
                        ->setGiftCodes(null)
                        ->setBaseAmountUsed(null)
                        ->setBaseGiftVoucherDiscount(null)
                        ->setGiftVoucherDiscount(null)
                        ->setCodesBaseDiscount(null)
                        ->setCodesDiscount(null)
                        ->setGiftMaxUseAmount(null);
            if ($session->getUseGiftCardCredit())
                $session->setUseGiftCardCredit(null)
                        ->setMaxCreditUsed(null)
                        ->setBaseUseGiftCreditAmount(null)
                        ->setUseGiftCreditAmount(null);

            $session->setMessageApplyGiftcardWithCouponCode(true);
        }

        if ($codes = $session->getGiftCodes()) {
            $codesArray = array_unique(explode(',', $codes));
            foreach ($codesArray as $key => $value)
                $codesArray[$key] = 0;
            $session->setBaseAmountUsed(implode(',', $codesArray));
        } else {
            $session->setBaseAmountUsed(null);
        }
        $session->setBaseGiftVoucherDiscount(0);
        $session->setGiftVoucherDiscount(0);
        $session->setUseGiftCreditAmount(0);

        foreach ($quote->getAllAddresses() as $address) {

            $address->setGiftcardCreditAmount(0);
            $address->setBaseUseGiftCreditAmount(0);
            $address->setUseGiftCreditAmount(0);

            $address->setBaseGiftVoucherDiscount(0);
            $address->setGiftVoucherDiscount(0);

            $address->setGiftvoucherBaseHiddenTaxAmount(0);
            $address->setGiftvoucherHiddenTaxAmount(0);

            $address->setMagestoreBaseDiscount(0);
            $address->setMagestoreBaseDiscountForShipping(0);

            foreach ($address->getAllItems() as $item) {
                if ($item->getParentItemId())
                    continue;
                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $child->setBaseGiftVoucherDiscount(0)
                                ->setGiftVoucherDiscount(0)
                                ->setBaseUseGiftCreditAmount(0)
                                ->setMagestoreBaseDiscount(0)
                                ->setUseGiftCreditAmount(0);
                    }
                } elseif ($item->getProduct()) {
                    $item->setBaseGiftVoucherDiscount(0)
                            ->setGiftVoucherDiscount(0)
                            ->setBaseUseGiftCreditAmount(0)
                            ->setMagestoreBaseDiscount(0)
                            ->setUseGiftCreditAmount(0);
                }
            }
        }
    }

    public function orderPlaceBefore($observer) {
        $session = Mage::getSingleton('checkout/session');
        if ($codes = $session->getGiftCodes()) {
            $codesArray = explode(',', $codes);
            $baseSessionAmountUsed = explode(',', $session->getBaseAmountUsed());
            $baseAmountUsed = array_combine($codesArray, $baseSessionAmountUsed);

            foreach ($baseAmountUsed as $code => $amount) {
                $model = Mage::getModel('giftvoucher/giftvoucher')->loadByCode(strval($code));
                if (!$model || $model->getStatus() != Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE || round($model->getBaseBalance(), 10) < round($amount, 10)) {
                    Mage::app()->getResponse()
                            ->setHeader('HTTP/1.1', '403 Session Expired')
                            ->setHeader('Login-Required', 'true')
                            ->sendResponse();
                    exit;
                }
            }
        }
    }

    public function productAddAfter($observer) {
        if (!Mage::helper('magenotification')->checkLicenseKey('Giftvoucher')) {
            return;
        }
        $event = $observer->getEvent();
        $product = $event->getProduct();
        if ($product->getTypeId() == 'giftvoucher') {
            Mage::getSingleton('customer/session')->setGiftcardCustomUploadImage('');
        }
    }

    public function orderPlaceAfter($observer) {
        if (!Mage::helper('magenotification')->checkLicenseKey('Giftvoucher')) {
            return;
        }
        $order = $observer->getEvent()->getOrder();
        $this->_addGiftVoucherForOrder($order);
        $session = Mage::getSingleton('checkout/session');
        $admin_session = Mage::getSingleton('adminhtml/session_quote');

        if (Mage::app()->getStore()->isAdmin())
            $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        else
            $store = Mage::app()->getStore();

        if ($session->getMessageApplyGiftcardWithCouponCode()) {
            $session->setMessageApplyGiftcardWithCouponCode(false);
            Mage::throwException(Mage::helper('giftvoucher')->__('A coupon code has been used. You cannot apply gift codes or Gift Card credit with the coupon to get discount.'));
        }

        if ($admin_session->getQuote()->getCouponCode() && !Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($session->getUseGiftCreditAmount() > 0 || $session->getGiftVoucherDiscount() > 0)) {
            if ($session->getUseGiftCard())
                $session->setUseGiftCard(null)
                        ->setGiftCodes(null)
                        ->setBaseAmountUsed(null)
                        ->setBaseGiftVoucherDiscount(null)
                        ->setGiftVoucherDiscount(null)
                        ->setCodesBaseDiscount(null)
                        ->setCodesDiscount(null)
                        ->setGiftMaxUseAmount(null);
            if ($session->getUseGiftCardCredit())
                $session->setUseGiftCardCredit(null)
                        ->setMaxCreditUsed(null)
                        ->setBaseUseGiftCreditAmount(null)
                        ->setUseGiftCreditAmount(null);

            Mage::throwException(Mage::helper('giftvoucher')->__('A coupon code has been used. You cannot apply gift codes or Gift Card credit with the coupon to get discount.'));
        }

        if (!$session->getUseGiftCard() && !($session->getUseGiftCardCredit())) {
            return;
        }
        if ($codes = $order->getGiftCodes()) {

            $order->setGiftvoucherForOrderCodes($codes)
                    ->setGiftvoucherForOrderAmount($order->getGiftVoucherDiscount());

            $codesArray = explode(',', $codes);
            $codesBaseDiscount = explode(',', $order->getCodesBaseDiscount());
            $codesDiscount = explode(',', $order->getCodesDiscount());

            $baseDiscount = array_combine($codesArray, $codesBaseDiscount);
            $discount = array_combine($codesArray, $codesDiscount);
            foreach ($codesArray as $code) {
                if (!$baseDiscount[$code] || Mage::app()->getStore()->roundPrice($baseDiscount[$code]) == 0)
                    continue;
                $giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);

                $baseCurrencyCode = $order->getBaseCurrencyCode();
                $baseCurrency = Mage::getModel('directory/currency')->load($baseCurrencyCode);
                $currentCurrency = Mage::getModel('directory/currency')->load($giftVoucher->getData('currency'));

                $codeDiscount = Mage::helper('directory')->currencyConvert($baseDiscount[$code], $baseCurrencyCode, $giftVoucher->getData('currency'));
                $codeCurrentDiscount = Mage::helper('directory')->currencyConvert($baseDiscount[$code], $baseCurrencyCode, $store->getCurrentCurrencyCode());
                $balance = $giftVoucher->getBalance() - $codeDiscount;
                $baseBalance = $balance * $balance / $baseCurrency->convert($balance, $currentCurrency);
                $currentBalance = Mage::helper('directory')->currencyConvert($baseBalance, $baseCurrencyCode, $store->getCurrentCurrencyCode());

                $giftVoucher->setData('balance', $balance)->save();
                $history = Mage::getModel('giftvoucher/history')->setData(array(
                            'order_increment_id' => $order->getIncrementId(),
                            'giftvoucher_id' => $giftVoucher->getId(),
                            'created_at' => now(),
                            'action' => Magestore_Giftvoucher_Model_Actions::ACTIONS_SPEND_ORDER,
                            'amount' => $codeCurrentDiscount,
                            'balance' => $currentBalance,
                            'currency' => $store->getCurrentCurrencyCode(),
                            'status' => $giftVoucher->getStatus(),
                            'order_amount' => $discount[$code],
                            'comments' => Mage::helper('giftvoucher')->__('Spent on order %s', $order->getIncrementId()),
                            'extra_content' => Mage::helper('giftvoucher')->__('Used by %s %s', $order->getData('customer_firstname'), $order->getData('customer_lastname')),
                            'customer_id' => $order->getData('customer_id'),
                            'customer_email' => $order->getData('customer_email')
                        ))->save();

                // add gift code to customer list
                if ($order->getCustomerId()) {
                    $collection = Mage::getResourceModel('giftvoucher/customervoucher_collection')
                            ->addFieldToFilter('customer_id', $order->getCustomerId())
                            ->addFieldToFilter('voucher_id', $giftVoucher->getId());
                    if (!$collection->getSize()) {
                        try {
                            $timeSite = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

                            Mage::getModel('giftvoucher/customervoucher')
                                    ->setCustomerId($order->getCustomerId())
                                    ->setVoucherId($giftVoucher->getId())
                                    ->setAddedDate($timeSite)
                                    ->save();
                        } catch (Exception $e) {
                            
                        }
                    }
                }
            }
        }

        if ($order->getGiftcardCreditAmount() && $order->getCustomerId()) {
            $credit = Mage::getModel('giftvoucher/credit')->load($order->getCustomerId(), 'customer_id');
            if ($credit->getId()) {
                try {
                    $credit->setBalance($credit->getBalance() - $order->getGiftcardCreditAmount());
                    $credit->save();
                    if ($store->getCurrentCurrencyCode() != $order->getBaseCurrencyCode()) {
                        $currency_balance = $store->convertPrice(round($credit->getBalance(), 4));
                    } else {
                        $currency_balance = round($credit->getBalance(), 4);
                    }

                    $credithistory = Mage::getModel('giftvoucher/credithistory')->setData($credit->getData());
                    $credithistory->addData(array(
                        'action' => 'Spend',
                        'currency_balance' => $currency_balance,
                        'order_id' => $order->getId(),
                        'order_number' => $order->getIncrementId(),
                        'balance_change' => $order->getUseGiftCreditAmount(),
                        'created_date' => date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                        'currency' => $store->getCurrentCurrencyCode(),
                        'base_amount' => $order->getBaseUseGiftCreditAmount(),
                        'amount' => $order->getUseGiftCreditAmount()
                    ))->setId(null)->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }

        // Create invoice for Order payed by Giftvoucher
        if (Mage::app()->getStore()->roundPrice($order->getGrandTotal()) == 0 && $order->getPayment()->getMethod() == 'free' && $order->canInvoice()) {
            try {
                $itemQtys = array();
                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($itemQtys);
                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                $invoice->register();

                $invoice->getOrder()->setIsInProcess(true);
                $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder())
                        ->save();
            } catch (Exception $e) {
                
            }
        }

        if ($session->getUseGiftCard())
            $session->setUseGiftCard(null)
                    ->setGiftCodes(null)
                    ->setBaseAmountUsed(null)
                    ->setBaseGiftVoucherDiscount(null)
                    ->setGiftVoucherDiscount(null)
                    ->setCodesBaseDiscount(null)
                    ->setCodesDiscount(null)
                    ->setGiftMaxUseAmount(null);
        if ($session->getUseGiftCardCredit()) {
            $session->setUseGiftCardCredit(null)
                    ->setMaxCreditUsed(null)
                    ->setBaseUseGiftCreditAmount(null)
                    ->setUseGiftCreditAmount(null);
        }
    }

    protected function _loadOrderData($order) {
        $giftVouchers = Mage::getModel('giftvoucher/history')->getCollection()->joinGiftVoucher()
                ->addFieldToFilter('main_table.order_increment_id', $order->getIncrementId());
        $codesArray = array();
        $baseDiscount = 0;
        $discount = 0;
        foreach ($giftVouchers as $giftVoucher) {
            $codesArray[] = $giftVoucher->getGiftCode();
            $baseDiscount += $giftVoucher->getAmount();
            $discount += $giftVoucher->getOrderAmount();
        }
        if ($baseDiscount) {
            $baseCurrency = Mage::getModel('directory/currency')->load($order->getBaseCurrencyCode());
            $currentCurrency = Mage::getModel('directory/currency')->load($order->getOrderCurrencyCode());
            $baseDiscount = $baseDiscount * $baseDiscount / $baseCurrency->convert($baseDiscount, $currentCurrency);

            $order->setGiftCodes(implode(',', $codesArray));
            $order->setBaseGiftVoucherDiscount($baseDiscount);
            $order->setGiftVoucherDiscount($discount);
        }
        $creditHistory = Mage::getResourceModel('giftvoucher/credithistory_collection')
                ->addFieldToFilter('action', 'Spend')
                ->addFieldToFilter('order_id', $order->getId())
                ->getFirstItem();
        if ($creditHistory && $creditHistory->getId()) {
            $order->setGiftcardCreditAmount($creditHistory->getBalanceChange());
            $order->setBaseUseGiftCreditAmount($creditHistory->getBaseAmount());
            $order->setUseGiftCreditAmount($creditHistory->getAmount());
        }
        return $this;
    }

    public function paypalPrepareItems($observer) {
        $session = Mage::getSingleton('checkout/session');
        if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
            $paypalCart = $observer->getEvent()->getPaypalCart();
            if ($paypalCart) {
                $salesEntity = $paypalCart->getSalesEntity();

                // Vick - fix for Paypal Express
                $giftCardDiscount = $salesEntity->getGiftVoucherDiscount();
                if (!$giftCardDiscount)
                    $giftCardDiscount = $session->getGiftVoucherDiscount();
                if ($giftCardDiscount)
                    $paypalCart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_DISCOUNT, abs((float) $giftCardDiscount), Mage::helper('giftvoucher')->__('Gift Card Discount'));

                $giftCardCreditAmount = $salesEntity->getUseGiftCreditAmount();
                if (!$giftCardCreditAmount)
                    $giftCardCreditAmount = $session->getUseGiftCreditAmount();
                if ($giftCardCreditAmount && Mage::helper('giftvoucher')->getGeneralConfig('enablecredit'))
                    $paypalCart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_DISCOUNT, abs((float) $giftCardCreditAmount), Mage::helper('giftvoucher')->__('Gift Card Credit'));
            }
        }else {
            $salesEntity = $observer->getSalesEntity();
            $additional = $observer->getAdditional();
            if ($salesEntity && $additional) {
                $items = $additional->getItems();
                $items[] = new Varien_Object(array(
                    'name' => Mage::helper('giftvoucher')->__('Gift Card Discount'),
                    'qty' => 1,
                    'amount' => -(abs((float) $salesEntity->getGiftVoucherDiscount())),
                ));
                if (Mage::helper('giftvoucher')->getGeneralConfig('enablecredit')) {
                    $items[] = new Varien_Object(array(
                        'name' => Mage::helper('giftvoucher')->__('Gift Card Credit'),
                        'qty' => 1,
                        'amount' => - (abs((float) $salesEntity->getUseGiftCreditAmount())),
                    ));
                }
                $additional->setItems($items);
            }
        }
    }

    public function orderLoadAfter($observer) {
        $order = $observer->getEvent()->getOrder();
        $this->_loadOrderData($order);

        if ((abs($order->getGiftVoucherDiscount()) < 0.0001 && abs($order->getUseGiftCreditAmount()) < 0.0001) || $order->getState() === Mage_Sales_Model_Order::STATE_CLOSED || $order->isCanceled() || $order->canUnhold()) {
            return $this;
        }
        foreach ($order->getAllItems() as $item) {
            if (($item->getQtyInvoiced() - $item->getQtyRefunded() - $item->getQtyCanceled()) > 0) {
                $order->setForcedCanCreditmemo(true);
                return $this;
            }
        }
    }

    public function creditmemoRegisterBefore($observer) {
        $request = $observer->getEvent()->getRequest();
        $input = $request->getParam('creditmemo');
        $controller = $request->getRequestedRouteName() . '_' .
                $request->getRequestedControllerName() . '_' .
                $request->getRequestedActionName();

        $creditmemo = $observer['creditmemo'];
        $order = $creditmemo->getOrder();

        if (($order->getGiftVoucherDiscount() > 0 || $order->getUseGiftCreditAmount() > 0) && Mage::app()->getStore()->roundPrice($creditmemo->getGrandTotal()) <= 0) {
            $creditmemo->setAllowZeroGrandTotal(true);
        }

        if (isset($input['giftcard_refund'])) {
            $refund = $input['giftcard_refund'];
            if ($refund < 0)
                return $this;

            $creditmemo = $observer->getEvent()->getCreditmemo();
            $maxAmount = 0;
            if ($creditmemo->getUseGiftCreditAmount() && Mage::helper('giftvoucher')->getGeneralConfig('enablecredit', $creditmemo->getStoreId())) {
                $maxAmount += floatval($creditmemo->getUseGiftCreditAmount());
            }
            if ($creditmemo->getGiftVoucherDiscount()) {
                $maxAmount += floatval($creditmemo->getGiftVoucherDiscount());
            }

            if ($controller == 'adminhtml_sales_order_creditmemo_updateQty')
                $creditmemo->setGiftcardRefundAmount($maxAmount);
            else
                $creditmemo->setGiftcardRefundAmount(min(floatval($refund), $maxAmount));
        }
    }

    public function invoiceSaveAfter($observer) {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $order = Mage::getModel('sales/order')->load($order->getId());

        foreach ($invoice->getAllItems() as $itemCredit) {
            $item = $order->getItemById($itemCredit->getOrderItemId());
            if (isset($item) && $item != null) {
                if ($item->getProductType() != 'giftvoucher')
                    continue;
                $giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());
                $itemQtyInvoice = $itemCredit->getQty();
                foreach ($giftVouchers as $giftVoucher) {
                    if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_PENDING) {
                        $giftVoucher->addData(array(
                            'status' => Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE,
                            'comments' => Mage::helper('giftvoucher')->__('Active when order is complete'),
                            'amount' => $giftVoucher->getBalance(),
                            'action' => Magestore_Giftvoucher_Model_Actions::ACTIONS_UPDATE,
                        ))->setIncludeHistory(true);
                        try {
                            if ($giftVoucher->getDayToSend() && strtotime($giftVoucher->getDayToSend()) > time()
                            ) {
                                $giftVoucher->setData('dont_send_email_to_recipient', 1);
                            }
                            if (!empty($buyRequest['recipient_ship'])) {
                                $giftVoucher->setData('is_sent', 2);
                                if (!Mage::helper('giftvoucher')->getEmailConfig('send_with_ship', $order->getStoreId())) {
                                    $giftVoucher->setData('dont_send_email_to_recipient', 1);
                                }
                            }
                            $giftVoucher->save();
                            if (Mage::helper('giftvoucher')->getEmailConfig('enable', $order->getStoreId())) {
                                //Hai.Tran
                                $giftVoucher->setIncludeHistory(false);
                                if ($giftVoucher->getRecipientEmail()) {
                                    if ($giftVoucher->sendEmailToRecipient() && $giftVoucher->getNotifySuccess())
                                        $giftVoucher->sendEmailSuccess();
                                } else
                                    $giftVoucher->sendEmail();
                            }
                        } catch (Exception $e) {
                            
                        }
                        $itemQtyInvoice -= 1;
                        if (!$itemQtyInvoice)
                            break;
                    }
                }
            }
        }
    }

    public function creditmemoSaveAfter($observer) {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $baseGrandTotal = $creditmemo->getBaseGrandTotal();
        $order = $creditmemo->getOrder();
        $order = Mage::getModel('sales/order')->load($order->getId());

        foreach ($creditmemo->getAllItems() as $itemCredit) {
            $item = $order->getItemById($itemCredit->getOrderItemId());
            if (isset($item) && $item != null) {
                if ($item->getProductType() != 'giftvoucher')
                    continue;
                $giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());
                $product_options = $item->getProductOptions();
                $cantRefundGiftvoucherProduct = $item->getQtyInvoiced() - $item->getQtyRefunded();
                foreach ($giftVouchers as $giftVoucher) {
                    $giftVoucher->setCanRefund(true);
                    if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBalance() < $product_options['info_buyRequest']['amount']) {
                        $cantRefundGiftvoucherProduct -= 1;
                        $giftVoucher->setCanRefund(false);
                    }
                }
                if ($cantRefundGiftvoucherProduct < 0) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('There is atleast one of products which is giftvoucher and being used.'));
                    throw new Exception(
                    Mage::helper('giftvoucher')->__('There is atleast one of products which is giftvoucher and being used.')
                    );
                }

                $itemQtyRefund = $itemCredit->getQty();
                foreach ($giftVouchers as $giftVoucher) {
                    //                if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_PENDING || $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE) {
                    if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getCanRefund()) {
                        $itemQtyRefund -= 1;
                        $giftVoucher->addData(array(
                            'status' => Magestore_Giftvoucher_Model_Status::STATUS_DISABLED,
                            'comments' => Mage::helper('giftvoucher')->__('Refund order %s', $order->getIncrementId()),
                            'amount' => $giftVoucher->getBalance(),
                            'action' => Magestore_Giftvoucher_Model_Actions::ACTIONS_REFUND,
                            'order_increment_id' => $order->getIncrementId(),
                            'currency' => $giftVoucher->getCurrency(),
                        ))->setIncludeHistory(true);
                        try {
                            $giftVoucher->save();
                            if ($giftVoucher->getData('is_sent') && Mage::helper('giftvoucher')->getEmailConfig('send_refund', $order->getStoreId()))
                                $giftVoucher->sendEmailRefundToRecipient();
                        } catch (Exception $e) {
                            
                        }
                        if (!$itemQtyRefund)
                            break;
                    }
                }
            }
        }
        // manual save in Backend
        if (Mage::app()->getStore()->isAdmin() && $creditmemo->getGiftcardRefundAmount()) {
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
            if ($customer->getId() && Mage::helper('giftvoucher')->getGeneralConfig('enablecredit', $creditmemo->getStoreId())) {
                $credit = Mage::getModel('giftvoucher/credit')->load($customer->getId(), 'customer_id');
                if (!$credit->getId()) {
                    $credit->setCustomerId($customer->getId())
                            ->setCurrency($order->getBaseCurrencyCode())
                            ->setBalance(0);
                }
                $refundAmount = 0;
                $baseCurrency = Mage::app()->getStore($order->getStoreId())->getBaseCurrency();
                if ($rate = $baseCurrency->getRate($order->getOrderCurrencyCode())) {
                    $refundAmount = $creditmemo->getGiftcardRefundAmount() / $rate;
                }
                if ($refundAmount && $baseCurrency->getRate($credit->getCurrency())) {
                    $creditBalance = $refundAmount * $baseCurrency->getRate($credit->getCurrency());
                    try {
                        $credit->setBalance($credit->getBalance() + $creditBalance)
                                ->save();

                        if ($order->getOrderCurrencyCode() != $order->getBaseCurrencyCode()) {
                            $baseCurrency = Mage::getModel('directory/currency')->load($order->getBaseCurrencyCode());
                            $currentCurrency = Mage::getModel('directory/currency')->load($order->getOrderCurrencyCode());
                            $currency_balance = $baseCurrency->convert(round($credit->getBalance(), 4), $currentCurrency);
                        } else {
                            $currency_balance = round($credit->getBalance(), 4);
                        }

                        $credithistory = Mage::getModel('giftvoucher/credithistory')->setData($credit->getData());
                        $credithistory->addData(array(
                            'action' => 'Refund',
                            'currency_balance' => $currency_balance,
                            'order_id' => $order->getId(),
                            'order_number' => $order->getIncrementId(),
                            'balance_change' => $creditmemo->getGiftcardRefundAmount(),
                            'created_date' => date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                            'currency' => $order->getOrderCurrencyCode(),
                            'base_amount' => $refundAmount,
                            'amount' => $creditmemo->getGiftcardRefundAmount()
                        ))->setId(null)->save();
                    } catch (Exception $e) {
                        Mage::logException($e);
                    }
                }
            } else {
                $refundAmount = 0;
                $baseCurrency = Mage::app()->getStore($order->getStoreId())->getBaseCurrency();
                if ($rate = $baseCurrency->getRate($order->getOrderCurrencyCode())) {
                    $refundAmount = $creditmemo->getGiftcardRefundAmount() / $rate;
                }
                if ($refundAmount) {
                    $this->_refundOffline($order, $refundAmount);
                }
            }
            return $this;
        }
        // online save in frontend
        if (!Mage::app()->getStore()->isAdmin() && Mage::helper('giftvoucher')->getGeneralConfig('online_refund')) {
            if ($creditmemo->getBaseGiftVoucherDiscount()) {
                $maxAmount = floatval($creditmemo->getBaseGiftVoucherDiscount());
                $this->_refundOffline($order, $maxAmount);
            }
        }
        // refund for Giftvoucher payment method
        if ($order->getPayment()->getMethod() == 'giftvoucher') {
            $this->_refundOffline($order, $baseGrandTotal);
        }
    }

    protected function _refundOffline($order, $baseGrandTotal) {
        if (Mage::app()->getStore()->isAdmin())
            $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        else
            $store = Mage::app()->getStore();

        if ($codes = $order->getGiftCodes()) {
            $codesArray = explode(',', $codes);
            foreach ($codesArray as $code) {
                if (Mage::app()->getStore()->roundPrice($baseGrandTotal) == 0)
                    return $this;

                $giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
                $history = Mage::getModel('giftvoucher/history');

                $availableDiscount = $history->getTotalSpent($giftVoucher, $order) - $history->getTotalRefund($giftVoucher, $order);
                if (Mage::app()->getStore()->roundPrice($availableDiscount) == 0)
                    continue;

                if ($availableDiscount < $baseGrandTotal) {
                    $baseGrandTotal = $baseGrandTotal - $availableDiscount;
                } else {
                    $availableDiscount = $baseGrandTotal;
                    $baseGrandTotal = 0;
                }
                $baseCurrencyCode = $order->getBaseCurrencyCode();
                $baseCurrency = Mage::getModel('directory/currency')->load($baseCurrencyCode);
                $currentCurrency = Mage::getModel('directory/currency')->load($giftVoucher->getData('currency'));

                $discountRefund = Mage::helper('directory')->currencyConvert($availableDiscount, $baseCurrencyCode, $giftVoucher->getData('currency'));
                $discountCurrentRefund = Mage::helper('directory')->currencyConvert($availableDiscount, $baseCurrencyCode, $order->getOrderCurrencyCode());

                $balance = $giftVoucher->getBalance() + $discountRefund;
                $baseBalance = $balance * $balance / $baseCurrency->convert($balance, $currentCurrency);
                $currentBalance = Mage::helper('directory')->currencyConvert($baseBalance, $baseCurrencyCode, $order->getOrderCurrencyCode());

                if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_USED)
                    $giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE);
                $giftVoucher->setData('balance', $balance)->save();

                $history->setData(array(
                    'order_increment_id' => $order->getIncrementId(),
                    'giftvoucher_id' => $giftVoucher->getId(),
                    'created_at' => now(),
                    'action' => Magestore_Giftvoucher_Model_Actions::ACTIONS_REFUND,
                    'amount' => $discountCurrentRefund,
                    'balance' => $currentBalance,
                    'currency' => $order->getOrderCurrencyCode(),
                    'status' => $giftVoucher->getStatus(),
                    'comments' => Mage::helper('giftvoucher')->__('Refund from order %s', $order->getIncrementId()),
                    'customer_id' => $order->getData('customer_id'),
                    'customer_email' => $order->getData('customer_email'),
                ))->save();
            }
        }
        if ($order->getGiftcardCreditAmount() && $order->getCustomerId() && Mage::helper('giftvoucher')->getGeneralConfig('enablecredit', $order->getStoreId())) {
            $credit = Mage::getModel('giftvoucher/credit')->load($order->getCustomerId(), 'customer_id');
            if ($credit->getId()) {
                // check order is refunded to credit balance
                $histories = Mage::getResourceModel('giftvoucher/credithistory_collection')
                        ->addFieldToFilter('customer_id', $order->getCustomerId())
                        ->addFieldToFilter('action', 'Refund')
                        ->addFieldToFilter('order_id', $order->getId())
                        ->getFirstItem();
                if ($histories && $histories->getId()) {
                    return $this;
                }
                try {
                    $credit->setBalance($credit->getBalance() + $order->getGiftcardCreditAmount());
                    $credit->save();
                    if ($store->getCurrentCurrencyCode() != $order->getBaseCurrencyCode()) {
                        $baseCurrency = Mage::getModel('directory/currency')->load($order->getBaseCurrencyCode());
                        $currentCurrency = Mage::getModel('directory/currency')->load($order->getOrderCurrencyCode());
                        $currency_balance = $baseCurrency->convert(round($credit->getBalance(), 4), $currentCurrency);
                    } else {
                        $currency_balance = round($credit->getBalance(), 4);
                    }
                    $credithistory = Mage::getModel('giftvoucher/credithistory')->setData($credit->getData());
                    $credithistory->addData(array(
                        'action' => 'Refund',
                        'currency_balance' => $currency_balance,
                        'order_id' => $order->getId(),
                        'order_number' => $order->getIncrementId(),
                        'balance_change' => $order->getUseGiftCreditAmount(),
                        'created_date' => date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                        'currency' => $store->getCurrentCurrencyCode(),
                        'base_amount' => $order->getBaseUseGiftCreditAmount(),
                        'amount' => $order->getUseGiftCreditAmount()
                    ))->setId(null)->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }

    public function orderSaveAfter($observer) {
        $order = $observer->getEvent()->getOrder();
        if ($order->getStatus() == Mage_Sales_Model_Order::STATE_COMPLETE) {
            $this->_addGiftVoucherForOrder($order);
        }

        $refundState = array('canceled');
        if (in_array($order->getStatus(), $refundState)) {
            $this->_refundOffline($order, $order->getBaseGiftVoucherDiscount());
        }
    }

    protected function _addGiftVoucherForOrder($order) {
        $router = Mage::app()->getRequest()->getRouteName();
        if (Mage::app()->getStore()->isAdmin())
            $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        else
            $store = Mage::app()->getStore();

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() != 'giftvoucher')
                continue;

            $options = $item->getProductOptions();

            $buyRequest = $options['info_buyRequest'];

            $quoteItemOptions = Mage::getModel('sales/quote_item_option')->getCollection()->addFieldToFilter('item_id', array('eq' => $item->getQuoteItemId()));
            if (isset($buyRequest['amount']) && $quoteItemOptions) {
                foreach ($quoteItemOptions as $quoteItemOption) {
                    if ($quoteItemOption->getCode() == 'amount') {
                        $buyRequest['amount'] = $store->roundPrice($quoteItemOption->getValue());
                        $options['info_buyRequest'] = $buyRequest;
                        $item->setProductOptions($options);
                    }
                }
            }
            $giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());

            $time = time();
            for ($i = 0; $i < $item->getQtyOrdered() - $giftVouchers->getSize(); $i++) {
                $giftVoucher = Mage::getModel('giftvoucher/giftvoucher');

                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                if (isset($buyRequest['amount'])) {
                    $amount = $buyRequest['amount'];
                } else {
                    $amount = $item->getPrice();
                }

                $giftVoucher->setBalance($amount)->setAmount($amount);
                $giftVoucher->setOrderAmount($item->getBasePrice());

                $giftProduct = Mage::getModel('giftvoucher/product')->loadByProduct($product);
                $giftVoucher->setDescription($giftProduct->getGiftcardDescription());
                if ($giftProduct->getId()) {
                    $conditionsArr = unserialize($giftProduct->getConditionsSerialized());
                    $actionsArr = unserialize($giftProduct->getActionsSerialized());
                    if (!empty($conditionsArr) && is_array($conditionsArr)) {
                        $giftVoucher->getConditions()->loadArray($conditionsArr);
                    }
                    if (!empty($actionsArr) && is_array($actionsArr)) {
                        $giftVoucher->getActions()->loadArray($actionsArr);
                    }
                }
                //Hai.Tran
                if (isset($buyRequest['customer_name']))
                    $giftVoucher->setCustomerName($buyRequest['customer_name']);
                if (isset($buyRequest['giftcard_template_id']) && $buyRequest['giftcard_template_id'])
                    $giftVoucher->setGiftcardTemplateId($buyRequest['giftcard_template_id']);
                if (isset($buyRequest['recipient_name']))
                    $giftVoucher->setRecipientName($buyRequest['recipient_name']);
                if (isset($buyRequest['recipient_email']))
                    $giftVoucher->setRecipientEmail($buyRequest['recipient_email']);
                if (isset($buyRequest['message']))
                    $giftVoucher->setMessage($buyRequest['message']);
                if (isset($buyRequest['notify_success']))
                    $giftVoucher->setNotifySuccess($buyRequest['notify_success']);
                if (isset($buyRequest['day_to_send']) && $buyRequest['day_to_send'])
                    $giftVoucher->setDayToSend(date('Y-m-d', strtotime($buyRequest['day_to_send'])));

                //                                        time zone 
                if (isset($buyRequest['timezone_to_send']) && $buyRequest['timezone_to_send']) {

                    $giftVoucher->setTimezoneToSend($buyRequest['timezone_to_send']);
                    $customerZone = new DateTimeZone($giftVoucher->getTimezoneToSend());
                    $date = new DateTime($giftVoucher->getDayToSend(), $customerZone);
                    $serverTimezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
                    $date->setTimezone(new DateTimeZone($serverTimezone));
                    $giftVoucher->setDayStore($date->format('Y-m-d'));
                }
                //                end timezone

                if (isset($buyRequest['giftcard_template_image']) && $buyRequest['giftcard_template_image']) {
                    if (isset($buyRequest['giftcard_use_custom_image']) && $buyRequest['giftcard_use_custom_image']) {
                        $dir = Mage::getBaseDir('media') . DS . 'tmp' . DS . 'giftvoucher' . DS . 'images' . DS . $buyRequest['giftcard_template_image'];
                        if (file_exists($dir)) {
                            $imageObj = new Varien_Image($dir);
                            $image_path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'giftvoucher/template/images/';
                            $customer_upload_image = $time . $buyRequest['giftcard_template_image'];
                            $dir_customer_upload = Mage::getBaseDir() . str_replace("/", DS, strstr($image_path, '/media')) . $customer_upload_image;
                            if (!file_exists($dir_customer_upload)) {
                                $imageObj->save($dir_customer_upload);
                                Mage::helper('giftvoucher')->customResizeImage($image_path, $customer_upload_image, 'images');
                            }
                            $giftVoucher->setGiftcardCustomImage(true);
                            $giftVoucher->setGiftcardTemplateImage($customer_upload_image);
                            // unlink($dir);
                        } else {
                            $giftVoucher->setGiftcardTemplateImage('default.png');
                        }
                    } else {
                        $giftVoucher->setGiftcardTemplateImage($buyRequest['giftcard_template_image']);
                    }
                }

                if (isset($buyRequest['recipient_ship']) && $buyRequest['recipient_ship'] != null && $address = $order->getShippingAddress())
                    $giftVoucher->setRecipientAddress($address->getFormated());

                $giftVoucher->setCurrency($store->getCurrentCurrencyCode());

                if ($order->getStatus() == Mage_Sales_Model_Order::STATE_COMPLETE)
                    $giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE);
                else
                    $giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_PENDING);

                if ($timeLife = Mage::helper('giftvoucher')->getGeneralConfig('expire', $order->getStoreId())) {
                    //$currentDate = Mage::getModel('core/date')->gmtDate();
                    $orderTime = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                    $expire = date("Y-m-d H:i:s", strtotime($orderTime . ' +' . $timeLife . ' days'));
                    $giftVoucher->setExpiredAt($expire);
                }

                $giftVoucher->setCustomerId($order->getCustomerId())
                        //->setCustomerName($order->getData('customer_firstname') . ' ' . $order->getData('customer_lastname'))
                        ->setCustomerEmail($order->getCustomerEmail())
                        ->setStoreId($order->getStoreId());
                //Hai.Tran
                if (!$giftVoucher->getCustomerName())
                    $giftVoucher->setCustomerName($order->getData('customer_firstname') . ' ' . $order->getData('customer_lastname'));

                $giftVoucher->setAction(Magestore_Giftvoucher_Model_Actions::ACTIONS_CREATE)
                        ->setComments(Mage::helper('giftvoucher')->__('Created for order %s', $order->getIncrementId()))
                        ->setOrderIncrementId($order->getIncrementId())
                        ->setOrderItemId($item->getId())
                        ->setExtraContent(Mage::helper('giftvoucher')->__('Created by customer %s %s', $order->getData('customer_firstname'), $order->getData('customer_lastname')))
                        ->setIncludeHistory(true);
                try {
                    if ($giftVoucher->getDayToSend() && strtotime($giftVoucher->getDayToSend()) > time()
                    ) {
                        $giftVoucher->setData('dont_send_email_to_recipient', 1);
                    }
                    if (!empty($buyRequest['recipient_ship'])) {
                        $giftVoucher->setData('is_sent', 2);
                        if (!Mage::helper('giftvoucher')->getEmailConfig('send_with_ship', $order->getStoreId())) {
                            $giftVoucher->setData('dont_send_email_to_recipient', 1);
                        }
                    }

                    $giftVoucher->save();
                    if ($order->getCustomerId()) {
                        $timeSite = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                        Mage::getModel('giftvoucher/customervoucher')
                                ->setCustomerId($order->getCustomerId())
                                ->setVoucherId($giftVoucher->getId())
                                ->setAddedDate($timeSite)
                                ->save();
                    }
                } catch (Exception $e) {
                    
                }
            }
        }
        return $this;
    }

    protected function _expireGiftVoucherOfOrder($order) {
        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() != 'giftvoucher')
                continue;

            $giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());
            foreach ($giftVouchers as $giftVoucher) {
                if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_PENDING || $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE) {
                    $giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_DISABLED);
                    try {
                        $giftVoucher->save();
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        return $this;
    }

    public function autoSendMail() {
        if (Mage::helper('giftvoucher')->getEmailConfig('autosend')) {
            $giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()
                    ->addFieldToFilter('status', array('neq' => Magestore_Giftvoucher_Model_Status::STATUS_DELETED))
                    ->addExpireAfterDaysFilter(Mage::helper('giftvoucher')->getEmailConfig('daybefore'));
            foreach ($giftVouchers as $giftVoucher)
                $giftVoucher->sendEmail();
        }
    }

    /**
     * send schedule for friend
     */
    public function sendScheduleEmail() {
        $collection = Mage::getResourceModel('giftvoucher/giftvoucher_collection');
        $timeSite = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
        $collection->addFieldToFilter('is_sent', array('neq' => 1))
                ->addFieldToFilter('day_store', array('notnull' => true))
                ->addFieldToFilter('day_store', array('to' => $timeSite));
        if (count($collection)) {
            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);
            foreach ($collection as $giftCard) {
                $giftCard->save();
                if ($giftCard->sendEmailToRecipient()) {
                    if ($giftCard->getNotifySuccess())
                        $giftCard->sendEmailSuccess();
                }
            }
            $translate->setTranslateInline(true);
        }
    }

    /*
     * redirect when admin edit gift product
     */

    public function adminhtmlCatalogProductNewAfter($observer) {
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() != 'giftvoucher')
            return;
        if (!($stockItem = $product->getStockItem())) {
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->assignProduct($product)
                    ->setData('stock_id', 1)
                    ->setData('store_id', 1);
        }
        $stockItem->setData('manage_stock', 0);
        $stockItem->setData('use_config_manage_stock', 0);
        $stockItem->setData('use_config_min_sale_qty', 1);
        $stockItem->setData('use_config_max_sale_qty', 1);
        $product->getStockItem();
    }

    public function adminhtmlCatalogProductSaveAfter($observer) {
        $action = $observer->getEvent()->getControllerAction();
        $back = $action->getRequest()->getParam('back');
        $session = Mage::getSingleton('giftvoucher/session');
        $giftproductsession = $session->getGiftProductEdit();

        if ($back || !$giftproductsession)
            return $this;

        $type = $action->getRequest()->getParam('type');
        if (!$type) {
            $id = $action->getRequest()->getParam('id');
            $type = Mage::getModel('catalog/product')->load($id)->getTypeId();
        }
        if (!$type)
            return $this;

        $reponse = Mage::app()->getResponse();
        $url = Mage::getModel('adminhtml/url')->getUrl("giftvoucheradmin/adminhtml_giftproduct/index");
        $reponse->setRedirect($url);
        $reponse->sendResponse();
        $session->unsetData('gift_product_edit');
        return $this;
    }

    public function giftcardPaymentMethod($observer) {
        $store_Id = Mage::app()->getStore()->getId();
        if (Mage::helper('giftvoucher')->getGeneralConfig('active', $store_Id)) {
            $block = $observer['block'];
            /* Display Gift Card From in the Checkout page */
            if (($block instanceof Mage_Checkout_Block_Onepage_Payment_Methods)) {
                $requestPath = $block->getRequest()->getRequestedRouteName()
                        . '_' . $block->getRequest()->getRequestedControllerName()
                        . '_' . $block->getRequest()->getRequestedActionName();
                if ($requestPath == 'checkout_onepage_index') {
                    return;
                }

                $transport = $observer['transport'];

                $html_addgiftcard = $block->getLayout()->createBlock('giftvoucher/payment_form')->renderView();
                $html = $transport->getHtml();
                //Hai.Tran
                if (version_compare(Mage::getVersion(), '1.8.0', '>=') && Mage::app()->getRequest()->getRouteName() != 'onestepcheckout') {
                    $html = '<dl class="sp-methods" id="checkout-payment-method-load">' . $html . '</dl>';
                }
                $html .= '<script type="text/javascript">checkOutLoadGiftCard(' . Mage::helper('core')->jsonEncode(array('html' => $html_addgiftcard)) . ');onLoadGiftvoucherForm();</script>';
                $transport->setHtml($html);
            }

            /* Show Gift Card Form in the Cart page */
            if (($block instanceof Magestore_RewardPoints_Block_Checkout_Cart_Rewrite_Coupon) || ($block instanceof Mage_Checkout_Block_Cart_Coupon )) {
                $requestPath = $block->getRequest()->getRequestedRouteName()
                        . '_' . $block->getRequest()->getRequestedControllerName()
                        . '_' . $block->getRequest()->getRequestedActionName();

                if ($requestPath == 'checkout_onepage_index' || strpos($requestPath, 'checkout_cart') === false) {
                    return;
                }
                $transport = $observer['transport'];
                $html = $transport->getHtml();
                $html_addgiftcardform = $block->getLayout()->createBlock('giftvoucher/cart_giftcard')->renderView();
                $html .= $html_addgiftcardform;

                $transport->setHtml($html);
            }

            /* Show or hide the "check Gift Card" link */
            if (($block instanceof Mage_Page_Block_Template_Links) && Mage::helper('giftvoucher')->getGeneralConfig('gc_show_check_link')) {
                if (!Mage::registry('show_check_giftcard_link')) {
                    Mage::register('show_check_giftcard_link', 1);
                    $top_links = $block->getLayout()->getBlock('top.links');
                    if (isset($top_links) && $top_links != null)
                        $top_links->addLink('Check Gift Card', Mage::helper('giftvoucher')->getCheckGiftCardUrl(), 'Check Gift Card', false, null, 10);
                }
            }
        }
    }

    /**
     * clear admin checkout session
     * 
     * @param type $observer
     */
    public function clearAdminCheckoutSession($observer) {
        Mage::getSingleton('checkout/session')
                ->setUseGiftCard(null)
                ->setGiftCodes(null)
                ->setBaseAmountUsed(null)
                ->setBaseGiftVoucherDiscount(null)
                ->setGiftVoucherDiscount(null)
                ->setCodesBaseDiscount(null)
                ->setCodesDiscount(null)
                ->setGiftMaxUseAmount(null)
                ->setUseGiftCardCredit(null)
                ->setMaxCreditUsed(null)
                ->setBaseUseGiftCreditAmount(null)
                ->setUseGiftCreditAmount(null);
    }

    public function updateShippedGiftCard($observer) {
        $shipmentItem = $observer->getEvent()->getShipmentItem();
        $orderItemId = $shipmentItem->getOrderItemId();

        $giftVouchers = Mage::getResourceModel('giftvoucher/giftvoucher_collection')->addItemFilter($orderItemId);
        foreach ($giftVouchers as $giftCard) {
            if ($giftCard->getShippedToCustomer() || !Mage::getStoreConfig('giftvoucher/general/auto_shipping', $giftCard->getStoreId())
            ) {
                return;
            }
            try {
                $giftCard->setShippedToCustomer(1)
                        ->save();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    public function customerSaveAfter($observer) {
        // if (!Mage::helper('giftvoucher')->getGeneralConfig('enablecredit'))
        //     return $this;
        $customer = $observer->getEvent()->getCustomer();
        if (!$customer->getId())
            return $this;
        $balance = Mage::app()->getRequest()->getParam('change_balance');
        if (!$balance)
            return $this;

        $credit = Mage::getModel('giftvoucher/credit')->getCreditByCustomerId($customer->getId());

        if (!$credit->getCurrency()) {
            $currency = Mage::app()->getStore()->getDefaultCurrencyCode();
            $credit->setCurrency($currency);
            $credit->setCustomerId($customer->getId());
        }
        $credit->setBalance($credit->getBalance() + $balance);
		$timeSite = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
        $credithistory = Mage::getModel('giftvoucher/credithistory')
                ->setCustomerId($customer->getId())
                ->setAction('Adminupdate')
                ->setCurrencyBalance($credit->getBalance())
                ->setBalanceChange($balance)
                ->setCurrency($credit->getCurrency())
                ->setCreatedDate($timeSite);
        try {
            $credit->save();
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__($e->getMessage()));
        }
        try {
            $credithistory->save();
        } catch (Mage_Core_Exception $e) {
            $credit->setBalance($credit->getBalance() - $balance)->save();
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__($e->getMessage()));
        }
        return $this;
    }

    // Gift Card Product Condition
    public function conditionsAction($observer) {
        $product = Mage::registry('current_product');
        $model = Mage::getSingleton('giftvoucher/product');
        if (!$model->getId() && $product->getId()) {
            $model->loadByProduct($product);
        }
        $model->getConditions()->setJsFormObject('giftvoucher_conditions_fieldset');
        Mage::app()->getLayout()->getBlock('head')->setCanLoadRulesJs(true);
    }

    public function productSaveAfter($observer) {
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() != 'giftvoucher' || !$product->getId()) {
            return $this;
        }
        $model = Mage::getSingleton('giftvoucher/product');
        if ($model->getIsSavedConditions()) {
            return $this;
        }
        $model->setIsSavedConditions(true);
        if (!$model->getId()) {
            $model->loadByProduct($product);
        }
        $data = Mage::app()->getRequest()->getPost();
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
        $model->loadPost($data);
        $model->setProductId($product->getId());
        try {
            $model->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function cartIndexAction($observer) {
        $session = Mage::getSingleton('checkout/session');
        if ($session->getQuote()->getCouponCode() && !Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($session->getUseGiftCreditAmount() > 0 || $session->getGiftVoucherDiscount() > 0)) {
            $session->addNotice(Mage::helper('giftvoucher')->__('A coupon code has been used. You cannot apply gift codes or Gift Card credit with the coupon to get discount.'));
            $session->setMessageApplyGiftcardWithCouponCode(false);
        }
    }

    public function predispatchCheckout($observer) {
        $action = $observer->getEvent()->getControllerAction();
        $requestPath = $action->getRequest()->getRequestedRouteName()
                . '_' . $action->getRequest()->getRequestedControllerName()
                . '_' . $action->getRequest()->getRequestedActionName();

        $session = Mage::getSingleton('core/session');
        $cart = Mage::getSingleton('checkout/session');

        if ($requestPath == 'checkout_multishipping_addresses')
            $cart->setUseGiftCard(0)
                    ->setUseGiftCardCredit(0);

        $items = $cart->getQuote()->getAllItems();
        foreach ($items as $item) {
            $code = 'recipient_ship';
            $code_send_friend = 'send_friend';
            $option = $item->getOptionByCode($code);
            $option2 = $item->getOptionByCode($code_send_friend);
            if ($option && $option2) {
                $data = $option->getData();
            }

            if (isset($data['value']) && $data['value'] != null) {
                $session->addNotice(Mage::helper('giftvoucher')->__('You need to add your friend\'s address as the shipping address. We will send this gift card to that address.'));
                return $this;
            }
        }

        if ($cart->getQuote()->getCouponCode() && !Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($cart->getUseGiftCreditAmount() > 0 || $cart->getGiftVoucherDiscount() > 0)) {
            $session->setMessageApplyGiftcardWithCouponCode(false);
            $session->addNotice(Mage::helper('giftvoucher')->__('A coupon code has been used. You cannot apply gift codes or Gift Card credit with the coupon to get discount.'));
        }
        return $this;
    }

    public function controllerActionLayoutGenerateBlocksAfter($observer) {
        $store_Id = Mage::app()->getStore()->getId();
        if (Mage::helper('giftvoucher')->getGeneralConfig('active', $store_Id)) {
            $class = get_class($observer['action']);
            if (isset($class) && $class != null) {
                $infor = explode('_', $class);
                $extensionName = $infor[0] . '_' . $infor[1];

                if (strpos($extensionName, 'Mage_') === false && $extensionName != 'Magestore_Onestepcheckout') {
                    $this->removeItemsLayout($extensionName);
                }
            }
        }
    }

    /**
     * auto delete image in media/tmp
     */
    public function autoDeleteImage() {
        $dir = Mage::getBaseDir('media') . DS . 'tmp' . DS . 'giftvoucher' . DS . 'cache' . DS;
        $this->delTree($dir);
        return;
    }

    public function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir($dir . DS . $file)) ? $this->delTree($dir . DS . $file) : unlink($dir . DS . $file);
        }
        return;
    }

    public function autoDeleteImage1() {
        $dir = Mage::getBaseDir('media') . DS . 'tmp' . DS . 'giftvoucher' . DS . 'images' . DS;
        $h = opendir($dir);
        while ($file = readdir($h)) {
            if ($file != '.' and $file != '..') {
                unlink($dir . $file);
            }
        }
        closedir($h);
        return;
    }

    public function applyWithCoupon($observer) {
        $action = $observer->getEvent()->getControllerAction();
        $session = Mage::getSingleton('checkout/session');
        $admin_session = Mage::getSingleton('adminhtml/session_quote');
        $params = $action->getRequest()->getPost();

        if (isset($params['order']['coupon']['code']) && $params['order']['coupon']['code'] != null) {
            $giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($params['order']['coupon']['code']);
            $quote = $admin_session->getQuote();

            if ($giftVoucher->getId() && $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBaseBalance() > 0 && $giftVoucher->validate($quote->setQuote($quote))
            ) {
                $count = 0;
                $items = $quote->getAllItems();
                foreach ($items as $item) {
                    $data = $item->getData();
                    if ($data['product_type'] == 'giftvoucher') {
                        $count++;
                    }
                }
                if ($count == count($items)) {
                    $admin_session->addNotice(Mage::helper('giftvoucher')->__('Gift Cards cannot be used to purchase Gift Card products'));
                    unset($params['order']['coupon']);
                    $action->getRequest()->setPost($params);
                }
            } else {
                if (!Mage::helper('giftvoucher')->getGeneralConfig('use_with_coupon') && ($session->getUseGiftCreditAmount() > 0 || $session->getGiftVoucherDiscount() > 0)) {
                    if ($session->getUseGiftCardCredit() && $session->getUseGiftCard()) {
                        $admin_session->addNotice(Mage::helper('giftvoucher')->__('You cannot apply a coupon code with either gift codes or Gift Card credit at once to get discount.'));
                    } elseif ($session->getUseGiftCard()) {
                        $admin_session->addNotice(Mage::helper('giftvoucher')->__('The gift code(s) has been used. You cannot apply a coupon code with gift codes to get discount.'));
                    } elseif ($session->getUseGiftCardCredit()) {
                        $admin_session->addNotice(Mage::helper('giftvoucher')->__('An amount in your Gift Card credit has been used. You cannot apply a coupon code with Gift Card credit to get discount.'));
                    }
                    unset($params['order']['coupon']);

                    $action->getRequest()->setPost($params);
                }
            }
        }

        return $this;
    }

    public function removeItemsLayout($extensionName) {
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        if (isset($headBlock) && $headBlock != null) {
            $jsUrl = Mage::getBaseDir() . DS . 'js' . DS . 'magestore' . DS . 'giftvoucher' . DS . 'onestepcheckout' . DS . $extensionName . DS . 'giftvoucher.js';
            $cssUrl1 = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . DS, Mage::getDesign()->getSkinUrl('css/magestore/onestepcheckout/' . $extensionName . '/giftvoucher.css', array()));
            $cssUrl2 = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . DS, Mage::getDesign()->getSkinUrl('css/magestore/onestepcheckout/' . $extensionName . '/reupdate.css', array()));
            $cssUrl3 = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . DS, Mage::getDesign()->getSkinUrl('css/magestore/onestepcheckout/' . $extensionName . '/reupdate_temp3.css', array()));
            $cssUrl4 = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . DS, Mage::getDesign()->getSkinUrl('css/magestore/onestepcheckout/' . $extensionName . '/mobile_giftvoucher.css', array()));

            if (is_file($jsUrl)) {
                $headBlock->removeItem('js', 'magestore/giftvoucher/giftvoucher.js');
                $headBlock->addJs('magestore/giftvoucher/onestepcheckout/' . $extensionName . '/giftvoucher.js');
            }
            if (is_file(str_replace('/', DIRECTORY_SEPARATOR, $cssUrl1))) {
                $headBlock->removeItem('skin_css', 'css/magestore/giftvoucher.css');
                $headBlock->addCss('css/magestore/onestepcheckout/' . $extensionName . '/giftvoucher.css');
            }

            if (is_file(str_replace('/', DIRECTORY_SEPARATOR, $cssUrl2))) {
                $headBlock->removeItem('skin_css', 'css/magestore/reupdate.css');
                $headBlock->addCss('css/magestore/onestepcheckout/' . $extensionName . '/reupdate.css');
            }

            if (is_file(str_replace('/', DIRECTORY_SEPARATOR, $cssUrl3))) {
                $headBlock->removeItem('skin_css', 'css/magestore/reupdate_temp3.css');
                $headBlock->addCss('css/magestore/onestepcheckout/' . $extensionName . '/reupdate_temp3.css');
            }
            if (is_file(str_replace('/', DIRECTORY_SEPARATOR, $cssUrl4))) {
                $headBlock->removeItem('skin_css', 'css/magestore/mobile_giftvoucher.css');
                $headBlock->addCss('css/magestore/onestepcheckout/' . $extensionName . '/mobile_giftvoucher.css');
            }
        }
    }

}
