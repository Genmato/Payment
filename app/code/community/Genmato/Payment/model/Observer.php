<?php
/**
 * @category    Genmato
 * @package     Genmato_Payment
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */

class Genmato_Payment_model_Observer
{

    public function autoCreateInvoice($observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        $createInvoice = Mage::getStoreConfigFlag('payment/' . $order->getPayment()->getMethod() . '/create_invoice');

        if ($order->getId() && $order->canInvoice() && $createInvoice) {

            $emailInvoice = Mage::getStoreConfigFlag('payment/' . $order->getPayment()->getMethod() . '/email_invoice');
            $capture_case = Mage::getStoreConfig('payment/' . $order->getPayment()->getMethod() . '/invoice_status');

            $invoice = $order->prepareInvoice();
            $invoice->setRequestedCaptureCase($capture_case);
            $invoice->register();

            Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();

            try {
                $invoice->sendEmail($emailInvoice);
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(Mage::helper('genmato_payment')->__('Unable to send the invoice email.'));
            }

            $order->addStatusToHistory($order->getStatus(), Mage::helper('genmato_payment')->__('Invoice created!'));
        }
    }
}