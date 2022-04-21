<script src="/js/Page_Scripts/myCollectionScript.js"></script>

<div class="container marginContent">
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Количество супремов:</div>
            <div class="littleTitle" id="supremCount"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Активность:</div>
            <div class="littleTitle" id="activity"><span style = "color:red">Получать из базы</span></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Общие затраты на покупку Супремов:</div>
            <div class="littleTitle" id="totalCost"><span style = "color:red">Получать из базы</span></div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Предложения на моих Супремов:</div>
            <div class="littleTitle" id="supremOffer"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма ставок на моих Супремов:</div>
            <div class="littleTitle" id="supremBetSum"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма продаж моих Супремов:</div>
            <div class="littleTitle" id="supremSaleSum"><span style = "color:red">Получать из базы</span></div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Мои предложения о покупке:</div>
            <div class="littleTitle" id="myDemandsOnTokens"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма моих предложений:</div>
            <div class="littleTitle" id="myDemandsBetSum"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Наблюдатели:</div>
            <div class="littleTitle" id="supremSaleSum"><span style = "color:red">???</span></div>
        </div>
    </div>
</div>
<div class="container marginContent" style=" margin-top: 60px; ">
    <div class="row borderBottom">
        <div class="col-9">
            <h3>Все мои Супремы: <span id ="allSupremes_count"></span></h3>
        </div>
    </div>
    <div>
        <div id = "allSupremes_content" class="row marginContent"></div>
        <div>
            <a href = "#" id = "allSupremes_showMore">Загрузить еще</a>
        </div>
    </div>
    <div class="row borderBottom">
        <div class="col-9">
            <h3>Предложения на моих Супремов: <span id ="demandsOnMySupremes_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle" id = "demandsOnMySupremes_price"></span>
        </div>
    </div>
    <div>
        <div id = "demandsOnMySupremes_content" class="row marginContent"></div>
    </div>
    <div class="row borderBottom">
        <div class="col-9">
            <h3>Мои Супремы на продаже: <span id ="offersTab_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle" id = "offersTabs_price"></span>
        </div>
    </div>
    <div>
        <div id = "offersTab_content" class="row marginContent"></div>
        <div>
            <a href = "#" id = "offersTab_showmore">Загрузить еще</a>
        </div>
    </div>
    <div class="row borderBottom marginContent">
        <div class="col-9">
            <h3>Мои предложения: <span id ="myDemands_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle" id = "myDemands_price"></span>
        </div>
    </div>
    <div>
        <div id = "myDemands_content" class="row marginContent"></div>
        <div>
            <a href = "#" id = "myDemands_showMore">Загрузить еще</a>
        </div>
    </div>


    <!--div class="row borderBottom marginContent">
        <div class="col-9">
            <h3>Купленные Супремы: 1</h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">125 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($823)</span></span>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-1">
            <img src="/previewData/smallPreview/1.png">
            <div class="centerContent" style=" padding-top: 10px; ">
                <b>125</b> <img style='width: 20px;' src='/sources/nearCircleLogo.png'>
            </div>
            <div class="greenColor centerContent">
                ($823)
            </div>
        </div>
    </div>
    <div class="row borderBottom marginContent">
        <div class="col-9">
            <h3>Проданные Супремы: 1</h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">325 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($2423)</span></span>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-1">
            <img src="/previewData/smallPreview/1.png">
            <div class="centerContent" style=" padding-top: 10px; ">
                <b>325</b> <img style='width: 20px;' src='/sources/nearCircleLogo.png'>
            </div>
            <div class="greenColor centerContent">
                ($2423)
            </div>
        </div>
    </div-->
</div>