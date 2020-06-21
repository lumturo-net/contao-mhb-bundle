<?php
namespace Lumturo\ContaoMhbBundle\Controller;

use mhb_events\Mhb_Infotag_Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HitController extends Controller
{
    public function notifycreditcardAction()
    {
        $strEnv = Mhb_Infotag_Payment::getEnvironment();
        $arrConfig = $GLOBALS['TL_CONFIG']['mhb_events']['giropay']['creditcard'][$strEnv];
        return $this->processNotification($arrConfig);
    }

    public function notifypaypalAction()
    {
        $strEnv = Mhb_Infotag_Payment::getEnvironment();
        $arrConfig = $GLOBALS['TL_CONFIG']['mhb_events']['giropay']['paypal'][$strEnv];
        return $this->processNotification($arrConfig);
    }

    public function notifygiropayAction()
    {
        $strEnv = Mhb_Infotag_Payment::getEnvironment();
        $arrConfig = $GLOBALS['TL_CONFIG']['mhb_events']['giropay']['giropay'][$strEnv];
        return $this->processNotification($arrConfig);
    }

    public function notifydebitAction()
    {
        $strEnv = Mhb_Infotag_Payment::getEnvironment();
        $arrConfig = $GLOBALS['TL_CONFIG']['mhb_events']['giropay']['debit'][$strEnv];
        return $this->processNotification($arrConfig);
    }

    public function notifypaydirektAction()
    {
        $strEnv = Mhb_Infotag_Payment::getEnvironment();
        $arrConfig = $GLOBALS['TL_CONFIG']['mhb_events']['giropay']['paydirekt'][$strEnv];
        return $this->processNotification($arrConfig);
    }


    private function processNotification($arrConfig)
    {
        try
        {
            Mhb_Infotag_Payment::notification($arrConfig);
        } catch (\Exception $objException) {
            Mhb_Infotag_Payment::sendErrorMail($objException);
        }

        $objResponse = new Response('');
        return $objResponse;
    }
}
