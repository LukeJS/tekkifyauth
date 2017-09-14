<?php

return [

    /*
     * A secret that must be sent with the verification code to authorise the request.
     */

    'secret' => env('VERIFICATION_SECRET'),

    /*
     * Length of the verification code.
     */

    'code_length' => env('VERIFICATION_CODE_LENGTH', 10),

    /*
     * Expiry time of the verification code in minutes.
     */

    'code_expiry' => env('VERIFICATION_CODE_EXPIRY', 10),

];
