<?php
/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
 
 class Genmato_Payment_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract {
	 protected $_canCapture         = true;
	 protected $_canCapturePartial  = true;


	 public function isAvailable($quote = null) {
		 $min_order_amount	= Mage::getStoreConfig('payment/'.$this->_code.'/min_order_amount');
		 if(!is_numeric($min_order_amount)){
			 $min_order_amount = 0;
		 }
		 if( $min_order_amount>0 && (Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) < Mage::app()->getStore()->roundPrice($min_order_amount)) ) {
			 return false;
		 }

		 $max_order_amount	= Mage::getStoreConfig('payment/'.$this->_code.'/max_order_amount');
		 if(!is_numeric($max_order_amount)){
			 $max_order_amount = 0;
		 }

		 if($max_order_amount>0 && (Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) >= Mage::app()->getStore()->roundPrice($max_order_amount)) ) {
			 return false;
		 }

		 $allowed_groups		= explode(',',Mage::getStoreConfig('payment/'.$this->_code.'/allow_group'));
		 if(!in_array($quote->getCustomerGroupId(),$allowed_groups)) {
			 return false;
		 }

		 return parent::isAvailable($quote);
	 }
 }