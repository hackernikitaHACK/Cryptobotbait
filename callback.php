require_once __DIR__ . '/vendor/autoload.php';
use Longman\TelegramBot\Request;
use Classes\Apirone\ApironeWallet;
 
$config = require __DIR__ . '/config.php';
 
$telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);
$apirone = new ApironeWallet;
$telegram->enableMySql($config['mysql']);;
 
$apironeData = file_get_contents('php://input');
 
if ($apironeData) {
   $params = json_decode($apironeData, true);
 
   // check your secret code
   if ($params["data"]["secret"] !== $config['apirone_secret']) die();
 
   $user_id = $params["data"]["user_id"];
   $input_address = $params["input_address"];
   $input_transaction_hash = $params["input_transaction_hash"];
   $value_in_satoshi = $params["value"];
 
   $wallets = $apirone->getWallets($user_id);
 
   foreach ($wallets as $wallet) {
      if($wallet['wallet_id'] === $params['wallet']) {
       $currency = $apirone->getCurrencyById($wallet['currency'], true);
      }
   }
 
   //Save unconfirmed transactions and data to your Database.
       $data['chat_id'] = $user_id;
       $data['parse_mode'] = 'markdown';
   if ($params["confirmations"] < 2 ) {
       $data['text'] = '*'.strtoupper($currency['name']) .' wallet*: '. PHP_EOL .
       "Transaction ". $input_transaction_hash . PHP_EOL.
       'Waiting *'. number_format($value_in_satoshi/pow(10, $currency['units-factor']), 8, '.', ''). strtoupper($currency['name']) .'*'. PHP_EOL .
       $params["confirmations"].' of 2 confirmations received'. PHP_EOL .
       $apirone->explorerUrl($currency['name'], $input_transaction_hash);  
   }
  
   if ($params["confirmations"] >= 2) {
       $balance = $apirone->getBalance($user_id, $user_id, $params['wallet']);
       $data['text'] = '*'.strtoupper($currency['name']) .' wallet*: '. PHP_EOL .
       "Transaction ". $input_transaction_hash . PHP_EOL.
       'Payment successfully received!'. PHP_EOL .'Amount: *'. number_format($value_in_satoshi/pow(10, $currency['units-factor']), 8, '.', ''). strtoupper($currency['name']) .'*'. PHP_EOL.
       'Current balance:'. PHP_EOL .
       $balance . PHP_EOL.
       $apirone->explorerUrl($currency['name'], $input_transaction_hash);
       echo "*ok*";
   }
   return Request::sendMessage($data);