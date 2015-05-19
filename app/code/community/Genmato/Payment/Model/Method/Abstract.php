<?php

/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2014 Genmato BV (http://genmato.com)
 */
class Genmato_Payment_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract
{
    protected $_canCapture = true;
    protected $_canCapturePartial = true;

    public function isAvailable($quote = null)
    {
        // Skip checks if quote is null
        if (is_null($quote)) {
            return parent::isAvailable($quote);
        }

        // Check for minimal order amount
        $min_order_amount = $this->getConfig('min_order_amount');
        if (!is_numeric($min_order_amount)) {
            $min_order_amount = 0;
        }
        if ($min_order_amount > 0
            && (Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) <
                Mage::app()->getStore()->roundPrice($min_order_amount))
        ) {
            return false;
        }

        // Check for maximum order amount
        $max_order_amount = $this->getConfig('max_order_amount');
        if (!is_numeric($max_order_amount)) {
            $max_order_amount = 0;
        }
        if ($max_order_amount > 0 &&
            (Mage::app()->getStore()->roundPrice($quote->getGrandTotal()) >=
                Mage::app()->getStore()->roundPrice($max_order_amount))
        ) {
            return false;
        }

        // Check if customer is in allowed group
        $allowed_groups = explode(',', $this->getConfig('allow_group'));
        if (!in_array($quote->getCustomerGroupId(), $allowed_groups)) {
            return false;
        }

        // Check if shipping method is allowed
        $allowed_shipping_method = explode(',', $this->getConfig('allow_shipping_method'));
        $active_shipping_method = $quote->getShippingAddress()->getShippingMethod();
        if (count($allowed_shipping_method) > 0 && !in_array($active_shipping_method, $allowed_shipping_method)) {
            return false;
        }

        return parent::isAvailable($quote);
    }

    /**
     * Get Payment configuration node
     *
     * @param $node
     *
     * @return mixed
     */
    protected function getConfig($node)
    {
        return Mage::getStoreConfig('payment/' . $this->_code . '/' . $node);
    }
}