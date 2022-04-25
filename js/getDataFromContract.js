async function getCountOfTokensForAcc(account_id) {
    return new Promise((resolve, reject) => {
        let _result = contract.nft_supply_for_owner({
            account_id: account_id,
        })
        resolve(_result)
        reject("error")
    })
}

async function getListOfAllSupremesForAcc(account_id, from, limit) {
    return new Promise((resolve, reject) => {
        let _result = contract.nft_tokens_for_owner({
            account_id: account_id,
            from_index: from,
            limit: limit,
        })
        resolve(_result)
        reject("error")
    })
}

async function getInfoOfDemandsForToken(token_id) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_list_of_demands_for",
        args_base64: Buffer.from(JSON.stringify({
            token_id: token_id,
        })).toString('base64'),
        finality: "optimistic",
    });
    const res = JSON.parse(Buffer.from(rawResult.result).toString())
    return res
}

async function v_setListOfPersonalSupremesForAcc(arrOfSupremes) {
    console.log(arrOfSupremes)
    for (let [_, element] of arrOfSupremes.entries()) {
        let token_id = parseInt(element.token_id)
        let _element = supreme_mid_elem(token_id, "", "")
        $("#allSupremes_content").append(_element)
        /*await getInfoOfDemandsForToken(element.token_id).then(
            result => {
                let max_bid = find_max_bid_in(result)
                let _element = supreme_mid_elem(token_id, max_bid, "600")
                $("#allSupremes_content").append(_element)
            }
        )*/
    }
}


async function getCountOfOffersForAcc(account_id) {
    console.log(logged_user)
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_count_of_offers_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getListOfOffersForAcc(account_id, from, limit) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_list_of_offers_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id,
            start_index: from,
            limit: limit
        })).toString('base64'),
        finality: "optimistic",
    });
    const res = JSON.parse(Buffer.from(rawResult.result).toString())
    return res
}

async function getCountOfDemandsForAcc(account_id) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_count_of_demands_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getListOfDemandsForAcc(account_id, from, limit) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_list_of_demands_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id,
            start_index: from,
            limit: limit,
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

function find_max_bid_in(arr) {
    let max_bid = 0
    for (let [_, element] of arr.entries()) {
        let nbr = number_from_scientific_notation(element.price)
        if (max_bid < nbr) {
            max_bid = nbr
        }
    }
    return max_bid
}

async function getListOfTokensForAcc(account_id) {
    return new Promise((resolve, reject) => {
        let _result = contract.nft_tokens_id_for_acc({
            account_id: account_id,
        })
        resolve(_result)
        reject("error")
    })
}

async function getCountOfDemandsForTokensList(tokensArr) {
    let obj = {
        list_of_tokens_id: tokensArr
    }
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_count_of_demands_for_tokens_list",
        args_base64: Buffer.from(JSON.stringify({
            supremes_json: JSON.stringify(obj),
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getSumOfBidsOnDemandsForTokensList(tokensArr) {
    let obj = {
        list_of_tokens_id: tokensArr
    }
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_sum_of_bids_on_demands_for_tokens_list",
        args_base64: Buffer.from(JSON.stringify({
            supremes_json: JSON.stringify(obj),
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getSumOfBidsOnDemandsForAcc(account_id) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_sum_of_bids_on_demands_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id,
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getListOfDemandsForListOfTokenIds(tokensArr) {
    let obj = {
        list_of_tokens_id: tokensArr
    }
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_list_of_demands_for_list_of_token_ids",
        args_base64: Buffer.from(JSON.stringify({
            token_ids: JSON.stringify(obj),
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

async function getSumOfBidsOnOffersForAcc(account_id) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_sum_of_bids_on_offers_for_acc",
        args_base64: Buffer.from(JSON.stringify({
            account_id: account_id,
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());

    return res
}

////Face Page functions

async function getOwnerOfToken(token_id) {
    return new Promise((resolve, reject) => {
        let _result = contract.nft_get_owner_for_token({
            token_id: token_id,
        })
        resolve(_result)
        reject("error")
    })
}

async function getOfferForTokenId(token_id) {
    const rawResult = await provider.query({
        request_type: "call_function",
        account_id: market_contract,
        method_name: "get_offer_for_token_id",
        args_base64: Buffer.from(JSON.stringify({
            token_id: token_id,
        })).toString('base64'),
        finality: "optimistic",
    });

    const res = JSON.parse(Buffer.from(rawResult.result).toString());
    return res
}

async function doesTokenBelongsToContractAcc(token_id) {
    return new Promise((resolve, reject) => {
        let _result = contract.does_token_belongs_to_contract_acc({
            token_id: token_id,
        })
        resolve(_result)
        reject("error")
    })
}

async function nftGetTokenForFree(token_id) {
    return new Promise((resolve, reject) => {
        let _result = contract.nft_get_token_for_free(
            {
                token_id: token_id,
            },
            "300000000000000", // attached GAS (optional)
            "1" // attached deposit in yoctoNEAR (optional)
            )
        resolve(_result)
        reject("error")
    })
}

async function makeDemandForBuyingToken(token_id, deposit) {
    return new Promise((resolve, reject) => {
        let _result = contract.make_demand_for_buying_token(
            {
                token_id: token_id,
            },
            "300000000000000",
            deposit
            )
        resolve(_result)
        reject("error")
    })
}