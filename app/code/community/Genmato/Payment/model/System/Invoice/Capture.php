<?php

/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_Payment_Model_System_Invoice_Capture
{
    protected $options;

    /**
     * Return array of capture options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = array();
            $this->options[] = array(
                'value' => Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE,
                'label' => Mage::helper('sales')->__('Capture Online')
            );
            $this->options[] = array(
                'value' => Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE,
                'label' => Mage::helper('sales')->__('Capture Offline')
            );
            $this->options[] = array(
                'value' => Mage_Sales_Model_Order_Invoice::NOT_CAPTURE,
                'label' => Mage::helper('sales')->__('Not Capture')
            );
        }
        return $this->options;
    }
}