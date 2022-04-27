<div class="container" id = "errorInfoScreen" style="display:none">
    <div class="row">
        <div class="col">
            <div id="usageOfLocalDumpInfoDiv" class="alert alert-warning alert-dismissible fade show" role="alert">
                <h3>Обратите внимание!</h3>
                <p> 
                    Данное сообщение содержит отчет об ошибках в работе сервиса.
                    Пожалуйста сохраните его и отправьте разработчикам по адресу:
                    <a href = "mailto:simple_like@list.ru">simple_like@list.ru</a>
                </p>
                <div id = "errorContainer"></div>
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