var ColorName = "";
var idPodetail = "";
var productColor = "";

async function getColor() {
    const url = 'https://wnj1.com/custom/miki-hat/data_color.php';
    const res = await fetch(url).then((response) => {
        return response.json();
    }).then((data) => {
        let colors = data;
        const myObj = JSON.stringify(colors);
        const myObj2 = JSON.parse(myObj);
        const countdata = Object.keys(myObj2.name).length;
        for (z = 0; z < countdata; z++) {
            if (z === 0) {
                ColorName = "'" + myObj2.name[z] + "'";
                idPodetail = "'" + myObj2.code[z] + "'";
            } else {
                ColorName = ColorName + ",'" + myObj2.name[z] + "'";
                idPodetail = idPodetail + ",'" + myObj2.code[z] + "'";
            }

        }

        return '[' + ColorName + ']';
    }).catch(function (error) {
        console.log(error);
    });

    productColor = eval(res);
}

// ---------------------get data--------------------

var mockupWrapper = $('#mockupWrapper');
var mockupImg = $('#mockup');
var orderForm = $('#orderForm');
var canvas;

var fonts = [
    'Halaney Demo',
    'BlackJack',
    'Awal Ramadhan',
    'Amazing Mother'
];

var storedData = JSON.parse(localStorage.getItem('customOrderDataMikiHat'));
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const storedDataId = urlParams.get('id');
const storedTeks = urlParams.get('teks');
const idadmin = urlParams.get('idadmin');
const idpo = urlParams.get('idpo');
const idpodetail = urlParams.get('idpodetail');
const idproduk = urlParams.get('idproduk');
// if(window.location.search == ''){
//     if (confirm('Please Login First') == true) {
//         window.location.href = "https://wnj1.com/"
//       } else {
//         window.location.href = "https://wnj1.com/"
//       }
// }
$(document).ready(() => {
    initFabricjs();
    $('#qty').val(1);
    addFabricText('');
    addTextInput(0, '');
    // if (storedDataId != undefined) {
    //     initFabricjs();
    //     for (let i = 0; i < storedData.length; i++) {
    //         addFabricText(storedData[i].customTeks, storedData[i].fontType);
    //         if (storedTeks != storedData[i].customTeks) {
    //             canvas._objects[i].opacity = 0;
    //         } else {
    //             // $('#mikiHat')[0].src = window.location.origin + '/custom/miki-hat/img/miki-' + storedData[storedDataId].warnaTopi + '.jpg';
    //             $('#mikiHat')[0].src = window.location.origin + '/custom-wnj/miki-hat/img/miki-' + storedData[storedDataId].warnaTopi + '.jpg';
    //             if (storedData[storedDataId].warnaTopi == 'cream' || storedData[storedDataId].warnaTopi == 'silver' || storedData[storedDataId].warnaTopi == 'white' || storedData[storedDataId].warnaTopi == 'grey') {
    //                 console.log(canvas._objects[storedDataId].set('fill', 'black'));
    //                 canvas.renderAll();
    //             } else {
    //                 console.log(canvas._objects[storedDataId].set('fill', 'gold'));
    //                 canvas.renderAll();
    //             }
    //         }
    //         addTextInput(i, storedData[i].customTeks);
    //         $('#qty').val(Number(storedData.length));
    //         $('#hatColor-' + i).val(storedData[i].warnaTopi);
    //         $('#selectUkuran-' + i).val(storedData[i].ukuranTopi);
    //         $('#selectFont-' + i).val(storedData[i].fontType);
    //     }
    // } else {
    // }
})

// const fontColor = (id) => {
//     if ($('#hatColor-' + id).val())
// }

function addFabricImage(url) {
    if (window.location.search == '') {
        fabric.Image.fromURL(url, (image) => {
            var img = image.set({
                angle: 0,
                left: 0,
                top: 0,
                mode: 'multiply',
                scaleX: 0.3,
                scaleY: 0.3,
                selectable: true,
                centeredScaling: true,
                centeredRotation: true
            });
            canvas.add(img);
        });
    } else {
        $('#addToCart')[0].innerText = 'Save';
        // $('#mikiHat')[0].src = window.location.origin + '/custom/miki-hat/img/miki-' + storedData[storedDataId].warnaTopi + '.jpg';
        $('#mikiHat')[0].src = window.location.origin + '/custom-wnj/miki-hat/img/miki-' + storedData[storedDataId].warnaTopi + '.jpg';
        fabric.Image.fromURL(url, (image) => {
            var img = image.set({
                angle: 0,
                left: 0,
                top: 0,
                mode: 'multiply',
                scaleX: 0.3,
                scaleY: 0.3,
                selectable: true,
                centeredScaling: true,
                centeredRotation: true
            });
            canvas.add(img);
            for (let i = 0; i < canvas._objects.length; i++) {
                if (canvas._objects[i].cacheKey == 'texture0') {
                    canvas.remove(canvas._objects[i])
                }
            }
        });
    }
}

function initFabricjs() {
    var defaultUrl = 'img/Topi-lebaran-1.png';
    canvas = new fabric.Canvas('tcanvas', {
        hoverCursor: 'pointer',
        selection: true,
        selectionBorderColor: 'blue'
    });

    // addFabricImage(defaultUrl);
    // templateInit();

}

function addFabricText(text, fontType, fontColor) {
    var textSample = '';
    if (fontColor && fontType) {
        textSample = new fabric.Text(text, {
            left: 200 / 2,
            top: 50,
            textAlign: 'right',
            fontFamily: fontType,
            angle: 0,
            fill: fontColor,
            originX: 'center',
            originY: 'center',
            fontSize: 25,
            fontWeight: '',
            hasRotatingPoint: true
        });
    } else {
        textSample = new fabric.Text(text, {
            left: 200 / 2,
            top: 50,
            textAlign: 'right',
            fontFamily: 'helvetica',
            angle: 0,
            fill: '#000000',
            originX: 'center',
            originY: 'center',
            fontSize: 25,
            fontWeight: '',
            hasRotatingPoint: true
        });
    }
    canvas.add(textSample);
}
$('#qty').on('input', (e) => {
    if (e.target.value <= 0 || e.target.value == '') {
        $('#listCustomTeks').empty();
        for (let i = 0; i < canvas._objects.length; i++) {
            if (canvas._objects[i]._element == undefined && canvas._objects[i].text == "") {
                canvas.remove(canvas._objects[i]);
                // console.log(canvas._objects[i]);
            }
        }
    } else {
        $('#listCustomTeks').empty();
        for (let i = 0; i < Number(e.target.value); i++) {
            addFabricText('');
            addTextInput(i, canvas._objects[i].text);
        }
        console.log(canvas._objects);
    }
})

function addTextInput(id, text) {
    getColor().then(() => {
        //console.log(productColor);

        idPodetail = eval('[' + idPodetail + ']');
        var listCustomTeksInput = `
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="customTeks-` + id + `" onclick="selectText(` + id + `)" oninput="addText(` + id + `)" id="customTeks-` + id + `" placeholder="Custom Teks ` + id + `" value="` + text + `">
                        </td>
                        <td>
                        <select class="form-select"  name="selectFont" onclick="selectText(` + id + `)" id="selectFont-` + id + `"  onchange="changeFont(value, ` + id + `)"> aria-label="Default select example" >
                        <option selected>Font</option>
                        ` +

            fonts.map((font) => {
                return (
                    `<option style="font-family: ` +
                    font +
                    `" value="` +
                    font +
                    `">` +
                    font +
                    `</option>`
                );
            }) +
            `
                    </select>
                        </td>
                        <td>
                        <select class="form-select js-example-basic-single"  name="hatColor" onclick="selectText(` + id + `)"  id="hatColor-` + id + `" onchange="changehatColor(value, ` + id + `)"  aria-label="Default select example">
                        <option selected>Warna & Ukuran</option>
                        ` +
            productColor.map((color, index) => {
                return (
                    `<option id="` + idPodetail[index] + `" style="text-transform:capitalize;" value="` +
                    color +
                    `">` +
                    color +
                    `</option>`
                );
            }) +
            `
                        </select>
                        </td>
                    </tr>`;

        $('#listCustomTeks').append(listCustomTeksInput).html();
        $('.js-example-basic-single').select2({
            theme: 'bootstrap-5',
            width: 'resolve',
            dropdownCssClass: 'font-13'
        });
        for (let i = 0; i < Number($('#qty').val()); i++) {
            $('#hatColor-' + i).on('change`', (e) => {
                changehatColor(e.target.value, i);
            })
        }
    })

}

function selectText(id) {
    var customTeks = 'customTeks-' + id;
    var hatColor = 'hatColor-' + id;
    console.log(customTeks);
    for (let i = 0; i < canvas._objects.length; i++) {
        if (canvas._objects[i].text != undefined) {
            if (canvas._objects[i].text == $('#' + customTeks).val()) {
                canvas._objects[i].set('opacity', 1);
                canvas.renderAll();
            } else {
                console.log('hilang')
                canvas._objects[i].set('opacity', 0);
                canvas.renderAll();
            }
        }
    }
    if ($('#' + hatColor).val() != 'Warna Topi') {
        changehatColor($('#' + hatColor).val(), id);
    }

}

function addText(val) {
    canvas._objects[val].set('text', $('#customTeks-' + val).val());
    canvas._objects[val].opacity = 1;
    canvas.renderAll();
}

function changeFont(font, id) {
    canvas._objects[id].fontFamily = font
    canvas.renderAll();
}

function changehatColor(color, id) {
    if (color == 'Warna & Ukuran') return;
    var words = color.split(" ");
    var src = window.location.origin + '/custom-wnj/miki-hat/img/miki-' + words[1] + '.jpg';
    // var src = window.location.origin + '/custom/miki-hat/img/miki-' + words[1].toLowerCase() + '.jpg';
    $('#mikiHat')[0].src = src;
    if ($('#hatColor-' + id).val().split(" ")[1].toLowerCase() == 'cream' || $('#hatColor-' + id).val().split(" ")[1].toLowerCase() == 'silver' || $('#hatColor-' + id).val().split(" ")[1].toLowerCase() == 'white' || $('#hatColor-' + id).val().split(" ")[1].toLowerCase() == 'grey') {
        canvas._objects[id].set('fill', 'black');
        canvas.renderAll();
    } else {
        canvas._objects[id].set('fill', 'gold');
        canvas.renderAll();
    }
}



function customTeks() {
    const daftarTeks = [];
    for (let i = 0; i < Number($('#qty').val()); i++) {
        daftarTeks.push($('#customTeks-' + i).val());
    }
    return daftarTeks;
}

function addToCartButton1() {
    var customOrderDataMikiHat = [];
    for (let i = 0; i < Number($('#qty').val()); i++) {
        customOrderDataMikiHat.push({
            qty: 1,
            date: new Date("2015-03-25"),
            warnaTopi: $('#hatColor-' + i).val(),
            ukuranTopi: $('#selectUkuran-' + i).val(),
            fontType: $('#selectFont-' + i).val(),
            customTeks: $('#customTeks-' + i).val(),
            idpo: 222,
            idadmin: 333,
            idpodetail: 333,
            harga: 350000,
        })
    }
    var username = localStorage.getItem('username');
    var qty = document.getElementById('qty').value;
    var total = document.getElementById('total').value;

    for (x = 0; x < qty; x++) {

        //----post data-------------
        $.post('https://wnj1.com/custom/miki-hat/send_data.php', {
            idadmin: username,
            font: customOrderDataMikiHat[x].fontType,
            colortopi: customOrderDataMikiHat[x].warnaTopi,
            idpodetail: idpodetail,
            idproduk: idproduk,
            nama: customOrderDataMikiHat[x].customTeks,
            jumlah: qty,
            total: total
        }, function (data) {
            console.log(data);
        });

    }

}
