<?php
$txt_howtopay = '<p>&bull; We will ship your order ASAP after we confirm your name & address by phone.</p>';

if ($payment_cmd == 'form') {
    // no hidden field as it is not required
    $form['pay_redirect_to_gateway'] = false;
    $form['txt_howtopay'] = $txt_howtopay;
    $form['hidden'] = '';
}
