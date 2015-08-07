<?php

/**
 * Adding Products
 */
Mage::log("START INSTALL SUBSCRIPTION PRODUCTS");
$installer = $this;
$installer->startSetup();

$artisan_subs = array();
$sng_sub_1 = array("sku" =>'SGP-ART-1M','name'=>'1 Month SGP Subscription','description'=>'1 Month SGP Subscription','price'=>'28.80','weight'=>'30');
$sng_sub_3 = array("sku" =>'SGP-ART-3M','name'=>'3 Month SGP Subscription','description'=>'3 Month SGP Subscription','price'=>'79.80','weight'=>'90');
$sng_sub_6 = array("sku" =>'SGP-ART-6M','name'=>'6 Month SGP Subscription','description'=>'6 Month SGP Subscription','price'=>'152.80','weight'=>'180');
$sng_sub_12 = array("sku" =>'SGP-ART-12M','name'=>'12 Month SGP Subscription','description'=>'12 Month SGP Subscription','price'=>'296.80','weight'=>'360');
$int_sub_1 = array("sku" =>'INT-ART-1M','name'=>'1 Month INT Subscription','description'=>'1 Month International Subscription','price'=>'35.80','weight'=>'30');
$int_sub_3 = array("sku" =>'INT-ART-3M','name'=>'3 Month INT Subscription','description'=>'3 Month International Subscription','price'=>'98.80','weight'=>'90');
$int_sub_6 = array("sku" =>'INT-ART-6M','name'=>'6 Month INT Subscription','description'=>'6 Month International Subscription','price'=>'186.80','weight'=>'180');
$int_sub_12 = array("sku" =>'INT-ART-12M','name'=>'12 Month INT Subscription','description'=>'12 Month International Subscription','price'=>'362.80','weight'=>'360');

$artisan_subs[] = $sng_sub_1;
$artisan_subs[] = $sng_sub_3;
$artisan_subs[] = $sng_sub_6;
$artisan_subs[] = $sng_sub_12;
$artisan_subs[] = $int_sub_1;
$artisan_subs[] = $int_sub_3;
$artisan_subs[] = $int_sub_6;
$artisan_subs[] = $int_sub_12;

$gourmet_subs = array();
$gsng_sub_1 = array("sku" =>'SGP-GUR-1M','name'=>'1 Month SGP Subscription','description'=>'1 Month SGP Subscription','price'=>'34.80','weight'=>'120');
$gsng_sub_3 = array("sku" =>'SGP-GUR-3M','name'=>'3 Month SGP Subscription','description'=>'3 Month SGP Subscription','price'=>'95.80','weight'=>'360');
$gsng_sub_6 = array("sku" =>'SGP-GUR-6M','name'=>'6 Month SGP Subscription','description'=>'6 Month SGP Subscription','price'=>'177.80','weight'=>'720');
$gsng_sub_12 = array("sku" =>'SGP-GUR-12M','name'=>'12 Month SGP Subscription','description'=>'12 Month SGP Subscription','price'=>'339.80','weight'=>'1440');
$gint_sub_1 = array("sku" =>'INT-GUR-1M','name'=>'1 Month INT Subscription','description'=>'1 Month International Subscription','price'=>'42.80','weight'=>'120');
$gint_sub_3 = array("sku" =>'INT-GUR-3M','name'=>'3 Month INT Subscription','description'=>'3 Month International Subscription','price'=>'117.80','weight'=>'360');
$gint_sub_6 = array("sku" =>'INT-GUR-6M','name'=>'6 Month INT Subscription','description'=>'6 Month International Subscription','price'=>'213.80','weight'=>'720');
$gint_sub_12 = array("sku" =>'INT-GUR-12M','name'=>'12 Month INT Subscription','description'=>'12 Month International Subscription','price'=>'403.80','weight'=>'1440');

$gourmet_subs[] = $gsng_sub_1;
$gourmet_subs[] = $gsng_sub_3;
$gourmet_subs[] = $gsng_sub_6;
$gourmet_subs[] = $gsng_sub_12;
$gourmet_subs[] = $gint_sub_1;
$gourmet_subs[] = $gint_sub_3;
$gourmet_subs[] = $gint_sub_6;
$gourmet_subs[] = $gint_sub_12;
$art_categoryids = array(94);
$gou_categoryids = array(95);

$websiteid = Mage::getModel( "core/website" )->load( 'base' )->getId();
$storeid = Mage::getModel( "core/store" )->load( 'default' )->getId();
Mage::app()->getStore(Mage_Core_Model_App::DISTRO_STORE_ID)->setWebsiteId(1);
foreach ($artisan_subs as $row) {
    try{
        $product = Mage::getModel('catalog/product');
        $product->setSku($row['sku']);
        $product->setName($row['name']);
        $product->setDescription($row['description']);
        $short_desc = $row['weight']." Sachets";
        $product->setShortDescription($short_desc);
        $product->setPrice($row['price']);
        $product->setTypeId('virtual');
        $product->setAttributeSetId(4); // enter the catalog attribute set id here
        $product->setCategoryIds($art_categoryids); // id of categories
        $product->setWeight($row['weight']);
        $product->setTaxClassId(0);
        $product->setVisibility(2);
        $product->setStatus(1);
        $product->setStockData(
            array(
            'manage_stock' => 0,
            'is_in_stock' => 1,
            )
        );
        // assign product to the default website
        $product->setStoreIds(array($storeid));
        $product->setWebsiteIds(array(1));
        $product->setStoreId(1);
        /*try
        {
            $optionData = array(
            'is_delete'         => 0,
            'is_require'        => false,
            'previous_group'    => '',
            'title'             => 'Selected Teas',
            'type'              => Mage_Catalog_Model_Product_Option::OPTION_GROUP_TEXT,
            'sort_order'        => 1,
            );

            $optionInstance = $product->getOptionInstance()->unsetOptions();

            $product->setHasOptions(1);
            $optionInstance->addOption($optionData);
            $optionInstance->setProduct($product);
        }  
        catch (Exception $e)
        {
            Mage::logException($e);
        }*/
        /*  ------------------- */
        $product->save();
        $id = $product->getId();
    } catch (Exception $ex) {
        Mage::logException($ex);
    }
}

foreach ($gourmet_subs as $row) {
    try{
        $product = Mage::getModel('catalog/product');
        $product->setSku($row['sku']);
        $product->setName($row['name']);
        $product->setDescription($row['description']);
        $short_desc = "Tea Weight <br>". $row['weight']." g";
        $product->setShortDescription($short_desc);
        $product->setPrice($row['price']);
        $product->setTypeId('virtual');
        $product->setAttributeSetId(4); // enter the catalog attribute set id here
        $product->setCategoryIds($gou_categoryids); // id of categories
        $product->setWeight($row['weight']);
        $product->setTaxClassId(0);
        $product->setVisibility(2);
        $product->setStatus(1);
        $product->setStockData(
            array(
            'manage_stock' => 0,
            'is_in_stock' => 1,
            )
        );
        // assign product to the default website
        $product->setWebsiteIds(array(1));
        $product->setStoreId(1);
        $product->save();
        $id = $product->getId();
    } catch (Exception $ex) {
        Mage::logException($ex);
    }
}

$installer->endSetup();
