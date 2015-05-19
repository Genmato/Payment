<?php

/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_Payment_model_Observer
{

    /**
     * Check to auto create order invoice and invoice e-mail
     *
     * @param Varien_Event_Observer $observer
     */
    public function autoCreateInvoice(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        $method = $order->getPayment()->getMethod();

        $createInvoice = Mage::getStoreConfigFlag('payment/' . $method . '/create_invoice');

        if ($order->getId() && $order->canInvoice() && $createInvoice) {

            $emailInvoice = Mage::getStoreConfigFlag('payment/' . $method . '/email_invoice');
            $capture_case = Mage::getStoreConfig('payment/' . $method . '/invoice_status');

            $invoice = $order->prepareInvoice();
            $invoice->setRequestedCaptureCase($capture_case);
            $invoice->register();

            $transaction = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());

            try {
                $transaction->save();
            } catch (Exceopption $ex) {
                Mage::log($ex);
                $this->_getSession()->addError(
                    Mage::helper('genmato_payment')->__('Unable to create invoice.')
                );
                return;
            }

            try {
                $invoice->sendEmail($emailInvoice);
            } catch (Exception $ex) {
                Mage::log($ex);
                $this->_getSession()->addError(
                    Mage::helper('genmato_payment')->__('Unable to send invoice email.')
                );
            }

            $order->addStatusToHistory($order->getStatus(), Mage::helper('genmato_payment')->__('Invoice created!'));
        }
    }
}