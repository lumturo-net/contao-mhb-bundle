<?php
namespace Lumturo\ContaoMhbBundle\Controller;

use Contao\PageModel;
use mhb_events\EventApplicationsModel;
use mhb_events\EventsModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function applyAction()
    {
        $strUid = ArrGet($_GET, 'uid');
        if (!$strUid) {
            return $this->redirect('');
        }

        try
        {
            $objApplication = EventApplicationsModel::getByUid($strUid);
            if (!$objApplication) {
                $this->redirect('');
            }
            $TL_CONFIG = $GLOBALS['TL_CONFIG'];
            $intErrorPageId = $TL_CONFIG['mhb_event_application_double_opt_in_error_page'];
            $intSuccessPageId = $TL_CONFIG['mhb_event_application_double_opt_in_success_page'];
            $strLegalContent = $TL_CONFIG['mhb_event_application_mail_legal_content'];

            $objEvent = EventsModel::findByPk($objApplication->pid);
            $intContingent = (int) $objEvent->kontingent- EventApplicationsModel::getSum($objEvent->id);
            $objPage = PageModel::findByPk($intContingent - (int) $objApplication->anzahl >= 0 ? $intSuccessPageId : $intErrorPageId);

            if ($objApplication->acknowledged == 0) {
                // nur wenn noch nicht acknowledged, dann setzen / speichern
                $objApplication->acknowledged = time();
                $objApplication->save();
                // sende E-Mail an den Veranstalter
                sendMail($objEvent, $objApplication);
            }

            $strFrontendUrl = \Controller::generateFrontendUrl($objPage->row());
            return $this->redirect('/'. $strFrontendUrl);
        } catch (\Exception $e) {
            return $this->redirect('');
        }
    }
}
function ArrGet($array, $key, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

function sendMail($objEvent, $arrDbApplication)
{
   $objEmail = new \Email();
   $objEmail->charset = 'utf-8';
   $objEmail->subject = 'MHB | Events | Neue Anmeldung für ' . strip_tags(trim($objEvent->titel));
   $objEmail->from = 'itnachrichten.mhb@mhb-fontane.de';
   $objHtmlMailTemplate = new \Contao\FrontendTemplate('mhb_event_application_organizer_email');
   $objHtmlMailTemplate->event = $objEvent;
   $objHtmlMailTemplate->application = $arrDbApplication;
//    $objHtmlMailTemplate->job = $arrDbApplication;
   $objEmail->html = $objHtmlMailTemplate->parse();
   $objEmail->sendTo($objEvent->email);
}
