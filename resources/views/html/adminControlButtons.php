<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:33
 */
//qrButton + emailButton
/*
* share via qr code button
*/

$eventForView = ['event' => $event];

$qrButton = view('html.qrButton', $eventForView)->render();

/*
 * share via email button
 */

$emailButton = view('html.emailButton', $eventForView)->render();

/*
* event edit
*/
$eventEditButton = view('html.eventEditButton', $eventForView)->render();

$adminControlButtons = <<<EOT
<button class="btn btn-info btn-sm"
    type="button"
     data-toggle="modal"
     data-target="#qrCode">
<span class="glyphicon glyphicon-qrcode" aria-hidden = "true" ></span> QR
</button>
<button class="btn btn-info btn-sm"
        type="button"
        data-toggle="modal"
        data-target="#share_via_mail">
        <span class="glyphicon glyphicon-gift" aria-hidden = "true" ></span> INVITE
</button>
<button class="btn btn-info btn-sm"
        type="button"
         data-toggle="modal"
         data-target="#event_edit_modal">
<span class="glyphicon glyphicon-edit" aria-hidden = "true" ></span> EDIT
</button>

EOT;

echo $qrButton . $emailButton . $eventEditButton . $adminControlButtons;