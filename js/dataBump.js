var dataDump = {};
$(window).on("load", () => {

    $.get( "/API/getDataDump.php", {})
            .done(function( data ) {
                dataDump = JSON.parse(data);
                console.log(dataDump);
            })

})