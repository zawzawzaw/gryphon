<?php

/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Miragedesign_Shippingcustomiser_Model_Mysql4_Carrier_Customrate_Collection extends Varien_Data_Collection_Db
{
    protected $_shipTable;
    protected $_countryTable;
    protected $_regionTable;

    public function __construct()
    {
        parent::__construct(Mage::getSingleton('core/resource')->getConnection('shipping_read'));
        $this->_shipTable = Mage::getSingleton('core/resource')->getTableName('shippingcustomiser/customrate');
        $this->_countryTable = Mage::getSingleton('core/resource')->getTableName('directory/country');
        $this->_regionTable = Mage::getSingleton('core/resource')->getTableName('directory/country_region');
        $this->_select->from(array("s" => $this->_shipTable))
            ->joinLeft(array("c" => $this->_countryTable), 'c.country_id = s.dest_country_id', 'iso3_code AS dest_country')
            ->joinLeft(array("r" => $this->_regionTable), 'r.region_id = s.dest_region_id', 'code AS dest_region')
            ->order(array("dest_country", "dest_region", "dest_zip"));
        $this->_setIdFieldName('pk');
        return $this;
    }

    public function setWebsiteFilter($websiteId)
    {
        $this->_select->where("website_id = ?", $websiteId);

        return $this;
    }

    public function setConditionFilter($conditionName)
    {
        $this->_select->where("condition_name = ?", $conditionName);
        return $this;
    }

    public function setCountryFilter($countryId)
    {
        $this->_select->where("dest_country_id = ?", $countryId);

        return $this;
    }
}