<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <ul id="greetings"></ul>
  <textarea id="text" placeholder="Add Message"></textarea>
  <button id="add-text">Add Text</button>
  <script src="https://cdn.jsdelivr.net/npm/near-api-js@0.41.0/dist/near-api-js.min.js"></script>
  <script>
    // connect to NEAR
    const near = new nearApi.Near({
      keyStore: new nearApi.keyStores.BrowserLocalStorageKeyStore(),
      networkId: 'testnet',
      nodeUrl: 'https://rpc.testnet.near.org',
      walletUrl: 'https://wallet.testnet.near.org'
    });
    
    // connect to the NEAR Wallet
    const wallet = new nearApi.WalletConnection(near, 'my-app');

    // connect to a NEAR smart contract
    const contract = new nearApi.Contract(wallet.account(), 'dev-1633942638836-45891669614397', {
      viewMethods: ['get_greeting'],
      changeMethods: ['set_greeting']
    });

    const button = document.getElementById('add-text');
    if (!wallet.isSignedIn()) {
      button.textContent = 'SignIn with NEAR'
    }

    //call the getMessages view method
    contract.get_greeting()
      .then(greetings => {
        const ul = document.getElementById('greetings');
        console.log(greetings);
        greetings.forEach(greeting => {
          const li = document.createElement('li');
          li.textContent = `${greeting}`;
          ul.appendChild(li);
        })
      });

    // Either sign in or call the addMessage change method on button click
    document.getElementById('add-text').addEventListener('click', () => {
      if (wallet.isSignedIn()) {
        contract.set_greeting({
          args: { message: document.getElementById('text').value }
        })
      } else {
        wallet.requestSignIn({
          contractId: 'facesgenerator.testnet',
          methodNames: ['get_greeting', 'set_greeting']
        });
      }
    });
  </script>
</body>

</html>