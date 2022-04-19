<?
// require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
// use Codenixsv\CoinGeckoApi\CoinGeckoClient;

// $client = new CoinGeckoClient();
// $data = $client->derivatives()->getExchanges();
// $response = $client->getLastResponse();
// $headers = $response->getHeaders();

// $result = $client->coins()->getHistory('near', '22-05-2021');
// echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
<script src="/js/Page_Scripts/myCollectionScript.js"></script>

<div class="container marginContent">
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Количество супремов:</div>
            <div class="littleTitle" id="supremCount"></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Активность:</div>
            <div class="littleTitle" id="activity">12.10.2021 16:00</div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Общие затраты на покупку Супремов:</div>
            <div class="littleTitle" id="totalCost">125 <img style='width: 20px;' src='/sources/nearCircleLogo.png'> ($823)</div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Предложения на моих Супремов:</div>
            <div class="littleTitle" id="supremOffer">5</div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма ставок на моих Супремов:</div>
            <div class="littleTitle" id="supremBetSum">1025 <img style='width: 20px;' src='/sources/nearCircleLogo.png'> <span class="greenColor centerContentor">($8023)</span></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма продаж моих Супремов:</div>
            <div class="littleTitle" id="supremSaleSum">325 <img style='width: 20px;' src='/sources/nearCircleLogo.png'> ($2426)</div>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-4 centerContent">
            <div class = "bigTitle">Мои предложения:</div>
            <div class="littleTitle" id="supremOffer">7</div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Сумма моих предложений:</div>
            <div class="littleTitle" id="supremBetSum">325 <img style='width: 20px;' src='/sources/nearCircleLogo.png'> <span class="greenColor centerContentor">($2423)</span></div>
        </div>
        <div class="col-4 centerContent">
            <div class = "bigTitle">Наблюдатели:</div>
            <div class="littleTitle" id="supremSaleSum">12</div>
        </div>
    </div>
</div>








<div class="container marginContent" style=" margin-top: 60px; ">
    <div class="row borderBottom">
        <div class="col-9">
            <h3>Все мои Супремы: <span id ="allSupremes_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">500 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($3000)</span></span>
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
            <h3>Мои Супремы на продаже: <span id ="offersTab_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">500 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($3000)</span></span>
        </div>
    </div>
    <div>
        <div id = "offersTab_content" class="row marginContent"></div>
        <div>
            <a href = "#" id = "offersTab_showmore">Загрузить еще</a>
        </div>
    </div>
    <!--div class="row borderBottom marginContent">
        <div class="col-9">
            <h3>Предложения на моих Супремов: 2</h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">1025 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($8023)</span></span>
        </div>
    </div>
    <div class="row marginContent">
        <div class="col-1">
            <img src="/previewData/smallPreview/1.png">
            <div class="centerContent" style=" padding-top: 10px; ">
                <b>1000</b> <img style='width: 20px;' src='/sources/nearCircleLogo.png'>
            </div>
            <div class="greenColor centerContent">
                ($6500)
            </div>
        </div>
        <div class="col-1">
            <img src="/previewData/smallPreview/2.png">
            <div class="centerContent" style=" padding-top: 10px; ">
                <b>25</b> <img style='width: 20px;' src='/sources/nearCircleLogo.png'>
            </div>
            <div class="greenColor centerContent">
                ($1523)
            </div>
        </div>
    </div-->
    <div class="row borderBottom marginContent">
        <div class="col-9">
            <h3>Мои предложения: <span id ="myDemands_count"></span></h3>
        </div>
        <div class="col-3">
            <span class="littleTitle">325 <img style='width: 35px;' src='/sources/nearCircleLogo.png'> <span class="greenColor littleTitle">($2423)</span></span>
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