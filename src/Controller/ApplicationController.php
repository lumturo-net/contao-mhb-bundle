<?php
namespace Lumturo\ContaoMhbBundle\Controller;

use mhb_events\Mhb_Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function notifypaydirektAction()
    {
        $arrConfig = Mhb_Payment::getConfig('paydirekt');
        return $this->processNotification($arrConfig);
    }

    public function notifypaypalAction()
    {
        $arrConfig = Mhb_Payment::getConfig('paypal');
        return $this->processNotification($arrConfig);
    }

    public function notifycreditcardAction()
    {
        $arrConfig = Mhb_Payment::getConfig('creditcard');
        return $this->processNotification($arrConfig);
    }

    public function notifygiropayAction()
    {
        $arrConfig = Mhb_Payment::getConfig('giropay');
        return $this->processNotification($arrConfig);
    }

    public function notifydebitAction()
    {
        $arrConfig = Mhb_Payment::getConfig('debit');
        return $this->processNotification($arrConfig);
    }

    private function processNotification($arrConfig)
    {
        try
        {
            Mhb_Payment::notification($arrConfig);
        } catch (\Exception $objException) {
            Mhb_Payment::sendErrorMail($objException);
        }
        $objResponse = new Response('');
        return $objResponse;
    }
}
