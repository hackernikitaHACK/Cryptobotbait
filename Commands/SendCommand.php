protected $name = 'Send';
   protected $description = 'sends the amount of crypto from your wallet';
   protected $usage = '/send';
   protected $version = '1.2.0';

$this->conversation = new Conversation($user_id, $chat_id, $this->getName());
 
       // Load any existing notes from this conversation
       $notes = &$this->conversation->notes;
…
     // Every time a step is achieved the state is updated
 
       if($text === 'Cancel') {
           $state = 4;
           $notes['answer'] = 'Cancel';
       }
       if($text === 'Send') {
           $text = '';
       }
       switch ($state) {
           case 0:
               $currencies = $apirone->getAvailableCurrencies();
               if ($text === '' || !in_array(strtoupper($text), $currencies, true)) {
                  ...
                   $data['reply_markup'] = (new Keyboard(
                   [$currencies[0],$currencies[1]],
                   [$currencies[2],$currencies[3],$currencies[4]],
                   ['Cancel']))
                           ->setResizeKeyboard(true)
                           ->setOneTimeKeyboard(true)
                           ->setSelective(true);
              
                   $result = Request::sendMessage($data);
                   break;
               }
               $notes['currency'] = strtolower($text);
               $text          = '';
            case 1:
               if ($text === ''|| !$apirone->checkAddress($notes['currency'], $text)) {
                   $notes['state'] = 1;
                   $this->conversation->update();
 
                   $data['text'] = 'Type address for transfer:';
                   if ($text !== '') {
              ...
                   break;
               }
 
               $notes['address'] = $text;
               $text          = '';
 
           // No break!
           case 2:
                  …
           case 3:
               if ($text === '' || !in_array($text, ['Ok', 'Cancel'], true)) {
                   $notes['state'] = 3;
                   $this->conversation->update();
                   $currency = $apirone->getCurrencyId($notes['currency'], true);
                   $estimate = $apirone->estimate($user_id, $notes['currency'], $notes['address'], $notes['amount']);
                   if(isset($estimate['message'])) {
                       $data['text'] = 'Message from Apirone:'. PHP_EOL. '*'.$estimate['message'].'*' .PHP_EOL. 'Operation cancelled.';
                       $data['parse_mode'] = 'markdown';
                       $data['reply_markup'] = (new Keyboard(['Receive', 'Send'],
                       ['Balance']))
                           ->setResizeKeyboard(true)
                           ->setOneTimeKeyboard(true)
                           ->setSelective(true);
                       $this->conversation->stop();
                   } else {
                   $data['reply_markup'] = (new Keyboard(['Ok', 'Cancel']))
                       ->setResizeKeyboard(true)
                       ->setOneTimeKeyboard(true)
                       ->setSelective(true);
                   $data['text'] = 'Please double check that all data correct. Send "Ok" message in answer. If you want to stop sending type "Cancel"'. PHP_EOL;
...
                   if ($text !== '') {
                       $data['text'] = 'Simply type Ok or Cancel.';
                   }
                   }
                   $result = Request::sendMessage($data);
                   break;
               }
               $notes['answer'] = $text;
 
           // No break!
           case 4:
               $this->conversation->update();
               unset($notes['state']);
               if($notes['answer'] === 'Ok') {
                   $transfer = $apirone->transfer($user_id, $notes['currency'], $notes['address'], $notes['amount']);
 
                   if(isset($transfer['message'])) {
                       $data['text'] = 'Message from Apirone:'. PHP_EOL. '*'.$transfer['message'].'*' .PHP_EOL. 'Operation cancelled.';
                    ...
                       $this->conversation->stop();
                   } else {
                       $data['text'] = 'Transfer successfully complete.'.PHP_EOL.
                       'Transactions:'. PHP_EOL ;
                       foreach ($transfer['txs'] as $tx) {
                           $data['text'].= $tx .PHP_EOL. $apirone->explorerUrl($notes['currency'], $tx).PHP_EOL;
                       };
                   }
               } 
...
               $result = Request::sendMessage($data);
               $this->conversation->stop();
               break;
       }
       return $result;