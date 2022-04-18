const market_contract = "market.fg6.testnet"
const near_logo = "<img style='width: 20px;' src='/sources/nearCircleLogo.png'>"

let localForageData
//MySupremesTab
let mySupremesTab_currentStartIndex = 0
let mySupremesTab_limit = 10
let countOfSupremes

//MySupremesOnSalesTab
let mySupremesOnSaleTab_currentStartIndex = 0
let mySupremesOnSaleTab_limit = 10
let countOfMySupremesOnSaleTab

$(window).load(function () {
    //Получаем локальный дамп данных и после получения его вызываем все остальные функции заполнения контента
    localForageHandler(fillContent)
})


function fillContent(v) {
    localForageData = v

    showCountOfMySupremes()
    showListOfMySupremes()

    showCountOfMySupremesOnSale()
    showListOfMySupremesOnSale()

}
function showCountOfMySupremes() {

    async function downloadCountOfTokensForAcc(account_id) {
        return new Promise((resolve, reject) => {
            let _result = contract.nft_supply_for_owner({
                account_id: account_id,
            })
            resolve(_result)
            reject("error")
        })
    }

    downloadCountOfTokensForAcc(contract_id).then(
        result => {
            countOfSupremes = result
            $("#countOfSupremesSpan").text(countOfSupremes)
            $("#supremCount").text(countOfSupremes)
        },
        error => console.log(error)
    )
}

function showListOfMySupremes() {

    async function downloadSupremesForAcc(account_id, from_index, limit) {
        return new Promise((resolve, reject) => {
            let _result = contract.nft_tokens_for_owner({
                account_id: account_id,
                from_index: from_index,
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
            await getInfoOfDemandsForToken(element.token_id).then(
                result => {
                    let max_bid = find_max_bid_in(result)
                    let _element = supreme_mid_elem(token_id, max_bid, "600")
                    $("#personalSupremes").append(_element)
                }
            )
        }
    }

    function mySupremesTab_setHandlerOnUploadButton() {
        $("#mySupremesTab_showmore").click(() => {
            mySupremesTab_currentStartIndex = mySupremesTab_currentStartIndex + mySupremesTab_limit
            downloadSupremesForAcc(contract_id, String(mySupremesTab_currentStartIndex), mySupremesTab_limit).then(
                result => v_setListOfPersonalSupremesForAcc(result),
                error => console.log(error)
            )
        })
    }

    downloadSupremesForAcc(contract_id, String(mySupremesTab_currentStartIndex), mySupremesTab_limit).then(
        result => { 
            v_setListOfPersonalSupremesForAcc(result)
            mySupremesTab_setHandlerOnUploadButton()
        },
        error => console.log(error)
    )

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
    
}

function showCountOfMySupremesOnSale() {

    async function getCountOfOffersForAcc() {
        const rawResult = await provider.query({
            request_type: "call_function",
            account_id: market_contract,
            method_name: "get_count_of_offers_for_acc",
            args_base64: Buffer.from(JSON.stringify({
                account_id: "fg10.testnet"
            })).toString('base64'),
            finality: "optimistic",
        });

        const res = JSON.parse(Buffer.from(rawResult.result).toString());

        $("#numberOfMySupremsForSale").text(parseInt(res))
    }

    getCountOfOffersForAcc()
}

function showListOfMySupremesOnSale() {

    async function getListOfOffersForAcc() {
        const rawResult = await provider.query({
            request_type: "call_function",
            account_id: market_contract,
            method_name: "get_list_of_offers_for_acc",
            args_base64: Buffer.from(JSON.stringify({
                account_id: "fg10.testnet",
                fillContent_index: mySupremesOnSaleTab_currentStartIndex,
                limit: mySupremesOnSaleTab_limit
            })).toString('base64'),
            finality: "optimistic",
        });
        const res = JSON.parse(Buffer.from(rawResult.result).toString())
        return res
    }

    function mySupremesOnSaleTab_setListOfSupremes(res) {
        for (let [_, element] of res.entries()) {
            let token_id = Number(element[0])
            let price = number_from_scientific_notation(element[1])
            let _element = supreme_mid_elem(token_id, price, "600")
            $("#personalSupremesOnSale").append(_element)
        }
    }

    let mySupremesOnSaleTab_setHandlerOnUploadButton = () => {
        $("#personalSupremesOnSaleTab_showmore").click(() => {
            mySupremesOnSaleTab_currentStartIndex = mySupremesOnSaleTab_currentStartIndex + mySupremesOnSaleTab_limit
            getListOfOffersForAcc().then(
                result => mySupremesOnSaleTab_setListOfSupremes(result),
                error => console.log(error)
            )
        })
    }

    getListOfOffersForAcc().then(
        res => {
                mySupremesOnSaleTab_setListOfSupremes(res)
                mySupremesOnSaleTab_setHandlerOnUploadButton()
        },
        error => {
            console.log("err")
        }
    )

}

let supreme_mid_elem = (token_id, price_near, price_dollar) => {
    let pic_id = token_id + 1.0
    return `<div class="col-1">` +
                `<a href = "/face.php?id=${token_id}">` +
                    `<img src="/previewData/smallPreview/${pic_id}.png"` +
                `</a>` +
                `<div class="centerContent" style=" padding-top: 10px; ">` +
                    `<b>${nearApi.utils.format.formatNearAmount(price_near)}</b> ${near_logo}` +
                `</div>` +
                `<div class="greenColor centerContent">` +
                    `($${price_dollar})` +
                `</div>` +
            `<div>`
}

function number_from_scientific_notation(number) {
    return number.toLocaleString('fullwide', { useGrouping: false })
}