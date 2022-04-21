//MySupremesTab
let allSupremesTab_currentStartIndex = 0
let allSupremesTab_limit = 10
let countOfSupremes

//Offers
let myOffersTab_currentStartIndex = 0
let myOffersTab_limit = 10
let countOfMySupremesOnSaleTab

//Demands
let myDeamndsTab_currentStartIndex = 0
let myDeamndsTab_limit = 10
let countOfMyDemandsTab

$(window).load(function () {
    fillContent()
})


function fillContent() {

    //Получаем и устанавливаем количество всех супремов для зарегистрированного пользователя
    getCountOfTokensForAcc(logged_user).then(
        result => {
            countOfSupremes = result
            $("#allSupremes_count").text(countOfSupremes)
            $("#supremCount").text(countOfSupremes)
        },
        error => console.log(error)
    )

    //Заполняем поля "Предложения на моих Супремов:" и "Сумма ставок на моих Супремов:"
        //Сначала давай получим id твоих супремов
    getListOfTokensForAcc(logged_user).then(
        result => {
            //Затем над результатом запустим функции получения demand-ов для этих токенов
            getCountOfDemandsForTokensList(result).then(
                result => {
                    $("#supremOffer").text(result)
                    $("#demandsOnMySupremes_count").text(result)
                },
                error => console.log(error)
            )
            //А также посчитаем сумму ставок по demand на эти токены
            getSumOfBidsOnDemandsForTokensList(result).then(
                result => {
                    setPriceTo($("#supremBetSum"), result)
                    setPriceTo($("#demandsOnMySupremes_price"), result)
                },
                error => console.log(error)
            )
            //А также заполним таб "Предложения на моих Супремов:"
            getListOfDemandsForListOfTokenIds(result).then(
                result => {
                    let tab = $("#demandsOnMySupremes_content")
                    for (let [_, element] of result.entries()) {
                        let token_id = Number(element[0])
                        let count = Number(element[1])
                        
                        let _element = supreme_mid_elem(token_id, "", `<div>Всего: ${count}</div>`)
                    
                        tab.append(_element)
                    }
                },
                error => {
                    console.log(error)
                }
            )
        },
        error => {
            console.log(error)
        }
    )  

    //Заполняем поле "Сумма моих предложений:"
    getSumOfBidsOnDemandsForAcc(logged_user).then(
        result => {
            setPriceTo($("#myDemandsBetSum"), result)
            setPriceTo($("#myDemands_price"), result)
        },
        error => console.log(error)
    )

    //Получаем и заполняем список элементов всех супремов для зарегистрированного пользователя
    getListOfAllSupremesForAcc(logged_user, String(allSupremesTab_currentStartIndex), allSupremesTab_limit).then(
        result => {
            let button = $("#allSupremes_showMore")
            v_setListOfPersonalSupremesForAcc(result)
            //Ставим обработчик нажатий для кнопки "Загрузить еще"
            setHandlerOn(
                button, 
                function () {
                    allSupremesTab_currentStartIndex = allSupremesTab_currentStartIndex + allSupremesTab_limit
                    getListOfAllSupremesForAcc(logged_user, String(allSupremesTab_currentStartIndex), allSupremesTab_limit).then(
                        result => v_setListOfPersonalSupremesForAcc(result),
                        error => console.log(error))
                }
            )
        },
        error => console.log(error)
    )

    //Получаем и устанавливаем список всех супремов на продаже для зарегестрированного пользователя

    //Получаем и устанавливаем количество супремов на продаже для зарегистрированного пользователя
    getCountOfOffersForAcc(logged_user).then(
        res => {
            $("#offersTab_count").text(parseInt(res))
        },
        error => console.log(error)
    )
    //Получаем и заполняем список всех супремов на продаже для зарегистрированного пользователя    
    getListOfOffersForAcc(logged_user, myOffersTab_currentStartIndex, myOffersTab_limit).then(
        res => {
                let tab = $("#offersTab_content")
                let button = $("#offersTab_showmore")
                fillTabOfSupremesWithData(tab, res)
                setHandlerOn(
                    button, 
                    function () {
                        myOffersTab_currentStartIndex = myOffersTab_currentStartIndex + myOffersTab_limit
                        getListOfOffersForAcc(logged_user, myOffersTab_currentStartIndex, myOffersTab_limit).then(
                            result => fillTabOfSupremesWithData(tab, result),
                            error => console.log(error))
                    }
                )
        },
        error => {
            console.log("err")
        }
    )

    //Получаем и устанавливаем сумму по выставленным офферам
    getSumOfBidsOnOffersForAcc(logged_user).then(
        res => {
            setPriceTo($("#offersTabs_price"), res)
        },
        error => console.log(error)
    )

    //Получаем и устанавливаем количество предложений на другие супремы для зарегистрированного пользователя
    getCountOfDemandsForAcc(logged_user).then(
        res => {
            $("#myDemands_count").text(parseInt(res))
            $("#myDemandsOnTokens").text(parseInt(res))
        },
        error => console.log(error)
    )
    //Получаем и заполняем список всех предложений на другие супремы для зарегистрированного пользователя    
    getListOfDemandsForAcc(logged_user).then(
        res => {
                let tab = $("#myDemands_content")
                let button = $("#myDemands_showMore")
                fillTabOfSupremesWithData(tab,res)
                setHandlerOn(
                    button, 
                    function () {
                        myDeamndsTab_currentStartIndex = myDeamndsTab_currentStartIndex + myDeamndsTab_limit
                        getListOfDemandsForAcc(logged_user, myDeamndsTab_currentStartIndex, myDeamndsTab_limit).then(
                            result => fillTabOfSupremesWithData(tab,result),
                            error => console.log(error))
                    }
                )
                
        },
        error => {
            console.log("err")
        }
    )

}

function setHandlerOn(button, handler) {
    button.click( () => {
        handler()
    })
}

function fillTabOfSupremesWithData(tab,res) {
    for (let [_, element] of res.entries()) {
        let token_id = Number(element[0])
        let price = number_from_scientific_notation(element[1])
        let _element = supreme_mid_elem(token_id, price)
    
        tab.append(_element)
    }
}

let supreme_mid_elem = (token_id, price_near ="", additionalData = "") => {
    let pic_id = token_id + 1.0
    let price_near_frmt = ""
    let price_near_data = ""
    let price_dollar_data = ""
    if (price_near !== "")
    {
        price_near_frmt = convert_sum(price_near)
        price_near_data =  `<div class="centerContent" style=" padding-top: 10px; ">` +
                                `<b>${price_near_frmt}</b> ${near_logo}` +
                            `</div>`
        price_dollar_data = `${set_green_data_elem("($" + convertNearToUSD(price_near_frmt) + ")")}`
        
    }

    return `<div class="col-1">` +
                `<a href = "/face.php?id=${token_id}">` +
                    `<img src="/previewData/smallPreview/${pic_id}.png"` +
                `</a>` +
                `${price_near_data}` +
                `${price_dollar_data}` +
                `${additionalData}` +
            `<div>`
}