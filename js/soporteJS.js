const xhr = new XMLHttpRequest();

xhr.open('GET', 'marcas.json', true);

xhr.onload = function(){
    if(this.status === 200){
        const itm = JSON.parse(this.responseText);
        const ni = Object.keys(itm).length;
      //  console.log(itm);
       //console.log(itm.celulares_accesorios[0].cases_fundas);
        let m_p = '';
        let c;
        let sprites = '';
        for (var i=0; i<ni;i++){
            c = Object.keys(itm)[i];
            sprites += `
            <div class="div-img contenedor">
             <img src="${itm[c][0].Imagen}" alt="" class="img">
            </div>`;
        }
     
         document.getElementById('marcas').innerHTML = sprites;
    }
}
xhr.send();