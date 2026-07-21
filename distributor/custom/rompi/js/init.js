var ColorName = "";

//SET INI JNGAN LUPA
var idPodetail = "";
var idPo = "";
var harga = "";
/////////
var productColor = [
    "Cream",
    "Navy",
    "Black",
    "Grey"
];

const rompiColorStore = [];
const fontTypeStore = [];
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const storedTeks = urlParams.get('teks');
const idadmin = urlParams.get('idadmin');
const idpoproduk = urlParams.get('idpoproduk');
async function getColor() {
    return productColor = [
        "nude",
        "navy",
        "black",
        "medium-denim"
    ];
}

var mockupWrapper = $('#mockupWrapper');
var mockupImg = $('#mockup');
var orderForm = $('#orderForm');
var canvas;

var fonts = [
    'Halaney Demo',
    'BlackJack',
    'Awal Ramadhan',
    'Amazing Mother',
    'Helvetica Neue'
];

// if (window.location.search == '') {
//     if (confirm('Please Login First') == true) {
//         window.location.href = "https://wnj1.com/"
//     } else {
//         window.location.href = "https://wnj1.com/"
//     }
// }
var group;
$(document).ready(() => {
    initFabricjs();
    $('#qty').val(1);
    addFabricText(0, '');
    addFabricText2(0, '');
    addTextInput(0, '', '', '');
})



function initFabricjs() {
    canvasTop = new fabric.Canvas('tcanvas', {
        hoverCursor: 'pointer',
        selection: true,
        selectionBorderColor: 'blue'
    });
    canvasBottom = new fabric.Canvas('tcanvas2', {
        hoverCursor: 'pointer',
        selection: true,
        selectionBorderColor: 'blue'
    });

}

function addFabricText(id, text, fontType, fontColor) {
    var textSample = '';
    if (fontColor && fontType) {
        textSample = new fabric.Text('text', {
            left: 50,
            top: 10,
            textAlign: 'center',
            fontFamily: fontType,
            angle: 0,
            fill: fontColor,
            originX: 'center',
            originY: 'center',
            fontSize: 15,
            fontWeight: '',
            hasRotatingPoint: true,
            id: id
        });
    } else {
        textSample = new fabric.Text(text, {
            left: 50,
            top: 10,
            textAlign: 'center',
            fontFamily: 'Halaney Demo',
            angle: 0,
            fill: '#000000',
            originX: 'center',
            originY: 'center',
            fontSize: 15,
            fontWeight: '',
            hasRotatingPoint: true,
            id: id
        });
    }
    canvasTop.add(textSample);
}
function addFabricText2(id, text, fontType, fontColor) {
    var textSample = '';
    if (fontColor && fontType) {
        textSample = new fabric.Text('text', {
            left: 50,
            top: 0,
            textAlign: 'center',
            fontFamily: fontType,
            angle: 0,
            fill: fontColor,
            originX: 'center',
            originY: 'center',
            fontSize: 17,
            fontWeight: '',
            hasRotatingPoint: true,
            id: id
        });
    } else {
        textSample = new fabric.Text(text, {
            left: 50,
            top: 10,
            textAlign: 'center',
            fontFamily: 'Halaney Demo',
            angle: 0,
            fill: '#000000',
            originX: 'center',
            originY: 'center',
            fontSize: 17,
            fontWeight: '',
            hasRotatingPoint: true,
            id: id
        });
    }
    canvasBottom.add(textSample);
}
let lastQty = 1;
let addCount = 0;

var indexes = [0, 1];
$('#qty').on('input', (e) => {
    if (e.target.value <= 0 || e.target.value == '') {
        $('#listCustomTeks').empty();
        canvasTop.remove(...canvasTop.getObjects());
        canvasBottom.remove(...canvasBottom.getObjects());
    } else {
        for (let i = 0; i < Number(e.target.value); i++) {
            addFabricText(i, '');
            addFabricText2(i, '');
            addTextInput(i, '');
        }
    }
})
function addTextInput(id, text) {
    getColor().then(() => {
        idPodetail = eval('[' + idPodetail + ']');
        idPo = eval('[' + idPo + ']');
        harga = eval('[' + harga + ']');
        var listCustomTeksInput = `
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="customTeksAtas-` + id + `" onclick="selectText(this,` + id + `)" oninput="addText(this,` + id + `)" id="customTeksAtas-` + id + `" placeholder="Custom Teks Atas ` + (id + 1) + `" value="` + canvasTop._objects[id].text + `">
                        </td>
                        <td>
                        <select class="form-select"  name="selectFontAtas" onclick="selectText(this,` + id + `)" id="selectFont-` + id + `"  onchange="changeFont(value, ` + id + `)"> aria-label="Default select example" >
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

                    </tr>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="customTeksBawah-` + id + `" onclick="selectText(this,` + id + `)" oninput="addText(this,` + id + `)" id="customTeksBawah-` + id + `" placeholder="Custom Teks Bawah ` + (id + 1) + `" value="` + canvasBottom._objects[id].text + `">
                        </td>
                         <td>
                        <select class="form-select js-example-basic-single"  name="rompiColor" onclick="selectText(this,` + id + `)"  id="rompiColor-` + id + `" onchange="changerompiColor(` + id + `)"  aria-label="Default select example">
                        <option selected>Warna & Ukuran</option>
                        ` +
            productColor.map((color, index) => {
                return (
                    `<option class="` + idPo[index] + `"  style="text-transform:capitalize;" value="` +
                    idPodetail[index] +
                    `">` +
                    color +
                    `</option>`
                );
            }) +
            `
                        </select>
                        </td>

                    </tr>
                    `
            ;
        $('#listCustomTeks').append(listCustomTeksInput).html();
        $('.js-example-basic-single').select2({
            theme: 'bootstrap-5',
            width: 'resolve',
            dropdownCssClass: 'font-13'
        });
    })

}

function selectText(el, id) {
    var customTeks = '';
    if (el.name.includes('Atas')) {
        customTeks = 'customTeksAtas-' + id;
    } else {
        customTeks = 'customTeksBawah-' + id;
    }
    var rompiColor = 'rompiColor-' + id;
    for (let i = 0; i < $('#qty').val(); i++) {
        if (canvasTop._objects[i].text == $('#customTeksAtas-' + id).val() || canvasBottom._objects[i].text == $('#customTeksBawah-' + id).val()) {
            canvasTop._objects[i].set('opacity', 1);
            canvasBottom._objects[i].set('opacity', 1);
            canvasTop.renderAll();
            canvasBottom.renderAll();
        } else {
            canvasTop._objects[i].set('opacity', 0);
            canvasBottom._objects[i].set('opacity', 0);
            canvasTop.renderAll();
            canvasBottom.renderAll();
        }
    }
    if ($('#' + rompiColor).val() != 'Warna & Ukuran') {
        changerompiColor(id);
    }

}

function addText(el, val) {
    if (el.id.includes('Atas')) {
        // console.log($('#customTeksAtas-' + val).val());
        canvasTop._objects[val].set('text', $('#customTeksAtas-' + val).val());
    } else {
        // console.log($('#customTeksBawah-' + val).val());
        canvasBottom._objects[val].set('text', $('#customTeksBawah-' + val).val());
    }
    canvasTop._objects[val].opacity = 1;
    canvasBottom._objects[val].opacity = 1;
    canvasTop.renderAll();
    canvasBottom.renderAll();
}

function changeFont(font, id) {
    if (canvasTop._objects.length > 2 && id != 0) {
        id -= 1;
    }
    fontTypeStore.push(font);
    localStorage.setItem('fontTypeStore', fontTypeStore);
    canvasTop._objects[id].fontFamily = font
    canvasBottom._objects[id].fontFamily = font
    canvasTop.renderAll();
    canvasBottom.renderAll();
}

function changerompiColor(id) {
    rompiColorStore.push($('#rompiColor-' + id).find(':selected')[0].innerHTML);
    localStorage.setItem('rompiColorStore', rompiColorStore);
    // if (id < 1) return false;
    var words = $('#rompiColor-' + id).find(':selected')[0].innerHTML.split(" ");
    var src = window.location.origin + '/distributor/custom/rompi/img/rompi-' + words[0] + '.jpg';
    // var src = window.location.origin + '/custom/rompi/img/miki-' + words[1].toLowerCase() + '.jpg';
    $('#mikirompi')[0].src = src;
    if (words[0].toLowerCase() == 'nude' || words[0].toLowerCase() == 'silver' || words[0].toLowerCase() == 'white' || words[0].toLowerCase() == 'medium-denim') {
        canvasTop._objects[id].set('fill', 'black');
        canvasBottom._objects[id].set('fill', 'black');
        canvasTop.renderAll();
        canvasBottom.renderAll();
    } else {
        canvasTop._objects[id].set('fill', 'gold');
        canvasBottom._objects[id].set('fill', 'gold');
        canvasTop.renderAll();
        canvasBottom.renderAll();
    }
    if (words[0].toLowerCase() == 'medium-denim' || words[0].toLowerCase() == 'black') {
        $('#drawingArea').css('left', '55px')
        $('#drawingArea2').css('left', '55px')
    }else {
        $('#drawingArea').css('left', '90px')
        $('#drawingArea2').css('left', '90px')
    }
}


function customTeks() {
    const daftarTeks = [];
    let temp;
    for (let i = 0; i < Number($('#qty').val()); i++) {
        temp = {
            'textAtas': canvasTop._objects[i].text,
            'textBawah': canvasBottom._objects[i].text,
        }
        daftarTeks.push(temp);
    }
    return daftarTeks;
}
function customWarna() {
    const daftarTeks = [];
    for (let i = 0; i < Number($('#qty').val()); i++) {
        daftarTeks.push(Number($('#rompiColor-' + i).val()));
    }
    return daftarTeks;
}
function customFont() {
    const daftarTeks = [];
    for (let i = 0; i < Number($('#qty').val()); i++) {
        daftarTeks.push($('#selectFont-' + i).val());
    }
    return daftarTeks;
}

function addToCartButton() {
    let data = [];
    for (let i = 0; i < $('#qty').val(); i++) {
        data.push({
            idadmin: idadmin,
            'noRompi': i + 1,
            font: customFont()[i],
            customText: customTeks()[i],
            colortopi: customWarna(),
            idpodetail: customWarna(),
            idpo: Number($('#rompiColor-0').find(':selected').attr('class')),
            idproduk: idpoproduk,
            jumlah: qty
        })
    }
    //KIRIM DATA
    console.log(data);
}