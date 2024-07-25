protected $name = 'balance';

   protected $description = 'Show balance of wallet';

   protected $usage = '/balance';

   protected $version = '1.2.0';



$message = $this->getMessage();

       $text    = $message->getText(true);

       $chat    = $message->getChat();

       $user    = $message->getFrom();

       $chat_id = $chat->getId();

       $user_id = $user->getId();

  	 $sql = '

       	SELECT *

       	FROM `apirone_currencies`

    	 ';

     $pdo = DB::getPdo();

     $sth = $pdo->prepare($sql);

     $sth->execute();

     $result = $sth->fetchAll(PDO::FETCH_ASSOC);

     foreach ($result as $currency) {

       if (stripos($text, $currency['name']) === 8) {

           $response = new ApironeWallet;

    		$response->getAddress($chat_id,$user_id,$currency['name']);

           return Request::emptyResponse();

       }

     }

     if ($text === 'Balance' || $text ==='') {

       $response = new ApironeWallet;

       $response->getBalance($chat_id, $user_id);

   }

 

   return Request::emptyResponse();
