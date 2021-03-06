//globals var
let from, step, to, dataArrDump,
    dataArrCurrent, chBxParams,
    dataForFaceAttrChBX, dataForBackgroundeAttrChBX,
    dataForMaskAttrChBX, dataForEyeAttrChBX,
    dataForJeweleryAttrChBX, dataForShirtAttrChBX,
    dataForHatAttrChBX, dataForLipseAttrChBX
//On page loading
$(window).on("load", () => {
    //Initialize dataStructures
    dataArrDump = [];
    dataForFaceAttrChBX = {};
    dataForBackgroundeAttrChBX = {};
    dataForMaskAttrChBX = {};
    dataForEyeAttrChBX = {};
    dataForJeweleryAttrChBX = {};
    dataForShirtAttrChBX = {};
    dataForHatAttrChBX = {};
    dataForLipseAttrChBX = {};

    localForageHandler(_start)
})

function handler(e) {
    $('#infiniteScroll').empty()
    fillData()
}

function _start(v) {

    dataArrDump = v
    document.querySelector('.loading').classList.add('show')
    $('#jeweleryInputParamElement').on('change', handler)
    $('#backgroundInputParamElement').on('change', handler)
    $('#faceInputParamElement').on('change', handler)
    $('#maskInputParamElement').on('change', handler)
    $('#hatInputParamElement').on('change', handler)
    $('#shirtInputParamElement').on('change', handler)
    $('#lipsInputParamElement').on('change', handler)
    $('#eyesInputParamElement').on('change', handler)
    fillData()
    //setCheckBoxesParam(chBxParams);
    document.querySelector('.loading').remove('show')
    $('.preloader').fadeOut().end().delay(400).fadeOut('slow')
}

function fillData() {
    from = 0;
    step = 24;
    to = from + step;
    chBxParams = {
        f_a: $('#faceInputParamElement option:selected').attr('value'),
        h_a: $('#hatInputParamElement option:selected').attr('value'),
        m_a: $('#maskInputParamElement option:selected').attr('value'),
        b_a: $('#backgroundInputParamElement option:selected').attr('value'),
        j_a: $('#jeweleryInputParamElement option:selected').attr('value'),
        e_a: $('#eyesInputParamElement option:selected').attr('value'),
        l_a: $('#lipsInputParamElement option:selected').attr('value'),
        s_a: $('#shirtInputParamElement option:selected').attr('value'),
    }
    dataArrCurrent = makeDataFromSelectedCheckBoxes(chBxParams)
    calcTheCountOfElementsInCheckBoxes(dataArrCurrent)
    drawImages(from, to)
}

function drawImages(from, to, ) {
    let element = $('#infiniteScroll');
    for (let i = from; i <= to; i++) {
        element.append(`<div class = "col" style = "margin-bottom: 25px"> <a class = "galleryImgLink" href = "/face.php?id=${parseInt(dataArrCurrent[i].nbr - 1)}" onclick = "clickLinkFunction(event)"><img class="loadingImg" index = "${i}" src = "/previewData/midPreview/${parseInt(dataArrCurrent[i].nbr)}.png"></a></div>`)
    }
}

window.addEventListener('scroll', () => {

    if ($(window).scrollTop() >= $(document).height() - window.innerHeight - 10) {
        from = to + 1;
        to = from + step;
        drawImages(from, to)
    }
});

/*function clickLinkFunction(e) {
    e.preventDefault();
    let index = (e.target.getAttribute('index'))
    localStorage["object"] = JSON.stringify(dataArrCurrent[index])
    window.open("face.php", '_blank')
    console.log(dataArrCurrent[index])
}*/

function makeDataFromSelectedCheckBoxes(chBxParams) {

    let checker = (v) => {
        let _cond = chBxParams
        for (let key in _cond) {
            if (_cond[key] == "default") {
                delete _cond[key]
            }
        }
        for (let key in _cond) {
            if (v[key].T !== _cond[key]) {
                return false;
            }
        }
        return true;
    }
    let filtredData = dataArrDump.filter(checker)
    return filtredData
}

function calcTheCountOfElementsInCheckBoxes(data) {

    Object.keys(dataForFaceAttrChBX).forEach(v => dataForFaceAttrChBX[v] = 0)
    Object.keys(dataForBackgroundeAttrChBX).forEach(v => dataForBackgroundeAttrChBX[v] = 0)
    Object.keys(dataForMaskAttrChBX).forEach(v => dataForMaskAttrChBX[v] = 0)
    Object.keys(dataForEyeAttrChBX).forEach(v => dataForEyeAttrChBX[v] = 0)
    Object.keys(dataForJeweleryAttrChBX).forEach(v => dataForJeweleryAttrChBX[v] = 0)
    Object.keys(dataForShirtAttrChBX).forEach(v => dataForShirtAttrChBX[v] = 0)
    Object.keys(dataForHatAttrChBX).forEach(v => dataForHatAttrChBX[v] = 0)
    Object.keys(dataForLipseAttrChBX).forEach(v => dataForLipseAttrChBX[v] = 0)

    data.forEach(value => {
        if (!(value.f_a.T in dataForFaceAttrChBX)) {
            dataForFaceAttrChBX[value.f_a.T] = 0;
        }
        dataForFaceAttrChBX[value.f_a.T]++;

        if (!(value.b_a.T in dataForBackgroundeAttrChBX)) {
            dataForBackgroundeAttrChBX[value.b_a.T] = 0;
        }
        dataForBackgroundeAttrChBX[value.b_a.T]++;

        if (!(value.m_a.T in dataForMaskAttrChBX)) {
            dataForMaskAttrChBX[value.m_a.T] = 0;
        }
        dataForMaskAttrChBX[value.m_a.T]++;

        if (!(value.e_a.T in dataForEyeAttrChBX)) {
            dataForEyeAttrChBX[value.e_a.T] = 0;
        }
        dataForEyeAttrChBX[value.e_a.T]++;

        if (!(value.j_a.T in dataForJeweleryAttrChBX)) {
            dataForJeweleryAttrChBX[value.j_a.T] = 0;
        }
        dataForJeweleryAttrChBX[value.j_a.T]++;

        if (!(value.s_a.T in dataForShirtAttrChBX)) {
            dataForShirtAttrChBX[value.s_a.T] = 0;
        }
        dataForShirtAttrChBX[value.s_a.T]++;

        if (!(value.h_a.T in dataForHatAttrChBX)) {
            dataForHatAttrChBX[value.h_a.T] = 0;
        }
        dataForHatAttrChBX[value.h_a.T]++;

        if (!(value.l_a.T in dataForLipseAttrChBX)) {
            dataForLipseAttrChBX[value.l_a.T] = 0;
        }
        dataForLipseAttrChBX[value.l_a.T]++;
    })

    setOptionsForSelect('jeweleryInputParamElement', dataForJeweleryAttrChBX, {
        '??????????????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForJeweleryAttrChBX)) {
                sum += count;
            }
            return sum;
        }
    );
    setOptionsForSelect('backgroundInputParamElement', dataForBackgroundeAttrChBX, {
        '??????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForBackgroundeAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('faceInputParamElement', dataForFaceAttrChBX, {
        '????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForFaceAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('maskInputParamElement', dataForMaskAttrChBX, {
        '??????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForMaskAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('hatInputParamElement', dataForHatAttrChBX, {
        '??????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForHatAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('shirtInputParamElement', dataForShirtAttrChBX, {
        '????????????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForShirtAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('lipsInputParamElement', dataForLipseAttrChBX, {
        '????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForLipseAttrChBX)) {
                sum += count;
            }
            return sum;
        });
    setOptionsForSelect('eyesInputParamElement', dataForEyeAttrChBX, {
        '??????????': "default"
    },
        () => {
            let sum = 0;
            for (let count of Object.values(dataForEyeAttrChBX)) {
                sum += count;
            }
            return sum;
        });
}

function setOptionsForSelect(id, data, firstValue, totalCount) {

    let element = $('#' + id);
    let selectedValue = element.val();

    element.find('option').remove()
    data = {
        ...firstValue,
        ...data
    }
    for (let key in data) {

        let text = data[key] == "default" ? key + " (" + totalCount() + ")" : key + " (" + data[key] + ")";
        let value = data[key] == "default" ? "default" : key;
        let count = data[key] == "default" ? totalCount() : data[key];
        element.append(`<option value="${value}" count = "${count}" title = "${key}" >${text}</option>`)
    }
    element.val(selectedValue)
    let count = $("#" + id + " option:selected").attr('count')
    setContentForAttrComponent(id, $("#" + id + " option:selected").attr('title'), count)
}

function setContentForAttrComponent(id, choosenElementTitle, count) {
    let _id = id.replace('InputParamElement', 'AttrComponent');
    let element = $('#' + _id)
    element.empty()
    let title = ""
    switch (_id) {
        case 'jeweleryAttrComponent':
            title = "??????????????????"
            break
        case 'backgroundAttrComponent':
            title = "??????"
            break
        case 'hatAttrComponent':
            title = "??????????"
            break
        case 'lipsAttrComponent':
            title = "????????"
            break
        case 'shirtAttrComponent':
            title = "????????????????"
            break
        case 'maskAttrComponent':
            title = "??????????"
            break
        case 'eyesAttrComponent':
            title = "??????????"
            break
        case 'faceAttrComponent':
            title = "????????"
            break
    }
    element.append(
        `<div class = "row" class = "padding">
            <div class = "col typeStyle">
                ${title}
            </div>
            <div class = "col componentTitle">
                ${choosenElementTitle}
            </div>
    </div>
    <div class="row">
        <div class="col totalCountStr">
            ???????????????????? ???????????????? ?? ?????????????????? ??????????????????: ${count}
        </div>
    </div>`
    )
}