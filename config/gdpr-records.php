<?php

return [

    'table' => 'gdpr_processing_activities',

    /*
     * The controller, as it appears in the records of processing (Art. 30(1)(a)) and the privacy notice (Art. 13(1)(a)).
     */
    'controller' => [
        'name' => env('GDPR_CONTROLLER_NAME', env('APP_NAME')),
        'address' => env('GDPR_CONTROLLER_ADDRESS'),
        'contact' => env('GDPR_CONTROLLER_CONTACT'),
    ],

    /*
     * Representative in the Union, for controllers not established in the EU (Art. 27).
     */
    'representative' => [
        'name' => env('GDPR_REPRESENTATIVE_NAME'),
        'contact' => env('GDPR_REPRESENTATIVE_CONTACT'),
    ],

    /*
     * The data protection officer. Publish their contact details (Art. 37(7)).
     */
    'dpo' => [
        'name' => env('GDPR_DPO_NAME'),
        'email' => env('GDPR_DPO_EMAIL'),
        'phone' => env('GDPR_DPO_PHONE'),
    ],

    /*
     * The supervisory authority data subjects can complain to (Art. 13(2)(d)).
     */
    'supervisory_authority' => [
        'name' => env('GDPR_SUPERVISORY_AUTHORITY'),
        'url' => env('GDPR_SUPERVISORY_AUTHORITY_URL'),
    ],

];
