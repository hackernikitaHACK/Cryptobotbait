<?php
 
return [
   // Add you bot's API key and name
   'api_key'      => '7264254614:AAHDmPST65S3iUJqMyrhZwcPcgjx6y9X5ks',
   'bot_username' => '@reversotype_bot', // Without "@"
 
   // When using the getUpdates method, this can be commented out
   'webhook'      => [
       'url' => 'https://reversotype.static.domains/hook.php',
   ],
 
   'apirone_secret' => 'secret',
   'apirone_callback' => 'http://reversotype.static.domains/callback.php',
   // All command related configs go here
   'commands'     => [
       // Define all paths for your custom commands
       'paths'   => [
           __DIR__ . '/Commands',
       ],
 
   // Define all IDs of admin users
   'admins'       => [
       6249649479,
   ],
 
   // Enter your MySQL database credentials
   'mysql'        => [
       'host'     => 't90995sp.beget.tech',
       'user'     => 't90995sp_fig',
       'password' => '1234567890Aa',
       'database' => 't90995sp_fig',
   ],
 
   //Logging (Debug, Error and Raw Updates)
   'logging'  => [
       'debug'  => __DIR__ . '/php-telegram-bot-debug.log',
       'error'  => __DIR__ . '/php-telegram-bot-error.log',
       'update' => __DIR__ . '/php-telegram-bot-update.log',
   ],
 
   // Set custom Upload and Download paths
   'paths'        => [
       'download' => __DIR__ . '/Download',
       'upload'   => __DIR__ . '/Upload',
   ],
 
   // Requests Limiter (tries to prevent reaching Telegram API limits)
   'limiter'      => [
       'enabled' => true,
   ],
];