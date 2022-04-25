let data

var queryDict = {}
location.search.substr(1).split("&").forEach(function (item) {
    queryDict[item.split("=")[0]] = item.split("=")[1]
})
let id = queryDict.id
let number = parseInt(id) + 1

localForageHandler(_start)

const button = (color, text, handler) => {
    let color_class;
    switch (color) {
        case "red":
            color_class = "red"    
        break
        case "green":
            color_class = "green" 
            break
        default:
            color_class = "yellow" 
            break;

    }
    let button = document.createElement('button')
    $(button).addClass(color_class)
            .text(text).click(
                () => {
                    handler()
                }
            )
    return button
}

function _start(v) {
    data = v[id]
    setImageToDiv("/previewData/maxPreview/" + number + ".png", data.g_h)
    setUrlPath(data.g_h)
    setNumber(data.nbr)
    setRarity(data.rrt)
    setOwnerInfoContentDiv()

    setListOfOffers()
    //setListOfDemands()


    /*setDemandsInfoContentTable()

    getInfoOfDemandsForToken(id).then(
        result => {
            setDemandsInfoContentTable(result)
        }
    )*/

    setContentForAttrComponent("jeweleryAttrComponent", "Украшение", data.j_a.t_c, data.j_a.T)
    setContentForAttrComponent("backgroundAttrComponent", "Фон", data.b_a.t_c, data.b_a.T)
    setContentForAttrComponent("maskAttrComponent", "Маска", data.m_a.t_c, data.m_a.T)
    setContentForAttrComponent("hatAttrComponent", "Шапка", data.h_a.t_c, data.h_a.T)
    setContentForAttrComponent("shirtAttrComponent", "Футболка", data.s_a.t_c, data.s_a.T)
    setContentForAttrComponent("lipsAttrComponent", "Губы", data.l_a.t_c, data.l_a.T)
    setContentForAttrComponent("eyesAttrComponent", "Глаза", data.e_a.t_c, data.e_a.T)
    setContentForAttrComponent("faceAttrComponent", "Лицо", data.f_a.t_c, data.f_a.T)

    $('.preloader').fadeOut().end().delay(400).fadeOut('slow')
}

function setOwnerInfoContentDiv() {
    doesTokenBelongsToContractAcc(id).then(
        r => {
            switch(r) {
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
}

function setOwnerInfoContentDivForNoOnesToken() {
    //$("#ownerInfoContent").html("")
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
        result => {
            setCurrentOwner(result)
        },
        error => console.log("err")
    )

    setFirstOwner("Получить из контракта")
    
    /*getOfferForTokenId(id).then(
        result => {
            let price = result == null ? "Пока нет предложений о продаже" : nearApi.utils.format.formatNearAmount(number_from_scientific_notation(result.price))
            setTotalPrice(price)
            // if (result.sailer !== logged_user) {
            //     showMakeDemandButton()
            // }
        },
        error => console.log(error)
    )*/
}

function setListOfOffers() {
    getOfferForTokenId(id).then(
        offer => {
            setOffersForTokenIdTable(offer)
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
}

function setDemandsInfoContentTable(data) {
    let tr = ""
    if (data === null || data === undefined || data === "") return

    $("#demandsData").attr("display", "block");
    for (let [_, element] of data.entries()) {
        let tr = `<tr><td>${element.buyer_acc}</td><td>${convert_sum(element.price)}</td><td>Кнопка</td></tr>`
        $("#tableOfDemandsOnToken tbody").append(tr)
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

