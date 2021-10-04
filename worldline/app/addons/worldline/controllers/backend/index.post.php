<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;


	$offlineURL = fn_url("worldline.offline", AREA, 'current');
    $refundURL = fn_url("worldline.refund", AREA, 'current');
	$reconcileURL = fn_url("worldline.reconcile", AREA, 'current');

    Registry::get('view')->assign('refundURL', $refundURL);
    Registry::get('view')->assign('offlineURL', $offlineURL);
    Registry::get('view')->assign('reconcileURL', $reconcileURL);
