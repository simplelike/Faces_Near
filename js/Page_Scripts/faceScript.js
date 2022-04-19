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

    getOwnerOfToken(id).then(
        result => {
            setCurrentOwner(result)
        },
        error => console.log("err")
    )

    setFirstOwner("ivtanart.near")
    
    getOfferForTokenId(id).then(
        result => {
            setTotalPrice(result)
        },
        error => console.log(error)
    )
   



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
    $('#rarityDiv').append("Редкость - <span class = 'greenColor'>" + rarity + "</span>");
}

function setCurrentOwner(currentOwner) {
    $('#currentOwner').append("Текущий владелец: <span class = 'greenColor'>" + currentOwner + "</span>");
}

function setFirstOwner(firstOwner) {
    $('#firstOwner').append("Первый владелец: <span class = 'redColor'>" + firstOwner + "</span>");
}

function setTotalPrice(totalPrice) {
    if (totalPrice === null) {
        $('#totalpriceDiv').append("<span class = 'greenColor'>Предложений нет </span>" + make_demand_button());
        return
    }
    $('#totalpriceDiv').append("<span class = 'greenColor'>" + totalPrice + "</span> <img style='width: 50px;' src= '/sources/nearCircleLogo.png'>" + make_demand_button());
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
