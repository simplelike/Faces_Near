
$(window).load(function () {
    


    let showMySupreme = new Promise( (resolve, reject) => {
        contract.nft_tokens({
            args: {
                "from_index": 0,
                "limit": 10
            }
        })
        resolve("done")
        reject("err")
    })
    
    showMySupreme.then(  
        result => alert(result), // не будет запущена
        error => alert(error)
    ) 
})