<script src="/js/Page_Scripts/faceScript.js"></script>

<?require_once("errorSnippet.php")?>

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
    <div id="ownerInfoContent">
        <div class="row">
            <div class="col align-self-center centerContent faceInfo" id="totalpriceDiv"></div>
        </div>
        <div class="row">
            <div class="col align-self-center centerContent faceInfo" id="makeDemandButtonDiv"></div>
        </div>
        <div class="row">
            <div class="col align-self-center centerContent faceInfo no-vis" id="makeDemandInput">
                <input id="make_demand_input" type="number">
            </div>
        </div>
        <div class="row">
            <div class="col align-self-center centerContent faceInfo" id="currentOwner"></div>
        </div>
        <div class="row">
            <div class="col align-self-center centerContent faceInfo" id="firstOwner"></div>
        </div>
        <div class="row">
            <div class="col align-self-center centerContent" id="noOnesTokenInfo"></div>
        </div>
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
<div class="container" id="demandsData" style=" margin-top: 30px; ">
    <div class="row">
        <div class="col-12">
            <h3>Список предложений</h3>
        </div>
    </div>
    <div class="row" style=" margin-top: 30px; ">
        <div class="col-12">
            <h4>Предложения о продаже</h4>
        </div>
        <table class="table" id="offerTable">
            <thead>
                <tr>
                    <th class="littleTitle">Аккаунт</th>
                    <th class="littleTitle">Стоимость</th>
                    <th class="littleTitle"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="controlOfferPanel" style="display: none" class="row">
        <div class="col-4"> <label for="nearOfferValueInput"><b>Введите предложение о продаже в yoctoNear:</b></label></div>
        <div class="col-4"> <input id="nearOfferValueInput" name="nearOfferValueInput" type="number" min="0"></div>
    </div>
    <div class="row"  style=" margin-top: 60px; ">
        <div class="col-12">
            <h4> Предложения о покупке<h4>
        </div>
        <table class="table" id="demandTable">
            <thead>
                <tr>
                    <th class="littleTitle">Аккаунт</th>
                    <th class="littleTitle">Стоимость</th>
                    <th class="littleTitle"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="controlDemandPanel" style="display: none" class="row">
        <div class="col-4"> <label for="nearDemandValueInput"><b>Введите предложение о покупке в yoctoNear:</b></label></div>
        <div class="col-4"> <input id="nearDemandValueInput" name="nearDemandValueInput" type="number" min="0"></div>
    </div>
</div>

<!--div class="container-fluid marginContent containerFluidPadding" style=" margin-top: 60px; ">
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
                <td class="noneBorderTop"></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="cancelPrice">Отмена предложения</td>
                <td class="cancelPrice">www.near</td>
                <td class="cancelPrice"></td>
                <td class="cancelPrice">0</td>
                <td class="cancelPrice">23.10.2021</td>
                <td class="noneBorderTop"></td>
            </tr>
            <tr>
                <td class="waitingPrice">Предложение</td>
                <td class="waitingPrice">www.near</td>
                <td class="waitingPrice"></td>
                <td class="waitingPrice">450</td>
                <td class="waitingPrice">13.10.2021</td>
                <td class="noneBorderTop">
                    <a href="#" class="cancelButton button arrow" onClick='alert("here")'> Отозвать </a>
                </td>
            </tr>
            <tr>
                <td class="waitingPrice">Предложение</td>
                <td class="waitingPrice">zzz.near</td>
                <td class="waitingPrice"></td>
                <td class="waitingPrice">400</td>
                <td class="waitingPrice">10.10.2021</td>
                <td class="noneBorderTop">
                    <a href="#" class="cancelButton button arrow" onClick='alert("here")'> Отозвать </a>
                </td>
            </tr>
            <tr>
                <td class="dealPrice">Предложение</td>
                <td class="dealPrice">ivtanart.near</td>
                <td class="dealPrice">x.near</td>
                <td class="dealPrice">100</td>
                <td class="dealPrice">05.10.2021</td>
                <td class="noneBorderTop"></td>
            </tr>
            <tr>
                <td>Первый владелец</td>
                <td></td>
                <td>ivtanart.near</td>
                <td></td>
                <td>01.10.2021</td>
                <td class="noneBorderTop"></td>
            </tr>
        </tbody>
    </table>
</div-->

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
        border-top: none !important;
    }

    .no-vis {
        display: none;
    }
</style>