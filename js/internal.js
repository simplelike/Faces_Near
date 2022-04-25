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

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
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