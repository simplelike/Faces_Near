function loading_near_price_info(callback = () => {}) {
    $.ajax({
        url: "https://helper.mainnet.near.org/fiat",
        type: "GET",
        tryCount: 0,
        retryLimit: 3,
        async: false,
        success: function (data) {
            callback(data)
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(textStatus)
        },
    })
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

function setPriceTo(elem, price) {
    let price_in_near = convert_sum(price)

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
    }

}

function convertNearToUSD(sum) {
    if (isEmpty(near_price)) {
        loading_near_price_info( () => {
            return (sum * near_price.near.usd).toFixed(3)
        })
    }
    else {
        return (sum * near_price.near.usd).toFixed(3)
    }
}