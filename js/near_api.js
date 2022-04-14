/*const contract_id = 'fg10.testnet'

// connect to NEAR
const near = new nearApi.Near({
    keyStore: new nearApi.keyStores.BrowserLocalStorageKeyStore(),
    networkId: 'testnet',
    nodeUrl: 'https://rpc.testnet.near.org',
    walletUrl: 'https://wallet.testnet.near.org'
});

const wallet = new nearApi.WalletConnection(near, contract_id);

// const contract = new nearApi.Contract(wallet.account(), contract_id, {
//     viewMethods: ['get_link_to_data', 'get_hash_of_data'],
//     changeMethods: [],
// });

const contract = new nearApi.Contract(wallet.account(), contract_id, {
    viewMethods: ['nft_token', 'nft_metadata', 'get_link_to_data', 'get_hash_of_data'],
    changeMethods: ['nft_batch_mint', 'make_demand_for_buying_token', 'remove_demand_for_buying_token'],
});

$(window).load(function () {

    let signInButton = $("#signInButton")

    if (wallet.isSignedIn()) {
        let textForSignInButton = "Вы вошли как "
        signInButton.html(textForSignInButton + wallet.getAccountId())
    }
    else {
        signInButton.html('Войти в систему');
    }

    signInButton.click(function () {

        if (wallet.isSignedIn()) {
            wallet.signOut();
            signInButton.html('Войти в систему');
        }
        else {
            wallet.requestSignIn({
                contractId: contract_id,
                methodNames: ['make_demand_for_buying_token, nft_batch_mint, remove_demand_for_buying_token']
            });
            let textForSignInButton = "Вы вошли как "
            signInButton.html(textForSignInButton + wallet.getAccountId())
        }
    });
})
*/

const contract_id = 'fg10.testnet'
// connect to NEAR
const near = new nearApi.Near({
    keyStore: new nearApi.keyStores.BrowserLocalStorageKeyStore(),
    networkId: 'testnet',
    nodeUrl: 'https://rpc.testnet.near.org',
    walletUrl: 'https://wallet.testnet.near.org'
});

// create wallet connection
const wallet = new nearApi.WalletConnection(near, contract_id);

const contract = new nearApi.Contract(wallet.account(), contract_id, {
    viewMethods: ['nft_tokens', 'nft_metadata', 'get_link_to_data', 'get_hash_of_data'],
    changeMethods: ['nft_batch_mint', 'make_demand_for_buying_token', 'remove_demand_for_buying_token'],
});

$(window).load(function () {

    let signInButton = $("#signInButton")

    if (wallet.isSignedIn()) {
        let textForSignInButton = "Вы вошли как "
        signInButton.html(textForSignInButton + wallet.getAccountId())
    }
    else {
        signInButton.html('Войти в систему');
    }

    signInButton.click(function () {

        if (wallet.isSignedIn()) {
            wallet.signOut();
            signInButton.html('Войти в систему');
        }
        else {
            wallet.requestSignIn({
                contractId: contract_id,
                methodNames: ['nft_batch_mint, make_demand_for_buying_token, remove_demand_for_buying_token']
            });
            let textForSignInButton = "Вы вошли как "
            signInButton.html(textForSignInButton + wallet.getAccountId())
        }
    })
})

const provider = new nearApi.providers.JsonRpcProvider("https://rpc.testnet.near.org");

getState();

async function getState() {
  const rawResult = await provider.query({
    request_type: "call_function",
    account_id: "market.fg6.testnet",
    method_name: "get_list_of_demands",
    args_base64: "e30=",
    finality: "optimistic",
  });

  // format result
  const res = JSON.parse(Buffer.from(rawResult.result).toString());
  console.log(res);
}