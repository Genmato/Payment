<?php

/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_Payment_Model_System_Customer_Group
{

    protected $options;

    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = Mage::getResourceModel('customer/group_collection')
                ->setRealGroupsFilter()
                ->loadData()->toOptionArray();
            array_unshift(
                $this->options,
                array(
                    'value' => 0,
                    'label' => Mage::helper('customer')->__('NOT LOGGED IN')
                )
            );
        }
        return $this->options;
    }
}