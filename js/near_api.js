const contract_id = 'supremes_nft.testnet'

const market_contract = "market.supremes_nft.testnet"


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
                  'nft_tokens_id_for_acc',
                  'get_link_to_data',
                  'get_hash_of_data',],

    changeMethods:  ['nft_batch_mint',
                    'nft_mint',
                    'make_demand_for_buying_token',
                    'nft_get_token_for_free',
                    'remove_demand_for_buying_token',
                    'nft_approve',
                    'nft_revoke'
                    ],
});

$(window).load(function () {

    let signInButton = $("#wallet_register_button")
    let signInTextElement = $("#wallet_register_text")

    if (wallet.isSignedIn()) {
        let textForSignInButton = "Вы вошли как "
        signInTextElement.html(textForSignInButton + wallet.getAccountId())

        signInButton.html("Выйти")
    }
    else {
        signInTextElement.html('Войти в систему');
        signInButton.html("Войти")
    }

    signInButton.click(function () {

        if (wallet.isSignedIn()) {
            wallet.signOut();
            location.reload();
        }
        else {
            signIn();
        }
    })
})

const provider = new nearApi.providers.JsonRpcProvider("https://rpc.testnet.near.org");

const logged_user = wallet.getAccountId()

function signIn() {
    wallet.requestSignIn({
        contractId: contract_id,
        methodNames: [
            'nft_batch_mint',
            'nft_mint',
            'make_demand_for_buying_token',
            'nft_get_token_for_free',
            'remove_demand_for_buying_token',
            'nft_approve',
            'nft_revoke'
        ]
    });
}