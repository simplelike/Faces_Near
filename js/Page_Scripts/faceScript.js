let data

var queryDict = {}
location.search.substr(1).split("&").forEach(function (item) {
    queryDict[item.split("=")[0]] = item.split("=")[1]
})
let id = queryDict.id
let number = parseInt(id) + 1

localForageHandler(_start)

function _start(v) {
    data = v[id]
    setImageToDiv("/previewData/maxPreview/" + number + ".png", data.g_h)
    setUrlPath(data.g_h)
    setNumber(data.nbr)
    setRarity(data.rrt)
    setOwnerInfoContentDiv()

    setContentForAttrComponent("jeweleryAttrComponent", "Украшение", data.j_a.t_c, data.j_a.T)
    setContentForAttrComponent("backgroundAttrComponent", "Фон", data.b_a.t_c, data.b_a.T)
    setContentForAttrComponent("maskAttrComponent", "Маска", data.m_a.t_c, data.m_a.T)
    setContentForAttrComponent("hatAttrComponent", "Шапка", data.h_a.t_c, data.h_a.T)
    setContentForAttrComponent("shirtAttrComponent", "Футболка", data.s_a.t_c, data.s_a.T)
    setContentForAttrComponent("lipsAttrComponent", "Губы", data.l_a.t_c, data.l_a.T)
    setContentForAttrComponent("eyesAttrComponent", "Глаза", data.e_a.t_c, data.e_a.T)
    setContentForAttrComponent("faceAttrComponent", "Лицо", data.f_a.t_c, data.f_a.T)

    $('.preloader').fadeOut().end().delay(400).fadeOut('slow')

    setListOfOffers()
    setListOfDemands()
}

function setOwnerInfoContentDiv() {

    doesTokenBelongsToContractAcc(id).then(
        r => {
            switch (r) {
                case true: {
                    setOwnerInfoContentDivForNoOnesToken()
                    break
                }
                default: {
                    setOwnerInfoContentDivForOwnersToken()
                    break
                }
            }
        }
    )

    function setOwnerInfoContentDivForNoOnesToken() {
        $("#noOnesTokenInfo").append(button("green", "Станьте первым владельцем токена", () => {
            if (wallet.isSignedIn()) {
                nftGetTokenForFree(id).then(
                    result => {
                        console.log(result)
                    }
                )
            }
            else {
                signIn()
            }
        }))
    }

    function setOwnerInfoContentDivForOwnersToken() {
        getOwnerOfToken(id).then(
            owner => {
                setCurrentOwner(owner)
            },
            error => console.log("err")
        )
        setFirstOwner("Получить из контракта")
    }
}



function setListOfOffers() {

    getOfferForTokenId(id).then(
        offer => {
            setOffersForTokenIdTable(offer)
            fillControlPanelOfOfferData()
        },
        error => {
            console.log(error)
        }
    )

    function setOffersForTokenIdTable(offer) {
        let table = $("#offerTable tbody")
        if (offer === null) {
            add_table_tr_to(table, "Пока нет предложений", "-")
        }
        else {
            let sailer = offer.sailer
            let price = convert_sum(offer.price)
            let price_el = price_elem(price)
            add_table_tr_to(table, sailer, price_el)
        }
    }

    function fillControlPanelOfOfferData() {
        getOwnerOfToken(id).then(
            res => {
                if (res === logged_user) {
                    $("#controlOfferPanel").show()
                    $("#controlOfferPanel").append(button(
                        "green",
                        "Сделать предложение",
                        function () {
                            if ($("#nearOfferValueInput").val() !== "") {
                                makeOffer(id, $("#nearOfferValueInput").val()).then(
                                    result => { alert("wow") },
                                    error => { console.log(error)}
                                )
                            }
                        },
                        "class:btn open-popup#attr:data-id=popup_default"))
                }
            }
        )
    }
}

function setListOfDemands() {
    getInfoOfDemandsForToken(id).then(
        demands => {
            console.log(demands)
            fillControlPanelOfDemandsData(demands)
        }
    )

    function fillControlPanelOfDemandsData(demands) {
        if (wallet.isSignedIn()) {
            getOwnerOfToken(id).then(
                res => {
                    if (res != logged_user) {
                        $("#controlDemandPanel").show()
                        $("#controlDemandPanel").append(button(
                            "green",
                            "Сделать предложение",
                            function () {
                                if ($("#nearDemandValueInput").val() !== "") {
                                    makeDemandForBuyingToken(id, $("#nearDemandValueInput").val()).then(
                                        result => { alert("wow") }
                                    )
                                }
                            },
                            "class:btn open-popup#attr:data-id=popup_default"))
                        
                        setDemandsInfoContentTable(demands, false)
                    }
                }
            )  
        }
    }

    function setDemandsInfoContentTable(demands, areUserOwnerOfToken = false) {

        let table = $("#demandTable tbody")
        if (isEmpty(demands)) {
            add_table_tr_to(table, "Пока нет предложений", "-")
        }
        else {
            for (let [_, element] of demands.entries()) {
                let buyer = element.buyer_acc
                let price = convert_sum(element.price)
                let price_el = price_elem(price)
                if (!areUserOwnerOfToken) {
                    if (wallet.getAccountId() === buyer) {
                        add_table_tr_to(table, buyer, price_el, "owner:self", function() {
                            alert("here1111")
                        })
                    }
                    else {
                        add_table_tr_to(table, buyer, price_el, "color:green#title:Ответить")
                    }
                }
            }

        }
    }
}

function setContentForAttrComponent(id, title, count, choosenElementTitle) {
    let element = $('#' + id)
    //let titleForInput = value[0].replace(".png", "")
    element.append(
        `<div class = "row" class = "padding">
                <div class = "col typeStyle">
                    ${title.replace(".png", "")}
                </div>
                <div class = "col componentTitle">
                   ${count.replace(".png", "")} Супремов
                </div>
        </div>
        <div class="row">
            <div class="col totalCountStr">
                ${choosenElementTitle.replace(".png", "")}
            </div>
        </div>`
    )
}
const make_demand_button = () => {
    return '<a href="#" class = "buyButton button" onClick = "javascript:tooggleDemandInputView()"> Предложить </a>'
}

function setNumber(number) {
    $('#numberDiv').append("№ " + number.toString());
}

function setRarity(rarity) {
    $('#rarityDiv').append(`Редкость - ${create_green_data_elem(rarity)}`);
}

function setCurrentOwner(currentOwner) {
    console.log(currentOwner)
    $('#currentOwner').append(`Текущий владелец: ${create_green_data_elem(currentOwner)}`);
}

function setFirstOwner(firstOwner) {
    $('#firstOwner').append(`Первый владелец: ${set_red_data_elem(firstOwner)}`);
}

function setTotalPrice(totalPrice) {
    $('#totalpriceDiv').append(`${create_green_data_elem(totalPrice)} ${near_logo}`)
}
function showMakeDemandButton() {
    $('#makeDemandButtonDiv').append(button("green", "Предложить цену", () => {
        tooggleDemandInputView()
    }))
}

function setImageToDiv(src, hash) {
    let ipfUrl = hash;
    $('#imgDiv').append(`<a href = "${ipfUrl}"><img src = "${src}"></a>`)
}

function setUrlPath(hash) {
    let ipfUrl = "https://ipfs.io/ipfs/" + hash;
    $('#linkDiv').append(`<a href = "${ipfUrl}">Ссылка на оригинал</a>`)
}

function isHidden(el) {
    var style = window.getComputedStyle(el);
    return (style.display === 'none')
}

function tooggleDemandInputView() {
    if (!isHidden(document.getElementById("makeDemandInput"))) {
        alert($("#make_demand_input").val())
    }
    $('#makeDemandInput').toggle();
}
