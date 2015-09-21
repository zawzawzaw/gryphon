<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Mysql4_Carrier_Freeshippingrates extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct()
    {
        $this->_init('shippingcustomiser/customfreeshippingrate', 'pk');
    }
    public function uploadAndImport(Varien_Object $object)
    {
        $csvFile = $_FILES["groups"]["tmp_name"]["customrate"]["fields"]["free_shipping_import"]["value"];

        if (!empty($csvFile)) {

            $csv = trim(file_get_contents($csvFile));

            $table = Mage::getSingleton('core/resource')->getTableName('shippingcustomiser/customfreeshippingrate');

            $websiteId = $object->getScopeId();           

            if (!empty($csv)) {
                $exceptions = array();
                $csvLines = explode("\n", $csv);
                $csvLine = array_shift($csvLines);
                $csvLine = $this->_getCsvValues($csvLine);
                if (count($csvLine) < 4) {
                    $exceptions[0] = Mage::helper('shipping')->__('Invalid Custom Rates File Format');
                }
               
                if (empty($exceptions)) {
                    $data = array();                   

                    foreach ($csvLines as $k => $csvLine) {

                        $csvLine = $this->_getCsvValues($csvLine);                       

                        $data[] = array('website_id' => $websiteId, 'groupname' => $csvLine[0], 'OrderAmountFrom' => $csvLine[1], 'OrderAmountTo' => $csvLine[2], 'ShippingCharge' => $csvLine[3]);

                        $dataDetails[] = array('groupname' => $csvLine[0], 'OrderAmountFrom' => $csvLine[1], 'OrderAmountTo' => $csvLine[2], 'ShippingCharge' => $csvLine[3]);
                    }
                }
				
                if (empty($exceptions)) {
                    $connection = $this->_getWriteAdapter();

                    $condition = array(
                        $connection->quoteInto('website_id = ?', $websiteId),                        
                    );
                    $connection->delete($table, $condition);

                    foreach ($data as $k => $dataLine) {
                        try {
                            $connection->insert($table, $dataLine);
                        } catch (Exception $e) {
                            $exceptions[] = Mage::helper('shipping')->__('Duplicate Row #%s (GroupName "%s", OrderAmountFrom "%s", OrderAmountTo "%s", ShippingCharge "%s")', $dataDetails[$k]['groupname'], $dataDetails[$k]['OrderAmountFrom'], $dataDetails[$k]['OrderAmountTo'], $dataDetails[$k]['ShippingCharge']);
                        }
                    }
                }
                if (!empty($exceptions)) {
                    throw new Exception("\n" . implode("\n", $exceptions));
                }
            }
        }
    }

    private function _getCsvValues($string, $separator = ",")
    {
        $elements = explode($separator, trim($string));
        for ($i = 0; $i < count($elements); $i++) {
            $nquotes = substr_count($elements[$i], '"');
            if ($nquotes % 2 == 1) {
                for ($j = $i + 1; $j < count($elements); $j++) {
                    if (substr_count($elements[$j], '"') > 0) {
                        // Put the quoted string's pieces back together again
                        array_splice($elements, $i, $j - $i + 1, implode($separator, array_slice($elements, $i, $j - $i + 1)));
                        break;
                    }
                }
            }
            if ($nquotes > 0) {
                // Remove first and last quotes, then merge pairs of quotes
                $qstr =& $elements[$i];
                $qstr = substr_replace($qstr, '', strpos($qstr, '"'), 1);
                $qstr = substr_replace($qstr, '', strrpos($qstr, '"'), 1);
                $qstr = str_replace('""', '"', $qstr);
            }
            $elements[$i] = trim($elements[$i]);
        }
        return $elements;
    }

    private function _isPositiveDecimalNumber($n)
    {
        return preg_match("/^[0-9]+(\.[0-9]*)?$/", $n);
    }   
}