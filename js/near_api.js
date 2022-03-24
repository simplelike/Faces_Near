const contract_id = 'fg8.testnet'

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
    changeMethods: ['nft_mint', 'nft_batch_mint'],
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
                methodNames: ['nft_batch_mint']
            });
            let textForSignInButton = "Вы вошли как "
            signInButton.html(textForSignInButton + wallet.getAccountId())
        }
    });
})



