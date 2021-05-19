<?php
namespace Lumturo\ContaoMhbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function applyAction()
    {
        $strUid = ArrGet($_GET, 'uid');
        if (!$strUid) {
            \Contao\Controller::redirect('');
        }

        try
        {
            $objApplication = \mhb_events\EventApplicationsModel::getByUid($strUid);
            if (!$objApplication) {
                \Contao\Controller::redirect('');
            }
            $TL_CONFIG = $GLOBALS['TL_CONFIG'];
            $intErrorPageId = $TL_CONFIG['mhb_event_application_double_opt_in_error_page'];
            $intSuccessPageId = $TL_CONFIG['mhb_event_application_double_opt_in_success_page'];
            $strLegalContent = $TL_CONFIG['mhb_event_application_mail_legal_content'];

            $objEvent = \mhb_events\EventsModel::findByPk($objApplication->pid);
            $intContingent = (int) $objEvent->kontingent-\mhb_events\EventApplicationsModel::getSum($objEvent->id);

            if ($intContingent - (int) $objApplication->anzahl >= 0) {
                $objPage = \Contao\PageModel::findByPk($intSuccessPageId);
                if ($objApplication->acknowledged == 0) {
                    // nur wenn noch nicht acknowledged, dann setzen / speichern
                    $objApplication->acknowledged = time();
                    $objApplication->save();
                    // sende E-Mail an den Veranstalter
                    sendMail($objEvent, $objApplication);
                }
            } else {
                $objPage = \Contao\PageModel::findByPk($intErrorPageId);
            }
            $strFrontendUrl = \Contao\Controller::generateFrontendUrl($objPage->row());
            \Contao\Controller::redirect($strFrontendUrl);
        } catch (\Exception $e) {
            \Contao\Controller::redirect('');
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
