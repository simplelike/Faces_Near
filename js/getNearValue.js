function loading_near_price_info(callback = () => {}) {
    $.ajax({
        url: "https://helper.mainnet.near.org/fiat",
        type: "GET",
        tryCount: 0,
        retryLimit: 3,
        error: function (xhr, textStatus, errorThrown) {
            console.log(textStatus)
        },
        complete: function(response) {
            callback(response.responseJSON)
        }
    })
}

function setPriceTo(elem, price) {
    /*let price_in_near = convert_sum(price)

    if (isEmpty(near_price)) {
        loading_near_price_info( (r) => {
            near_price = r
            let usd_price = (price_in_near * r.near.usd).toFixed(3)
            elem.append( `${price_in_near} ${near_logo} ${set_green_data_elem("($" + usd_price + ")")}`)
        })
    }
    else {
        let usd_price = (price_in_near * near_price.near.usd).toFixed(3)
        elem.append( `${price_in_near} ${near_logo} ${set_green_data_elem("($" + usd_price + ")")}`)
    }*/
    let price_in_near = convert_sum(price)
    
    let span_id = make_id()
    let element = document.createElement('span')
    $(element).addClass("greenColor").attr('id', span_id);
    let el_str = $(element).prop('outerHTML')

    let usd_price = convertNearToUSD(price_in_near, span_id)
    elem.append( `${price_in_near} ${near_logo} ${el_str}`)
   
}

async function convertNearToUSD(sum, id) {
    loading_near_price_info( (r) => {
        if (r.near.usd != undefined) {
            let usd_price = (sum * r.near.usd).toFixed(3)
            $("#"+id).append(`($${usd_price})`)
        }
        else return ""
    })
}