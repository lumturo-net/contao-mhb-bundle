<?php

namespace Lumturo\ContaoMhbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class PinController extends Controller
{
    private $strPinSource = '<?xml version="1.0" encoding="utf-8"?>
<svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="32px" y="48px" viewBox="0 0 1190.6 1190.6" style="enable-background:new 0 0 1190.6 1190.6;" xml:space="preserve" width="40" height="48">
<style type="text/css">
	.white {fill:#FFFFFF; }
	.layer_1 {fill:#C6C7C8;}
	.layer_2 {fill:#9C9E9F; }
	.layer_3 {fill:#707173; }
</style>
<path id="White_Background" class="white" d="M262,838c-46.1-45.3-81.9-98.4-106.3-157.7c-23.6-57.3-35.8-117.9-36-180.3
	c-0.3-63,11.6-124.6,35.2-182.9c24.4-60.4,60.4-114.6,107-161.2c45.6-46.4,99-82.3,158.7-106.7c57.7-23.6,118.7-35.6,181.4-35.6
	c62.7,0,123.8,12,181.8,35.6c60,24.4,114,60.3,160.3,106.7c91.8,91.8,142.3,212.9,142.3,341.1c0,128.2-50.5,249.3-142.3,341.1
	L603,1179L262,838z"/>
<path id="Color_Layer_1" class="layer_1" d="M916.9,183C742.2,8.2,460.8,8.2,289,183c-174.7,174.7-174.7,456.1,0,627.9L603,1124.8
	l313.9-313.9C1091.7,636.1,1091.7,357.7,916.9,183z M902.7,623.4c-16.4,38.7-40,73.5-69.9,103.4c-29.9,29.9-64.7,53.4-103.4,69.9
	c-40.1,17.1-82.7,25.7-126.4,25.7s-86.3-8.6-126.4-25.7c-38.7-16.4-73.5-40-103.4-69.9c-29.9-29.9-53.4-64.7-69.9-103.4
	c-17.1-40.1-25.7-82.7-25.7-126.4c0-43.8,8.6-86.3,25.7-126.4c16.4-38.7,40-73.5,69.9-103.4c29.9-29.9,64.7-53.4,103.4-69.9
	c40.1-17.1,82.7-25.7,126.4-25.7s86.3,8.6,126.4,25.7c38.7,16.4,73.5,40,103.4,69.9c29.9,29.9,53.4,64.7,69.9,103.4
	c17.1,40.1,25.7,82.7,25.7,126.4C928.4,540.7,919.8,583.2,902.7,623.4z"/>
<path id="Color_Layer_2" class="layer_2" d="M603,211.8c-156.8,0-285.1,128.3-285.1,285.1S446.2,782,603,782s285.1-128.3,285.1-285.1
	S759.8,211.8,603,211.8z M732.6,626.6c-34.9,34.9-80.9,54.1-129.7,54.1c-48.7,0-94.8-19.2-129.7-54.1
	c-34.9-34.9-54.1-80.9-54.1-129.7s19.2-94.8,54.1-129.7c34.9-34.9,80.9-54.1,129.7-54.1s94.8,19.2,129.7,54.1
	c34.9,34.9,54.1,80.9,54.1,129.7S767.5,591.7,732.6,626.6z"/>
<path id="Color_Layer_3" class="layer_3" d="M700.7,399.2c-26.3-26.3-61-40.7-97.7-40.7s-71.4,14.5-97.7,40.7
	c-26.3,26.3-40.7,61-40.7,97.7s14.5,71.4,40.7,97.7c26.3,26.3,61,40.7,97.7,40.7c36.7,0,71.4-14.5,97.7-40.7
	c26.3-26.3,40.7-61,40.7-97.7S727,425.5,700.7,399.2z"/>
</svg>';

    public function __construct()
    {

    }

    public function generateAction($color1 = '', $color2 = '', $color3 = '')
    {
        $strCheckPattern = '/[a-fA-F0-9]{6}/';
        if(!empty($color1) && !preg_match($strCheckPattern, $color1)) {
            throw new Exception('Invalid HEX value for color1');
        }

        if(!empty($color2) && !preg_match($strCheckPattern, $color2)) {
            throw new Exception('Invalid HEX value for color2');
        }

        if(!empty($color3) && !preg_match($strCheckPattern, $color3)) {
            throw new Exception('Invalid HEX value for color3');
        }

        $response = new Response;
        $response->headers->set('Content-type', 'image/svg+xml');

        $strResponseContent = str_replace(
            [
                'C6C7C8',
                '9C9E9F',
                '707173'
            ],
            [
                empty($color1) ? 'C6C7C8' : $color1,
                empty($color2) ? '9C9E9F' : $color2,
                empty($color3) ? '707173' : $color3
            ],
            $this->strPinSource
        );

        $response->setContent($strResponseContent);
        return $response;
    }
}
