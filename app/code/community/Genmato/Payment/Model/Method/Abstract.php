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

        $orderAmount = Mage::app()->getStore()->roundPrice($quote->getGrandTotal());

        // Check for minimal order amount
        $minOrderAmount = $this->getConfig('min_order_amount');
        if (!is_numeric($minOrderAmount)) {
            $minOrderAmount = 0;
        }
        $minOrderAmount = Mage::app()->getStore()->roundPrice($minOrderAmount);
        if ($minOrderAmount > 0 && ($orderAmount < $minOrderAmount)) {
            Mage::helper('genmato_payment')->debug('Denied minimal order amount:'.$minOrderAmount, false, $orderAmount);
            return false;
        }

        // Check for maximum order amount
        $maxOrderAmount = $this->getConfig('max_order_amount');
        if (!is_numeric($maxOrderAmount)) {
            $maxOrderAmount = 0;
        }
        $maxOrderAmount = Mage::app()->getStore()->roundPrice($maxOrderAmount);
        if ($maxOrderAmount > 0 && ($orderAmount > $maxOrderAmount)) {
            Mage::helper('genmato_payment')->debug('Denied max order amount:'.$maxOrderAmount, false, $orderAmount);
            return false;
        }

        // Check if customer is in allowed group
        $allowedGroups = $this->getConfig('allow_group');
        if (!empty($allowedGroups) && !in_array($quote->getCustomerGroupId(), explode(',', $allowedGroups))) {
            Mage::helper('genmato_payment')
                ->debug('Denied cust. group:'.$quote->getCustomerGroupId(), false, $allowedGroups);
            return false;
        }

        // Check if shipping method is allowed
        $allowedShipMethod = $this->getConfig('allow_shipping_method');
        $activeShipMethod = $quote->getShippingAddress()->getShippingMethod();
        if (!empty($allowedShipMethod)  && !in_array($activeShipMethod, explode(',', $allowedShipMethod))) {
            Mage::helper('genmato_payment')
                ->debug('Denied Shipping method: '.$activeShipMethod, false, $allowedShipMethod);
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