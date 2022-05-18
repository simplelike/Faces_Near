let near_price = {};

function create_green_data_elem(data = "", id = "") {
    let element = document.createElement('span')
    $(element).addClass("greenColor");

    if (data != "") { $(element).html(data) }
    if (id != "") { $(element).attr("id", id) }

    return $(element).prop('outerHTML')

    //return `<span id = ${id} class = 'greenColor'>${data}</span>`;
}
const set_red_data_elem = (data) => {
    return `<span class = 'redColor'>${data}</span>`
}
const near_logo = `<img style='width: 50px;' src= '/sources/nearCircleLogo.png'>`
const sm_near_logo = `<img style='width: 20px;' src= '/sources/nearCircleLogo.png'>`

function number_from_scientific_notation(number) {
    return number.toLocaleString('fullwide', { useGrouping: false })
}

function add_table_tr_to(table, arrOfTds) {
    
    let table_row = document.createElement('tr')
    for (let [_, element] of arrOfTds.entries()) {
        let td = document.createElement('td')
        $(td).html(element)
        $(table_row).append(td)
    } 
    table.append(table_row)
}

function convert_sum(sum) {
    return nearApi.utils.format.formatNearAmount(number_from_scientific_notation(sum))
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

const button = (color, text, handler = () => { }, additionalData = "") => {
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
    $(button).text(text)
    if (handler != "") {
        console.log(handler)
        $(button).click(
            () => {
                handler()
            }
        )
    }
    if (additionalData != "") {
        let args = additionalData.split("#");
        for (let [_, element] of args.entries()) {
            let elem = element.split(":");
            if (!isEmpty(elem)) {
                switch (elem[0]) {
                    case "class":
                        $(button).addClass(elem[1])
                        break
                    case "attr":
                        let attr_arr = elem[1].split("=")
                        $(button).attr(attr_arr[0], attr_arr[1])
                        break
                    
                    default:
                        break
                }
            }
        }
    }
    return button
}

const price_elem = (price) => {
    let id = make_id()
    let element = document.createElement('span')
    $(element).addClass("greenColor").attr('id', id).html("<b>" + price + "</b> ");
    $(element).append(sm_near_logo)
    convertNearToUSD(price, id)

    return $(element).prop('outerHTML');
}

function make_id() {
    var id = "id" + Math.random().toString(16).slice(2)
    return id
}
function scrollToAnchor(aid){
    var aTag = $("[name='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
}

function showErrorMessage(msg) {
    showUsageOfLocalDumpInfoDiv()
    $("#errorInfoScreen").show()
    $("#errorContainer").append(msg)
    $("#errorContainer").append("<br>")
}

const detail_info_element  = (id) => {
    return `
            <div id = ${id}>
                <div>
                    <a href = "#" id = "info_toggler" onclick = "showDetailInfoElements()">
                        Подробнее
                    </a>
                </div>
                <div style = "display:none" id = "deatailElementsContent" class = "detail-info">
                    <label for="gas_input"><b>Введите желаемое значение GAS (max: 300000000000000): </b></label>
                    <input id="gas_input" name="gas_input" type="number" min="0" max = "300000000000000" value = "300000000000000">

                    <label for="deposit_select"><b>Введите желаемое значение депозита в yoctoNear: </b></label>
                    <select id = "deposit_select" name="deposit_select">
                        <option value = "1000000000000000000000">1000000000000000000000</option>
                        <option value = "2000000000000000000000">2000000000000000000000</option>
                        <option value = "3000000000000000000000">3000000000000000000000</option>
                        <option value = "4000000000000000000000">4000000000000000000000</option>
                        <option value = "5000000000000000000000">5000000000000000000000</option>
                        <option value = "6000000000000000000000">6000000000000000000000</option>
                        <option value = "7000000000000000000000">7000000000000000000000</option>
                        <option selected value = "8000000000000000000000">8000000000000000000000</option>
                        <option value = "9000000000000000000000">9000000000000000000000</option>
                        <option value = "1000000000000000000000">10000000000000000000000</option>
                        <option value = "20000000000000000000000">20000000000000000000000</option>
                        <option value = "30000000000000000000000">30000000000000000000000</option>
                        <option value = "40000000000000000000000">40000000000000000000000</option>
                        <option value = "50000000000000000000000">50000000000000000000000</option>
                        <option value = "60000000000000000000000">60000000000000000000000</option>
                        <option value = "70000000000000000000000">70000000000000000000000</option>
                        <option value = "80000000000000000000000">80000000000000000000000</option>
                        <option value = "90000000000000000000000">90000000000000000000000</option>
                        <option value = "100000000000000000000000">100000000000000000000000</option>
                        <option value = "200000000000000000000000">200000000000000000000000</option>
                    </select>
                </div>
            </div>
        `
}

function showDetailInfoElements() {
    event.preventDefault()
    $("#deatailElementsContent").toggle()
    if ($("#info_toggler").text().trim() === "Подробнее") { 
        $("#info_toggler").text("Скрыть"); 
    } else { 
        $("#info_toggler").text("Подробнее"); 
    }; 
}