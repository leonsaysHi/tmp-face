<?php

/**
 * List of plain SQS queues and their corresponding handling classes
 */
return [
    'handlers' => [
        //'libraries' => App\Jobs\LibrariesJob::class, was not picking it up?
    ],

    'default-handler' => App\Jobs\PlainHandlerJob::class
];
