<?php
/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */

class Genmato_Payment_Model_System_Shipping_Carriers {

    /**
     * Return array of carriers.
     *
     * @return array
     */
    public function toOptionArray ()
    {
        $options = array (
            array (
                'value' => '',
                'label' => ''
            )
        );
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();
        foreach ($carriers as $carrierCode => $carrierModel) {
            $name = $carrierModel->getConfigData('name');
            $options[] = array (
                'label' => $name.' ('.$carrierCode.')',
                'value' => $carrierCode
            );
        }
        return $options;
    }
}