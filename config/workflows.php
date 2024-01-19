<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Styling
    |--------------------------------------------------------------------------
    |
    | To easily integrate the Workflow frontend to your Style you can set your layout and the section.
    |
    */
    'layout' => 'workflows::layouts.workflow_app',
    'section' => 'content',

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Tasks which should be used in the Workflow Package. You can also deactivate Tasks
    | just by deleting them here.
    |
    */
    'tasks' => [
        'Start' => the42coders\Workflows\Tasks\Start::class,
        'Stop' => the42coders\Workflows\Tasks\Stop::class,
        // 'TextNode' => the42coders\Workflows\Tasks\SendMail::class,
        // 'Transition' => the42coders\Workflows\Tasks\Execute::class,
        'EvaluateContent' => the42coders\Workflows\Tasks\PregReplace::class,
        'SendNotification' => the42coders\Workflows\Tasks\HtmlInput::class,
        'UpdateContent' => the42coders\Workflows\Tasks\DomPDF::class,
        'UserAction' => the42coders\Workflows\Tasks\HttpStatus::class,
        // 'LoadModel' => the42coders\Workflows\Tasks\LoadModel::class,
        // 'ChangeModel' => the42coders\Workflows\Tasks\ChangeModel::class,
        // 'SaveModel' => the42coders\Workflows\Tasks\SaveModel::class,
        // 'SendSlackMessage' => the42coders\Workflows\Tasks\SendSlackMessage::class,
        // 'TextInput' => the42coders\Workflows\Tasks\TextInput::class,
    ],

    'task_settings' => [
        'LoadModel' => [
            'classes' => [
                \App\Models\User::class => 'User',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Resources
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Data Resources which should be used in the Workflow Package. You can also
    | deactivate Data Resources just by deleting them here.
    |
    */
    'data_resources' => [
        'ValueResource' => the42coders\Workflows\DataBuses\ValueResource::class,
        'ModelResource' => the42coders\Workflows\DataBuses\ModelResource::class,
        'DataResource' => the42coders\Workflows\DataBuses\DataBusResource::class,
        'ConfigResource' => the42coders\Workflows\DataBuses\ConfigResource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Triggers
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Triggers which should be used in the Workflow Package. You can also
    | deactivate Triggers just by deleting them here.
    |
    | Observers
    |
    | Events:
    | You can register all the events the Trigger should listen to here.
    |
    | Classes:
    | You can register the Classes which can be used for the ObserverTrigger.
    |
    */
    'triggers' => [

        'types' => [
            // 'Start' => the42coders\Workflows\Triggers\Start::class,
            // 'Stop' => the42coders\Workflows\Triggers\Stop::class,
        ],

        'Observers' => [
            'events' => [
                'retrieved',
                'creating',
                'created',
                'updating',
                'updated',
                'saving',
                'saved',
                'deleting',
                'deleted',
                'restoring',
                'restored',
                'forceDeleted',
            ],
            'classes' => [
                \App\Models\User::class => 'User',
                \the42coders\Workflows\Loggers\WorkflowLog::class => 'WorkflowLog',
            ],
        ],
        'Button' => [
            'classes' => [
                \App\Models\User::class => 'User',
            ],
            'categories' => [
                'all' => 'All',
            ],
        ],

    ],
    'queue' => 'redis',

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Configure if the package should load it's default routes. Default its not using the default routes. We recommend
    | using them as described in the Documentation because you should put a Auth middleware on them.
    */
    'prefix' => 'workflows',

    /*
    |--------------------------------------------------------------------------
    | Database prefixing
    |--------------------------------------------------------------------------
    |
    | We know how annoying it can be if a package brings a table name into your system which you are even worse another
    | package all ready uses. With the db_prefix you can set a prefix to the tables to avoid this conflict.
    | This changes needs to be done before the Migrations are running.
    */
    'db_prefix' => '',

];
