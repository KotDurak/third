<?php

return
    [
        'db' => [

            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=third',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',

            // Schema cache options (for production environment)
            //'enableSchemaCache' => true,
            //'schemaCacheDuration' => 60,
            //'schemaCache' => 'cache',
        ]
    ];
