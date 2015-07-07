<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class J2t_Rewardbirthday_Model_Observer extends Mage_Core_Model_Abstract {
    
    const XML_PATH_BIRTHDAY_EMAIL_TEMPLATE       = 'rewardpoints/j2trewardbirthday/email_template'; 
    const XML_PATH_BIRTHDAY_EMAIL_IDENTITY       = 'rewardpoints/j2trewardbirthday/email_identity'; 
    const XML_PATH_BIRTHDAY_POINTS               = 'rewardpoints/j2trewardbirthday/birthday_points';
    const XML_PATH_BIRTHDAY_LOGS                 = 'rewardpoints/j2trewardbirthday/birthday_logs';
    const XML_PATH_BIRTHDAY_PRIOR_VERIFICATION   = 'rewardpoints/j2trewardbirthday/prior_verification';
    const XML_PATH_BIRTHDAY_VALIDITY             = 'rewardpoints/j2trewardbirthday/points_duration';
    const XML_PATH_BIRTHDAY_DELAY                = 'rewardpoints/j2trewardbirthday/points_delay';
    
    
    
    
    public function aggregateBirthdayData(){
        
        $record_logs = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_LOGS);
        
        if ($record_logs){
            $message_log = date("Y-m-d h:i:s");
            $message = sprintf('[START] - Log message: birthday verification on %s', $message_log);
            $file    = "j2trewardsbirthday.log";
            Mage::log($message, Zend_Log::DEBUG, $file);
        }
        
        //1. get all customers
        //2. check the birthdays
        //3. if current day give points only if points haven't been given for specific day
        $collection = Mage::getModel('customer/customer')
				  ->getCollection()
				  ->addAttributeToSelect('*');
        
        if ($record_logs){
            $message_log = $collection->getSelect()->__toString();
            $message = sprintf('Log sql query: %s', $message_log);
            Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
        
        
            $message = sprintf('Log sql count: %d', $collection->getSize());
            Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
        }
        
	foreach ($collection as $customer) {
            //$result[] = $customer->toArray();
            $store_id = $customer->getStoreId();
            $points = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_POINTS, $store_id);
            $prior_verification_days = (int)Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_PRIOR_VERIFICATION, $store_id);
            
            if (($dob = $customer->getDob()) && $points > 0){
                //dob 1980-03-23 00:00:00
                $customer_id = $customer->getId();
                
                if ($record_logs){
                    $message = sprintf('Log birthday verification customer #%d (%s) - DOB DEFINED (%s)', $customer->getId(), $customer->getName(), $dob);
                    Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                }
                
                /*
                $dob_year = (int)Mage::app()->getLocale()->date($dob, null, null, false)->toString('yyyy');//Mage::app()->getLocale()->date($dob, null, null, false)->toString('yyyy-MM-dd');
                $dob_month = (int)Mage::app()->getLocale()->date($dob, null, null, false)->toString('M');//Mage::app()->getLocale()->date($dob, null, null, false)->toString('yyyy-MM-dd');
                $dob_day = (int)Mage::app()->getLocale()->date($dob, null, null, false)->toString('d');//Mage::app()->getLocale()->date($dob, null, null, false)->toString('yyyy-MM-dd');
                */
                
                $str_mktime = strtotime($dob);
                
                $dob_year = date("Y", $str_mktime);
                $dob_month = date("m", $str_mktime);
                $dob_day = date("d", $str_mktime);
                
                $minus_days = 0;
                if ($prior_verification_days){
                    $minus_days = $prior_verification_days * 24 * 60 * 60;
                }
                
                $date_verified_dob_original = mktime(0, 0, 0, $dob_month, $dob_day, date("Y"));
                $date_verified_dob = mktime(0, 0, 0, $dob_month, $dob_day, date("Y")) - $minus_days;
                $date_verified_current = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                
                if ($record_logs){
                    $message = sprintf('Log birthday verification (dob: %s) customer #%d (%s): verification (%s = %s)? Original DOB: %s.', $customer->getDob(), $customer->getId(), $customer->getName(), date('Y-m-d H:i:s', $date_verified_current), date('Y-m-d H:i:s', $date_verified_dob), date('Y-m-d H:i:s', $date_verified_dob_original));
                    Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                }
                
                if ($date_verified_current == $date_verified_dob){
                //if (mktime(0, 0, 0, date("m"), date("d"), date("Y")) == mktime(0, 0, 0, $dob_month, $dob_day, date("Y"))){
                    //check if points were given to the customer
                    
                    $dob_customer = Mage::getModel('rewardpoints/stats')->getDobPoints($store_id, $customer_id);
                    if ($date_start = $dob_customer->getDateStart()){
                        //check if year date_start < todays year
                        $last_dob_year_mktime = strtotime($date_start);
                        $last_dob_year = date("Y", $last_dob_year_mktime);
                        //$last_dob_year = Mage::app()->getLocale()->date($date_start, null, null, false)->toString('yyyy');
                        if ($last_dob_year < date("Y")){
                            //echo 'HAPPY BIRTHDAY!!!';
                            $this->addBirthdayPoints($points, Rewardpoints_Model_Stats::TYPE_POINTS_BIRTHDAY, $store_id, $customer_id);
                            if ($record_logs){
                                $message = sprintf('Log birthday verification customer #%d (%s): Points allocated', $customer->getId(), $customer->getName());
                                Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                            }
                        } else {
                            //echo 'NOT HAPPY BIRTHDAY!!!';
                            if ($record_logs){
                                $message = sprintf('Log birthday verification customer #%d (%s): Points already allocated (%s < %s)?', $customer->getId(), $customer->getName(), $last_dob_year, date("Y"));
                                Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                            }
                        }
                    } else {
                        //HAPPY BIRTHDAY
                        $this->addBirthdayPoints($points, Rewardpoints_Model_Stats::TYPE_POINTS_BIRTHDAY, $store_id, $customer_id);
                        if ($record_logs){
                            $message = sprintf('Log birthday verification customer #%d (%s): Points allocated', $customer->getId(), $customer->getName());
                            Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                        }
                    }
                }
                
                //$resource = Mage::getResourceSingleton('catalogrule/rule');
                //$resource->applyAllRulesForDateRange($resource->formatDate(mktime(0,0,0)));
                
            } else {
                if ($record_logs){
                    $message = sprintf('Log birthday verification customer #%d (%s) - NO DOB DEFINED', $customer->getId(), $customer->getName());
                    Mage::log($message, Zend_Log::DEBUG, "j2trewardsbirthday.log");
                }
            }
	}    
        if ($record_logs){
            Mage::log("[END]", Zend_Log::DEBUG, "j2trewardsbirthday.log"); 
        }
    }
    
    protected function sendBirthdayEmail(Mage_Customer_Model_Customer $user, $points, $store_id)
    {
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $email = Mage::getModel('core/email_template');
        /* @var $email Mage_Core_Model_Email_Template */        

        $template = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_EMAIL_TEMPLATE, $store_id);
        $recipient = array(
            'email' => $user->getEmail(),
            'name'  => $user->getFullName()
        );

        $sender  = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_EMAIL_IDENTITY, $store_id);

        $email->setDesignConfig(array('area'=>'frontend', 'store'=> $store_id))
                ->sendTransactional(
                    $template,
                    $sender,
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'user'   => $user,
                        'points'    => $points,
                        'store_name' => Mage::getModel('core/store')->load(Mage::app()->getStore($store_id)->getCode())->getName()
                    )
                );

        $translate->setTranslateInline(true);
        return $email->getSentSuccess();
    }
    
    
    protected function addBirthdayPoints($points, $order_id, $store_id, $customer_id){
        $reward_model = Mage::getModel('rewardpoints/stats');
        $post = array('order_id' => $order_id, 'customer_id' => $customer_id, 'store_id' => $store_id, 'points_current' => $points, 'convertion_rate' => Mage::getStoreConfig('rewardpoints/default/points_money', $store_id));
        //v.2.0.0
        $add_delay = 0;
        $delay = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_DELAY, $store_id);
        if (!is_numeric($delay)){
            $delay = 0;
        }    
        $post['date_start'] = $reward_model->getResource()->formatDate(mktime(0, 0, 0, date("m"), date("d")+$delay, date("Y")));
        $add_delay = $delay;
        
        if ($duration = Mage::getStoreConfig(self::XML_PATH_BIRTHDAY_VALIDITY, $store_id)){
            if (is_numeric($duration)){
                if (!isset($post['date_start'])){
                    $post['date_start'] = $reward_model->getResource()->formatDate(time());
                }
                $post['date_end'] = $reward_model->getResource()->formatDate(mktime(0, 0, 0, date("m"), date("d")+$duration+$add_delay, date("Y")));
            }
        }
        $reward_model->setData($post);
        $reward_model->save();
        
        Mage::getModel('rewardpoints/observer')->processRecordFlat($customer_id, $store_id);
        
        $this->sendBirthdayEmail(Mage::getModel('customer/customer')->load($customer_id), $points, $store_id);
    }

}
