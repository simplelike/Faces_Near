let near_price = {};

function create_green_data_elem(data ="", id ="") {
    let element = document.createElement('span')
    $(element).addClass("greenColor");

    if (data != "") {$(element).html(data)}
    if (id != "") {$(element).attr("id", id)}

    return  $(element).prop('outerHTML')

    //return `<span id = ${id} class = 'greenColor'>${data}</span>`;
}
const set_red_data_elem = (data) => {
    return `<span class = 'redColor'>${data}</span>`
}
const near_logo = `<img style='width: 50px;' src= '/sources/nearCircleLogo.png'>`

function number_from_scientific_notation(number) {
    return number.toLocaleString('fullwide', { useGrouping: false })
}

function add_table_tr_to (table, first_td_content, second_td_content, buttonText = "", buttonHandler = () => { }) {
    console.log(second_td_content)
    let elem = `<tr>
                        <td>${first_td_content}</td>
                        <td>${second_td_content}</td>
                    </tr>`;
    table.append(elem);
}

function convert_sum(sum) {
    return nearApi.utils.format.formatNearAmount(number_from_scientific_notation(sum))
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

const button = (color, text, handler = () => {}, additionalData = "") => {
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
    $(element).addClass("greenColor").attr('id', id).html("<b>"+price+"</b>");

    convertNearToUSD(price, id)

    return $(element).prop('outerHTML');
}

function make_id() {
    var id = "id" + Math.random().toString(16).slice(2)
    return id
}