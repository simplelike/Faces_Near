let localForageData

//MySupremesTab
let mySupremesTab_currentStartIndex = 0
let mySupremesTab_limit = 10
let countOfSupremes

$(window).load(function () {
    localForageHandler(start)
})


function start(v) {

    localForageData = v

    updateMySupremesTab()
    
}

async function downloadCountOfTokensForAcc (account_id) {
    return new Promise( (resolve, reject) => {
        let _result = contract.nft_supply_for_owner({
                account_id: account_id,
        })
        resolve(_result)
        reject("error")
    })
}

async function downloadSupremesForAcc (account_id, from_index, limit) {
    return new Promise( (resolve, reject) => {
        let _result = contract.nft_tokens_for_owner({
                account_id: account_id,
                from_index: from_index,
                limit: limit,
        })
        resolve(_result)
        reject("error")
    })
}

function updateMySupremesTab() {

    let v_setCountOfSupremes = (count) => {
        countOfSupremes = count
        $("#countOfSupremesSpan").text(count)
        $("#supremCount").text(count)
    }
    let v_setListOfPersonalSupremesForAcc = (arrOfSupremes) => {
        console.log(arrOfSupremes)
        for (let [_, element] of arrOfSupremes.entries()) {
            let token_id = parseInt(element.token_id)
            let _element =   `<div class="col-1">`+
                                `<a href = "/face.php?id=${token_id}">`+
                                    `<img src="/previewData/smallPreview/${token_id+1}.png"`+
                                `</a>`+
                            `<div>`
            $("#personalSupremes").append(_element)
        }
    }
    let v_setHandlerOnUploadButton = () => {
        $("#mySupremesTab_showmore").click( () => {
            mySupremesTab_currentStartIndex = mySupremesTab_currentStartIndex + mySupremesTab_limit
            downloadSupremesForAcc(contract_id,String(mySupremesTab_currentStartIndex), mySupremesTab_limit).then(
                result => v_setListOfPersonalSupremesForAcc(result),
                error => console.log(error)
            )
        })
    }

    downloadCountOfTokensForAcc(contract_id).then(
        result => v_setCountOfSupremes(result),
        error => console.log(error)
    )
    downloadSupremesForAcc(contract_id, String(mySupremesTab_currentStartIndex), mySupremesTab_limit).then(
        result => v_setListOfPersonalSupremesForAcc(result),
        error => console.log(error)
    )
    v_setHandlerOnUploadButton()
}