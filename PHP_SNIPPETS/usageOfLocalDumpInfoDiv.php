<div class="container">
    <div class="row">
        <div class="col">
            <div id="usageOfLocalDumpInfoDiv" class="alert alert-warning alert-dismissible fade show" role="alert">
                <h3>Обратите внимание!</h3>
                <p>Не удалось полностью синхронизировать данные с контрактом.</p>
                <p> Используется локальная копия данных токенов. Будьте аккуратны при проведении транзакций.</p>
                <p> <a href="mailto:test@test.ru">Сообщить об ошибке</a> </p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="myAlert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    #usageOfLocalDumpInfoDiv {
        display: none;
    }
</style>

<script>
    $('#myAlert').on('click', function() {
        alert("cloased");
    })
</script>