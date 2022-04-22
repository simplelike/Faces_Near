let near_price = {};

const set_green_data_elem = (data) => {
    return `<span class = 'greenColor'>${data}</span>`
}
const set_red_data_elem = (data) => {
    return `<span class = 'redColor'>${data}</span>`
}
const near_logo = `<img style='width: 50px;' src= '/sources/nearCircleLogo.png'>`

function number_from_scientific_notation(number) {
    return number.toLocaleString('fullwide', { useGrouping: false })
}

function add_table_tr_to (table, first_td_content, second_td_content, buttonText = "", buttonHandler = () => { }) {
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

    convertNearToUSD(price).then(
        dollar_price => {
            console.log(dollar_price)
            return `${set_green_data_elem("<b>"+price+"</b>" + "($"+dollar_price+")")}`
        }
    )
    
    //return `${set_green_data_elem("<b>"+price+"</b>" + "($"+convertNearToUSD(price)+")")}`
}