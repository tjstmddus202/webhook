<?php

// get the webhook response
$body = @file_get_contents('php://input');

// decode the json data into a php object
$response = json_decode($body);

// the webhook property tells us exactly which webhook event was fired
// so let's create a case for a few webhooks
switch ($response->webhook)
{
    case 'sale_new':
        // Someone purchased your add-on, your code goes here
        SugarOutfittersHelper::sale_new($response);
        break;

    case 'case_created':
        // A support case was created, your code goes here
        SugarOutfittersHelper::case_created($response);
        break;

    case 'usercount_changed':
        // The number of users has changed for a customer's license key, your code goes here
        SugarOutfittersHelper::usercount_changed($response);
        break;

    case 'question_created':
        // A question has been asked about your add-on, your code goes here
        SugarOutfittersHelper::question_created($response);
        break;
}

class SugarOutfittersHelper
{
    public static function sale_new($response)
    {
        // get the data from the event
        // a new sale gives you the addon, lineitem, member and licensekey objects related to the purchase
        $addon = $response->data->addon; // the addon that was purchased
        $lineitem = $response->data->lineitem; // we give you the lineitem because you may have multiple purchase plans for an add-on
        $member = $response->data->member; // your new customer!
        $licensekey = $response->data->licensekey; // if you're using SugarOutfitters lincense keys, the details of the license key are listed here

        // write whatever logic you need to kick off a new sale (below are made up methods...)
        alert_billing_of_new_purchase($addon->name, $lineitem->plan_name, $member->name, $member->email);
        start_new_customer_onboarding_process();
    }

    public static function case_created($response)
    {
        // get the data from the event
        // a case created event
        $case = $repsonse->data->case;
        $member = $response->data->member;
        $addon = $response->data->addon;

        // write whatever you want to occur when a new case is created (below are made up methods...)
        create_new_case_in_sugarcrm($case->id,$case->subject,$case->description,$member->email);
        send_case_to_jira($case->id,$case->subject,$case->description,$member->email);
    }

    public static function usercount_changed($response){}
    public static function question_created($response){}
}
