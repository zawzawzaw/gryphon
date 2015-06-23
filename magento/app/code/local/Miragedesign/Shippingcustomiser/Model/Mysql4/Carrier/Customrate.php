<?php

/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Miragedesign_Shippingcustomiser_Model_Mysql4_Carrier_Customrate extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('shippingcustomiser/customrate', 'pk');
    }

    public function getNewRate(Mage_Shipping_Model_Rate_Request $request)
    {
        $adapter = $this->_getReadAdapter();
        $bind = array(
            ':website_id' => (int) $request->getWebsiteId(),
            ':country_id' => $request->getDestCountryId(),
            ':region_id' => (int) $request->getDestRegionId(),
            ':postcode' => $request->getDestPostcode()
        );
        $select = $adapter->select()
            ->from($this->getMainTable())
            ->where('website_id = :website_id')
            ->order(array('dest_country_id DESC', 'dest_region_id DESC', 'dest_zip DESC', 'condition_value DESC'))
            ->limit(1);

        // Render destination condition
        $orWhere = '(' . implode(') OR (', array(
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = :postcode",
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = ''",

                // Handle asterix in dest_zip field
                "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_zip = '*'",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = '*'",
                "dest_country_id = '0' AND dest_region_id = :region_id AND dest_zip = '*'",
                "dest_country_id = '0' AND dest_region_id = 0 AND dest_zip = '*'",

                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = ''",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = :postcode",
                "dest_country_id = :country_id AND dest_region_id = 0 AND dest_zip = '*'",
            )) . ')';
        $select->where($orWhere);

        // Render condition by condition name
        $bind[':condition_name']  = $request->getConditionName();
        $bind[':condition_value'] = $request->getData($request->getConditionName());

        $select->where('condition_name = :condition_name');
        $select->where('condition_value <= :condition_value');

        $result = $adapter->fetchRow($select, $bind);

        if ($result && $result['dest_zip'] == '*') {
            $result['dest_zip'] = '';
        }
        return $result;
    }

    public function uploadAndImport(Varien_Object $object)
    {
        $csvFile = $_FILES["groups"]["tmp_name"]["customrate"]["fields"]["import"]["value"];

        if (!empty($csvFile)) {

            $csv = trim(file_get_contents($csvFile));

            $table = Mage::getSingleton('core/resource')->getTableName('shippingcustomiser/customrate');

            $websiteId = $object->getScopeId();
            $websiteModel = Mage::app()->getWebsite($websiteId);
            /*
            getting condition name from post instead of the following commented logic
            */

            if (isset($_POST['groups']['customrate']['fields']['condition_name']['inherit'])) {
                $conditionName = (string)Mage::getConfig()->getNode('default/carriers/customrate/condition_name');
            } else {
                $conditionName = $_POST['groups']['customrate']['fields']['condition_name']['value'];
            }

//            $conditionName = $object->getValue();
//            if ($conditionName{0} == '_') {
//                $conditionName = Mage::helper('core/string')->substr($conditionName, 1, strpos($conditionName, '/')-1);
//            } else {
//                $conditionName = $websiteModel->getConfig('carriers/customrate/condition_name');
//            }
            $conditionFullName = Mage::getModel('shippingcustomiser/carrier_customrate')->getCode('condition_name_short', $conditionName);
            if (!empty($csv)) {
                $exceptions = array();
                $csvLines = explode("\n", $csv);
                $csvLine = array_shift($csvLines);
                $csvLine = $this->_getCsvValues($csvLine);
                if (count($csvLine) < 6) {
                    $exceptions[0] = Mage::helper('shipping')->__('Invalid Custom Rates File Format');
                }

                $countryCodes = array();
                $regionCodes = array();
                foreach ($csvLines as $k => $csvLine) {
                    $csvLine = $this->_getCsvValues($csvLine);
                    if (count($csvLine) > 0 && count($csvLine) < 6) {
                        $exceptions[0] = Mage::helper('shipping')->__('Invalid Custom Rates File Format');
                    } else {
                        $countryCodes[] = $csvLine[0];
                        $regionCodes[] = $csvLine[1];
                    }
                }

                if (empty($exceptions)) {
                    $data = array();
                    $countryCodesToIds = array();
                    $regionCodesToIds = array();
                    $countryCodesIso2 = array();

                    $countryCollection = Mage::getResourceModel('directory/country_collection')->addCountryCodeFilter($countryCodes)->load();
                    foreach ($countryCollection->getItems() as $country) {
                        $countryCodesToIds[$country->getData('iso3_code')] = $country->getData('country_id');
                        $countryCodesToIds[$country->getData('iso2_code')] = $country->getData('country_id');
                        $countryCodesIso2[] = $country->getData('iso2_code');
                    }

                    $regionCollection = Mage::getResourceModel('directory/region_collection')
                        ->addRegionCodeFilter($regionCodes)
                        ->addCountryFilter($countryCodesIso2)
                        ->load();

                    foreach ($regionCollection->getItems() as $region) {
                        $regionCodesToIds[$countryCodesToIds[$region->getData('country_id')]][$region->getData('code')] = $region->getData('region_id');
                    }

                    foreach ($csvLines as $k => $csvLine) {

                        $csvLine = $this->_getCsvValues($csvLine);

                        if (empty($countryCodesToIds) || !array_key_exists($csvLine[0], $countryCodesToIds)) {
                            $countryId = '0';
                            if ($csvLine[0] != '*' && $csvLine[0] != '') {
                                $exceptions[] = Mage::helper('shipping')->__('Invalid Country "%s" in the Row #%s', $csvLine[0], ($k + 1));
                            }
                        } elseif ($csvLine[0] == '*' || $csvLine[0] == '') {
                            $countryId = '0';
                        } else {
                            $countryId = $countryCodesToIds[$csvLine[0]];
                        }

                        if (!isset($countryCodesToIds[$csvLine[0]])
                            || !isset($regionCodesToIds[$countryCodesToIds[$csvLine[0]]])
                            || !array_key_exists($csvLine[1], $regionCodesToIds[$countryCodesToIds[$csvLine[0]]])
                        ) {
                            $regionId = '0';
                            if ($csvLine[1] != '*' && $csvLine[1] != '') {
                                $exceptions[] = Mage::helper('shipping')->__('Invalid Region/State "%s" in the Row #%s', $csvLine[1], ($k + 1));
                            }
                        } elseif ($csvLine[1] == '*' || $csvLine[1] == '') {
                            $regionId = 0;
                        } else {
                            $regionId = $regionCodesToIds[$countryCodesToIds[$csvLine[0]]][$csvLine[1]];
                        }

                        if ($csvLine[2] == '*' || $csvLine[2] == '') {
                            $zip = '*';
                        } else {
                            $zip = $csvLine[2];
                        }

                        if (!$this->_isPositiveDecimalNumber($csvLine[3]) || $csvLine[3] == '*' || $csvLine[3] == '') {
                            $exceptions[] = Mage::helper('shipping')->__('Invalid %s From "%s" in the Row #%s', $conditionFullName, $csvLine[3], ($k + 1));
                        } else {
                            $csvLine[3] = (float)$csvLine[3];
                        }

                        $data[] = array('website_id' => $websiteId, 'dest_country_id' => $countryId, 'dest_region_id' => $regionId, 'dest_zip' => $zip, 'condition_name' => $conditionName, 'condition_value' => $csvLine[3], 'price' => $csvLine[4], 'delivery_time' => $csvLine[5]);

                        $dataDetails[] = array('country' => $csvLine[0], 'region' => $csvLine[1]);
                    }
                }
                if (empty($exceptions)) {
                    $connection = $this->_getWriteAdapter();

                    $condition = array(
                        $connection->quoteInto('website_id = ?', $websiteId),
                        $connection->quoteInto('condition_name = ?', $conditionName),
                    );
                    $connection->delete($table, $condition);

                    foreach ($data as $k => $dataLine) {
                        try {
                            $connection->insert($table, $dataLine);
                        } catch (Exception $e) {
                            $exceptions[] = Mage::helper('shipping')->__('Duplicate Row #%s (Country "%s", Region/State "%s", Zip "%s", Delivery Time "%s", Value "%s")', ($k + 1), $dataDetails[$k]['country'], $dataDetails[$k]['region'], $dataLine['dest_zip'], $dataLine['delivery_time'], $dataLine['condition_value']);
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
