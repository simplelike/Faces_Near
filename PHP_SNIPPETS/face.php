<div class="preloader">
    <div class="preloader__image"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col align-self-center centerContent marginContent loadingImg" id="imgDiv"></div>
    </div>
    <!--div class="row">
        <div class="col align-self-center centerContent marginContent" id = "linkDiv"></div>
    </div-->
    <div class="row">
        <div class="col align-self-center centerContent faceInfo" id="numberDiv"></div>
    </div>
    <div class="row">
        <div class="col align-self-center centerContent faceInfo" id="rarityDiv"></div>
    </div>
    <div class="row">
        <div class="col align-self-center centerContent faceInfo" id="totalpriceDiv"></div>
    </div>
    <div class="row">
        <div class="col align-self-center centerContent faceInfo" id="currentOwner"></div>
    </div>
    <div class="row">
        <div class="col align-self-center centerContent faceInfo" id="firstOwner"></div>
    </div>
</div>
<div class="container-fluid marginContent containerFluidPadding">
    <div class="row marginContent">
        <div class="col">
            <div class="attrComponent" id="jeweleryAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="backgroundAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="faceAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="maskAttrComponent"></div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col">
            <div class="attrComponent" id="hatAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="shirtAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="lipsAttrComponent"></div>
        </div>
        <div class="col">
            <div class="attrComponent" id="eyesAttrComponent"></div>
        </div>
    </div>
</div>
<div class="container-fluid marginContent containerFluidPadding" style=" margin-top: 60px; ">
    <div class="row">
        <div class="col littleTitle">
            История владения
        </div>
        <div class="col">
            <span class="littleTitle">Установить свою цену:</span>
            <span><input type="text"></span>
            <img style='width: 40px;' src='/sources/nearCircleLogo.png'>
            <a href="#" class="offerButton button" onClick='alert("here")'> Предложить </a>
        </div>
    </div>
</div>
<div class="container marginContent table-responsive-md">
    <table class="table">
        <thead>
            <tr>
                <td class="littleTitle">Действие</td>
                <td class="littleTitle">От кого</td>
                <td class="littleTitle">Кому</td>
                <td class="littleTitle">Стоимость <img style='width: 20px;' src='/sources/nearCircleLogo.png'> </td>
                <td class="littleTitle">Дата</td>
                <td class = "noneBorderTop"></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cancelPrice">Отмена предложения</td>
                <td class="cancelPrice">www.near</td>
                <td class="cancelPrice"></td>
                <td class="cancelPrice">0</td>
                <td class="cancelPrice">23.10.2021</td>
                <td class = "noneBorderTop"></td>
            </tr>
            <tr>
                <td class="waitingPrice">Предложение</td>
                <td class="waitingPrice" >www.near</td>
                <td class="waitingPrice" ></td>
                <td class="waitingPrice" >450</td>
                <td class="waitingPrice" >13.10.2021</td>
                <td class = "noneBorderTop">
                    <a href="#" class="cancelButton button arrow" onClick='alert("here")'> Отозвать </a>
                </td>
            </tr>
            <tr>
                <td class="waitingPrice">Предложение</td>
                <td class="waitingPrice">zzz.near</td>
                <td class="waitingPrice"></td>
                <td class="waitingPrice">400</td>
                <td class="waitingPrice">10.10.2021</td>
                <td class = "noneBorderTop">
                    <a href="#" class="cancelButton button arrow" onClick='alert("here")'> Отозвать </a>
                </td>
            </tr>
            <tr>
                <td class="dealPrice">Предложение</td>
                <td class="dealPrice">ivtanart.near</td>
                <td class="dealPrice">x.near</td>
                <td class="dealPrice">100</td>
                <td class="dealPrice">05.10.2021</td>
                <td class = "noneBorderTop"></td>
            </tr>
            <tr>
                <td>Первый владелец</td>
                <td></td>
                <td>ivtanart.near</td>
                <td></td>
                <td>01.10.2021</td>
                <td class = "noneBorderTop"></td>
            </tr>
        </tbody>
    </table>
</div>

<style type="text/css">
    .arrow {
        opacity: 0;
        position: absolute;
        left: 30px;
    }

    tr:hover .arrow {
        opacity: 1;
    }

    td {
        position: relative;
    }

    .noneBorderTop {
        border-top: none!important;
    }
</style>

<script type="text/javascript">


    let data
    var queryDict = {}
    location.search.substr(1).split("&").forEach(function(item) {
        queryDict[item.split("=")[0]] = item.split("=")[1]
    })
    let id = queryDict.id
    let number = parseInt(id) + 1 
    localForageHandler(_start)

    function _start(v) {
            data = v[id]
            setImageToDiv("/previewData/maxPreview/"+number+".png", data.g_h)
            setUrlPath(data.g_h)
            setNumber(data.nbr)
            setRarity(data.rrt)
            setCurrentOwner("x.near")
            setFirstOwner("ivtanart.near")
            setTotalPrice("598")



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
    const button = '<a href="#" class = "buyButton button" onClick = alert("here")> КУПИТЬ </a>'

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
        $('#totalpriceDiv').append("<span class = 'greenColor'>" + totalPrice + "</span> <img style='width: 50px;' src= '/sources/nearCircleLogo.png'>" + button);
    }

    function setImageToDiv(src, hash) {
        let ipfUrl = hash;
        $('#imgDiv').append(`<a href = "${ipfUrl}"><img src = "${src}"></a>`)
    }

    function setUrlPath(hash) {
        let ipfUrl = "https://ipfs.io/ipfs/" + hash;
        $('#linkDiv').append(`<a href = "${ipfUrl}">Ссылка на оригинал</a>`)
    }
</script>