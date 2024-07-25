// Fetch conversation command if it exists and execute it.

       if ($conversation->exists() && $command = $conversation->getCommand()) {

           return $this->telegram->executeCommand($command);

       }

      

       if($type === "text") {

           $text = $message->getText(true);

           if (stripos($text, 'Receive') === 0) {

               return $this->telegram->executeCommand('receive');

           }

           if (stripos($text, 'Menu') === 0) {

               return $this->telegram->executeCommand('menu');

           }

 

           if (stripos($text, 'Balance') === 0) {

               return $this->telegram->executeCommand('balance');

           }

           if (stripos($text, 'Send') === 0) {

               return $this->telegram->executeCommand('send');

           }

           return $this->replyToChat(

               'Command not found'

           );

       }
