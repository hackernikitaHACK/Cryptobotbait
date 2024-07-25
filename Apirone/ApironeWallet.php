getWallets($user_id, $currencyId = null) //получить кошельки для telegram Пользователя

addWallet($user_id, $wallet, $address) //add generated wallet into DB

postWallet($user_id, $currency) //get wallet from Apirone

createWallets($user_id) //create all available wallets

getCurrencyId($currency,$units = null) //get currency id from DB

getAddress($chat_id, $user_id, $currency) //outputs wallet from DB

getAvailableCurrencies() //collect available currencies from DB

getCurrencyById($id, $units = null) // return currency by Id

getBalance($chat_id, $user_id, $wallet_id = null) // get wallet or wallets balance

checkAddress($currency, $address) // validate crypto address

estimate($user_id, $currencyName, $address, $amount) // pre-calculation of crypto transaction

transfer($user_id, $currencyName, $address, $amount) // transfer funds

explorerUrl($currency, $tx) // get explorer link
