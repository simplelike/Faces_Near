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

    getOwnerOfToken(id).then(
        owner => {
            if (owner !== null) {
                setCurrentOwner(owner)
                setFirstOwner("Получить из контракта")
            }
            else {
                setOwnerInfoContentDivForNoOnesToken()
            }
        },
        error => {
            showErrorMessage(error)
        }
    )

    function setOwnerInfoContentDivForNoOnesToken() {
        $("#noOnesTokenInfo").append(button("green", "Станьте первым владельцем токена", () => {
            if (wallet.isSignedIn()) {
                let element = localDump[id]
                nftMint(
                    id,
                    {
                        title: element["T"],
                        media: element["g_h"]
                    },
                    wallet.getAccountId()
                ).then(
                    result => {
                        console.log(result)
                    },
                    error => {
                        showErrorMessage(error)
                    }
                )
            }
            else {
                signIn()
            }
        }))
    }
}

function setListOfOffers() {

    getOfferForTokenId(id).then(
        offer => {
            console.log(offer)
            fillOffersDataWith(offer)
        },
        error => {
            showErrorMessage(error)
        }
    )

    function fillOffersDataWith(offer) {
        getOwnerOfToken(id).then(
            res => {
                if (res === logged_user) {

                    setOffersForTokenIdTable(offer, true)

                    $("#controlOfferPanel").show()
                    $("#controlOfferPanel").append(button(
                        "green",
                        "Сделать предложение",
                        function () {
                            if ($("#nearOfferValueInput").val() !== "") {
                                makeOffer(id, $("#nearOfferValueInput").val()).then(
                                    error => {
                                        showErrorMessage(error)
                                    }
                                )
                            }
                        }))
                }
                else {
                    setOffersForTokenIdTable(offer, false)
                    $("#controlOfferPanel").hide()
                }
            },
            error => {
                showErrorMessage(error)
            }
        )
    }

    function setOffersForTokenIdTable(offer, areUserOwnerOfToken = false) {
        let table = $("#offerTable tbody")
        if (offer === null) {
            add_table_tr_to(table, ["Пока нет предложений", "-"])
        }
        else {

            let sailer = offer.sailer
            let price = convert_sum(offer.price)
            let price_el = price_elem(price)
            let _button = ""

            if (wallet.isSignedIn()) {
                if (areUserOwnerOfToken) {
                    _button = button("red", "Удалить", () => {
                        remove_offer_id_for(id).then(
                            result => { console.log(result) },
                            error => {
                                showErrorMessage(error)
                            }
                        )
                    })
                }
                else {
                    _button = button("green", "Принять", () => {
                        $("#nearDemandValueInput").val(number_from_scientific_notation(offer.price))
                        scrollToAnchor("nearDemandValueInput")
                    })
                }
            }
            add_table_tr_to(table, [sailer, price_el, _button])
        }
    }
}

function setListOfDemands() {
    getInfoOfDemandsForToken(id).then(
        demands => {
            fillDemandsDataWith(demands)
        },
        error => {
            showErrorMessage(error)
        }
    )

    function fillDemandsDataWith(demands) {
        //Получим аккаунт кому принадлежит токен
        getOwnerOfToken(id).then(
            res => {
                //Если токен не принадлежит пользователю, 
                if (res != logged_user) {
                    //то настроим вид таблицы с предложениями о покупке, как для потенциального покупателя
                    setDemandsInfoContentTable(demands, false)
                    //И можем делать ставки на покупку токена
                    if (wallet.isSignedIn()) {
                        $("#controlDemandPanel").show()
                        $("#controlDemandPanel").append(button(
                            "green",
                            "Сделать предложение",
                            function () {
                                if ($("#nearDemandValueInput").val() !== "") {
                                    makeDemandForBuyingToken(id, $("#nearDemandValueInput").val()).then(
                                        error => {
                                            showErrorMessage(error)
                                        }
                                    )
                                }
                            }))
                    }

                }
                //А если пользователь владелец токена, то он должен иметь возможность принять любой demand
                //Значит настроим соответствующим образом вид таблицы с предложениями
                //И скроем возможность делать ставки на покупку
                else {
                    setDemandsInfoContentTable(demands, true)
                    $("#controlDemandPanel").hide()
                }
            },
            error => {
                showErrorMessage(error)
            }
        )
    }

    function setDemandsInfoContentTable(demands, areUserOwnerOfToken = false) {

        let table = $("#demandTable tbody")
        if (isEmpty(demands)) {
            add_table_tr_to(table, ["Пока нет предложений", "-"])
        }
        else {
            for (let [_, element] of demands.entries()) {
                console.log(element)
                let buyer = element.buyer_acc
                let price = convert_sum(element.price)
                let price_el = price_elem(price)
                let _button = ""

                if (wallet.isSignedIn()) {
                    if (!areUserOwnerOfToken) {
                        if (wallet.getAccountId() === buyer) {
                            _button = button("red", "Удалить", () => {
                                remove_demand_id(element.demand_id).then(
                                    error => {
                                        showErrorMessage(error)
                                    }
                                )
                            })
                        }
                        else {
                            _button = button("green", "Ответить", () => {
                                $("#nearDemandValueInput").val(number_from_scientific_notation(element.price))
                                scrollToAnchor("nearDemandValueInput")
                            })
                        }
                    }
                    else {
                        _button = button("green", "Принять", () => {
                            let _price = nearApi.utils.format.parseNearAmount(number_from_scientific_notation(price))
                            makeOffer(id, _price).then(
                                error => {
                                    showErrorMessage(error)
                                }
                            )
                        })
                    }
                }
                add_table_tr_to(table, [buyer, price_el, _button])
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
