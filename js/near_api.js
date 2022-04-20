const contract_id = 'fg10.testnet'

const market_contract = "market.fg6.testnet"


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
    viewMethods: ['nft_tokens',
                  'nft_metadata',
                  'nft_tokens_for_owner',
                  'nft_supply_for_owner',
                  'nft_get_owner_for_token',
                  'get_offer_for_token_id',
                  'does_token_belongs_to_contract_acc',
                  'get_link_to_data',
                  'get_hash_of_data'],

    changeMethods: ['nft_batch_mint', 'make_demand_for_buying_token', 'nft_get_token_for_free', 'remove_demand_for_buying_token'],
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
            signIn();
            let textForSignInButton = "Вы вошли как "
            signInButton.html(textForSignInButton + wallet.getAccountId())
        }
    })
})

const provider = new nearApi.providers.JsonRpcProvider("https://rpc.testnet.near.org");

// getState();

// async function getState() {
//   const rawResult = await provider.query({
//     request_type: "call_function",
//     account_id: "market.fg6.testnet",
//     method_name: "get_list_of_demands",
//     args_base64: "e30=",
//     finality: "optimistic",
//   });

//   // format result
//   const res = JSON.parse(Buffer.from(rawResult.result).toString());
//   console.log(res);
// }

const logged_user = wallet.getAccountId()

function signIn() {
    wallet.requestSignIn({
        contractId: contract_id,
        methodNames: ['nft_batch_mint', 'make_demand_for_buying_token', 'nft_get_token_for_free', 'remove_demand_for_buying_token']
    });
}

function number_from_scientific_notation(number) {
    return number.toLocaleString('fullwide', { useGrouping: false })
}