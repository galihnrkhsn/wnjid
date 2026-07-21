var fonts = [
    'amazing mother',
    'a awal Ramadan',
    'blackjack',
    'halaney demo'
];
$('#templateBaju1').change((e)=>{
    changeTemplate(1)
    canvas.renderAll();
})  

        $('#templateBaju2').change((e)=>{
    changeTemplate(2)
    canvas.renderAll();
})  

$('#templateBaju3').change((e)=>{
    changeTemplate(3)
    canvas.renderAll();
}) 

$('#templateBaju4').change((e)=>{
    changeTemplate(4)
    canvas.renderAll();
}) 

$('#templateBaju5').change((e)=>{
    changeTemplate(5)
    canvas.renderAll();
}) 

$('#templateBaju6').change((e)=>{
    changeTemplate(6)
    canvas.renderAll();
}) 

$('#templateBaju0').change((e)=>{
    changeTemplate(0)
    canvas.renderAll();
}) 

        $('#customText').on('input',(e)=>{
            canvas._objects[0].text = e.target.value;
            canvas.renderAll();
        })
        $('#customFont').change((e)=>{
            canvas._objects[0].set('fontFamily', e.target.value);
            canvas.renderAll();
        })
        
        $('#customFont').ready((e)=>{
            canvas._objects[0].set('fontFamily', e.target.value);
            canvas.renderAll();
        })        
        
        //INIT FABRICJS
        function addFabricText(text, fontType, fontColor) {
    var textSample = '';
    textSample = new fabric.Text(text, {
            left: 70,
            top: 100,
            textAlign: 'right',
            fontFamily: fontType,
            angle: 0,
            fill: fontColor,
            originX: 'center',
            originY: 'center',
            fontSize: 20,
            fontWeight: '',
            hasRotatingPoint: true
        });
    canvas.add(textSample)}
    function initFabricjs() {
    console.log('init')
    var defaultUrl = 'img/baju.png';
    canvas = new fabric.Canvas('tcanvas', {
        hoverCursor: 'pointer',
        selection: true,
        selectionBorderColor: 'blue'
    });

    addFabricImage(defaultUrl);
    templateInit();

}
function addTemplateImage(url) {
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
        console.log(canvas._objects)
        // canvas.bringToFront(img);
    });
}
function changeTemplate(id) {
    var url = window.location;
    var fullSrc = url + 'img/KaosLebaran/KL_00' + id + '.png';
    var src = 'img/KaosLebaran/KL_00' + id + '.png';
    templateNumber = id;
    canvas.remove(canvas._objects[1])
    addTemplateImage(src)
}
function addFabricImage(url) {
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
}
function templateInit() {
    var template = ``;
    for (let i = 1; i <= templateBaju.length; i++) {
        template += `
            <div onclick="changeTemplate(` + i + `)" style="background:white;border-radius:5px;border:1px solid grey;padding:5px;margin-top:5px;">
                <img src="img/KaosLebaran/KL_00` + i + `.png" style="width: 50px;">
            </div>
        `;
    }
    $('#templateBaju').append(template).html();
}


initFabricjs();
addFabricText('','Amazing Mother','black');

       $('#kategori').ready(function(e){
           var warna = $('#kategori').find(':selected')[0].innerHTML.toLowerCase();
            $('#shirtDiv').css('background-color', warna);
            console.log(warna)
            // if(warna == 'black'){
                canvas._objects[0].set('fill', 'white');
                canvas.renderAll();
            // }
       })
       $('#kategori').change(function(e){
           var warna = $('#kategori').find(':selected')[0].innerHTML.toLowerCase();
            $('#shirtDiv').css('background-color', warna);
            console.log(warna)
            // if(warna == 'black'){
                canvas._objects[0].set('fill', 'white');
                canvas.renderAll();
            // }
       })