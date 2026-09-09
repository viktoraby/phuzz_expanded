<?php
/***
*
*Found actions: 1
*Found functions:1
*Extracted functions:1
*Total parameter names extracted: 1
*Overview: {'rzpInstrumentation': {'rzpInstrumentation'}}
*
***/

/** Function rzpInstrumentation() called by wp_ajax hooks: {'rzpInstrumentation'} **/
/** Parameters found in function rzpInstrumentation(): {"post": ["properties", "event"]} **/
function rzpInstrumentation()
{
    $paymentSettings = get_option('woocommerce_razorpay_settings');

    if ($paymentSettings === false)
    {
        return;
    }

    $api = new Api($paymentSettings['key_id'], $paymentSettings['key_secret']);

    $trackObject = new TrackPluginInstrumentation($api, $paymentSettings['key_id']);
    $properties = $_POST['properties'];

    if ($_POST['event'] === "signup.initiated" or
        $_POST['event'] === "login.initiated")
    {
        if (empty($paymentSettings['key_id']) === false and
            empty($paymentSettings['key_secret']) === false)
        {
            $properties['is_plugin_merchant'] = true;
            $properties['is_registered_on_razorpay'] = true;
        }
        else
        {
            $properties['is_plugin_merchant'] = false;
            $properties['is_registered_on_razorpay'] = false;
        }
    }

    $trackObject->rzpTrackSegment($_POST['event'], $properties);
    $trackObject->rzpTrackDataLake($_POST['event'], $properties);

    wp_die();
}


