<?php
/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */

class Genmato_Payment_Model_System_Invoice_Capture
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = array();
            $this->_options[] = array('value' => Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE, 'label' => Mage::helper('sales')->__('Capture Online'));
            $this->_options[] = array('value' => Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE, 'label' => Mage::helper('sales')->__('Capture Offline'));
            $this->_options[] = array('value' => Mage_Sales_Model_Order_Invoice::NOT_CAPTURE, 'label' => Mage::helper('sales')->__('Not Capture'));
        }
        return $this->_options;
    }
}