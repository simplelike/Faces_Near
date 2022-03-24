//Check if dataDump is already in forage
function localForageHandler(f) {
    localforage.getItem("dataDump").then((value) => {
        //calculating hash of object dataDump to make sure that object is the same that in ipfs
        let forageObjectHash = objectHash.sha1(value);
        //If it's the first loading of page and we havent obj in localforage - need to download it from server
        if (value == null) {
            alert('first time loaded')
            localForageSync(f)
            return
        }
        //Else asking the contract to data hash
        contract.get_hash_of_data({}).
            then(output => {
                //if hash of object in dataDump are the same that the hash in contract
                if (output === forageObjectHash) {
                    //Starting gallery page drawing
                    alert('already loaded')
                    f(value)
                }
                //If they are not the same - starting sync data from ipfs and save it to localForage
                else {
                    alert('hashes are: contract:'+ output + " forage: " + forageObjectHash)
                    localForageSync(f)
                }
            }).catch(function (err) {
                console.log(err)
                //If we can't get hash of object from contract - forcibly download it from ipfs
                localForageSync(f)
            });
    })
    //if we've got an error we need to place some code here!!!!    
    .catch(function (err) {
            console.log(err);
            //If we can't get obj from localforage - forcibly download it from ipfs
            localForageSync(f)
        })
}

function localForageSync(callback) {
    let link_to_ipfs_data = "";
    contract.get_link_to_data({}).
        then(output => {
            //let returnData = JSON.parse(output);
            link_to_ipfs_data = output;
            console.log(output)
            console.log(contract_id)
            loading(link_to_ipfs_data, callback)
        }).catch(function (err) {
            //If unable to get link from contract - show infoDiv and use localDump
            showUsageOfLocalDumpInfoDiv()
            callback(localDump)
            console.log(err);
        });
}
function loading(link, callback) {
    $.ajax({
        url: "/API/getDataDump.php",
        type: "POST",
        data: {
            url: link
        },
        tryCount: 0,
        retryLimit: 3,
        success: function (data) {
            let dataObj = JSON.parse(data)
            console.log(dataObj)
            localforage.setItem('dataDump', dataObj).then(function (value) {
                callback(value);
            }).catch(function (err) {
                console.log(err);
            });
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(textStatus)
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    //try again
                    $.ajax(this);
                    return;
                }
                return;
            }
            if (xhr.status == 500) {
                //handle error
            } else {
                //handle error
            }
            //If unable to get data from ipfs - show infoDiv and use localDump
            showUsageOfLocalDumpInfoDiv()
            callback(localDump)
        }
    })
}

function setObjectToLocalForage(obj, f) {
    localforage.setItem('dataDump', obj).then(
        f()
    ).catch(function(err) {
        // This code runs if there were any errors
        console.log(err);
    });
}

function showUsageOfLocalDumpInfoDiv() {
    let infoDiv = $("#usageOfLocalDumpInfoDiv");
    infoDiv.show();
}