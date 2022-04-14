<nav class="navbar navbar-expand-lg navbar-light navBarBg">
    <div>
        <a href="/">
            <img src="/sources/head_logo.png">
            <span class="brandLogo">
                SUPREM
            </span>
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-md-center" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/">Описание <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/gallery.php">Галерея</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/myCollection.php">Моя коллекция </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="signInButton" href="#"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="signInButton" onclick="contractMethodEval();" href="#">Тест </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    async function contractMethodEval() {

        let promises = []

        let i = 0
        let args_arr = []
        //console.log(localDump_test)
        for (let [index, element] of localDump_test.entries()) {

            let _e = {
                "token_id": index.toString(),
                "metadata": {
                    "title": element["T"],
                    "media": element["g_h"]
                },
                "receiver_id": contract_id
            }

            if (i <= 5) {
                args_arr.push(_e)
            }
            if (i === 5 || index === localDump_test.length - 1) {
                let args_str = JSON.stringify(args_arr)
                console.log(args_str)

                promises.push((resolve, reject) => {
                    contract.nft_batch_mint(
                        {
                            innerdData: args_str
                        },
                        "300000000000000")
                })
                args_arr = []
                i = 0
                continue
            }
            i++
        }

        //let _pr = promises.slice(5)
        await Promise.all(promises.map(
            promiseFn => new Promise(promiseFn)
        )).
        then(value => {
            console.log(value)
        }, reason => {
            console.log(reason)
        });

    }
</script>