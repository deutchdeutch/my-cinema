<?php
// Si la variable d'environnement existe (sur Railway), on l'utilise, sinon localhost
return [
    'db_host' => getenv('MYSQLHOST') ?: 'switchyard.proxy.rlwy.net',
    'db_port' => getenv('MYSQLPORT') ?: '56087',
    'db_name' => getenv('MYSQLDATABASE') ?: 'railway',
    'db_user' => getenv('MYSQLUSER') ?: 'root',
    'db_pass' => getenv('MYSQLPASSWORD') ?: 'tpBtMEEBRuSQuPolHEoIhlKVSmTjIdvp',
];