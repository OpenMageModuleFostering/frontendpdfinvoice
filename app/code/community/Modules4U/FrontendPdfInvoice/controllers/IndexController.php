<?php
/**
 * Copyright (C) 2016 modules4u.biz
 * All Rights Reserved
 *
 * NOTICE OF LICENSE
 * Permitted Use
 * One license grants the right to perform one installation of the Software. Each additional installation of the Software requires an additional purchased license,
 * please contact us at magento@modules4u.biz for details.
 *
 * Restrictions
 * It is not allowed to:
 * Reproduce, distribute, or transfer the Software, or portions thereof, to any third party.
 * Use the Software in violation of any international law or regulation.
 * Display of Copyright Notices
 * All copyright and proprietary notices and logos within the Software files must remain intact.

 *
 * Indemnity
 * You agree to indemnify and hold harmless Modules4U for any third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the Software, or a violation of any terms of this license.
 *
 * Disclaimer Of Warranty
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, Modules4U DOES NOT WARRANT THAT THE SOFTWARE OR ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.
 * 
 * Limitations Of Liability
 * YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
 *
 * You can download the full license text here: http://modules4u.biz/shop/license/
 *
 * @author      Modules4U
 */

class Modules4U_FrontendPdfInvoice_IndexController extends Mage_Core_Controller_Front_Action 
{        
 
    public function downloadInvoiceAction() 
    {
        $orderId = (int) $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);
 
        if ($this->_isAllowed($order)) 
        {
            $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                    ->setOrderFilter($order->getId())
                    ->load();
            if ($invoices->getSize() > 0) 
            {
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
 
                return $this->_prepareDownloadResponse
                (
                    'invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
 
            }
        }
    }
 
    protected function _isAllowed($order)
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $availableStates = Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates();
        if (
        	$order->getId() && $order->getCustomerId() && ($order->getCustomerId() == $customerId)
            && in_array($order->getState(), $availableStates, $strict = true)
            ) 
            {
            	return true;
        	}
        	return false;
    }
 }
 
?>