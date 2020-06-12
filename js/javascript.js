const xhr = new XMLHttpRequest();

xhr.open('GET', 'articulos.json', true);

xhr.onload = function(){
    if(this.status === 200){
        const itm = JSON.parse(this.responseText);
        const ni = itm.celulares_accesorios[0].micas_protectoras.length;
       //console.log(itm.celulares_accesorios[0].cases_fundas);
        let m_p = '';
        let c = '';
        let sprites = '';
        for (var i=0; i<ni;i++){
            sprites += `
            <div class="div-img contenedor img-fluid">
                            <a href="productPage.html"><img src="${itm.celulares_accesorios[0].cases_fundas[i].img}" alt="" class="img img-fluid"></a>
                            <h1>${itm.celulares_accesorios[0].cases_fundas[i].categorie}</h1>
                            <p>${itm.celulares_accesorios[0].cases_fundas[i].name}</p>
                            <h3>$${itm.celulares_accesorios[0].cases_fundas[i].precio}</h3>
                            <a href=""><div class="add-cart pl-5 pr-5 pt-2 pb-2 m-0">Agregar al carrito</div></a>
                            </div>`;
            m_p += `
                            <div class="div-img contenedor img-fluid">
                                            <a href="productPage.html"><img src="${itm.celulares_accesorios[0].micas_protectoras[i].img}" alt="" class="img img-fluid"></a>
                                            <h1>${itm.celulares_accesorios[0].micas_protectoras[i].categorie}</h1>
                                            <p>${itm.celulares_accesorios[0].micas_protectoras[i].name}</p>
                                            <h3>$${itm.celulares_accesorios[0].micas_protectoras[i].precio}</h3>
                                            <a href=""><div class="add-cart pl-5 pr-5 pt-2 pb-2 m-0">Agregar al carrito</div></a>
                                            </div>`;
            c += `
                                            <div class="div-img contenedor img-fluid">
                                                            <a href=""><img src="${itm.celulares_accesorios[0].cargadores[i].img}" alt="" class="img img-fluid"></a>
                                                            <h1>${itm.celulares_accesorios[0].cargadores[i].categorie}</h1>
                                                            <p>${itm.celulares_accesorios[0].cargadores[i].name}</p>
                                                            <h3>$${itm.celulares_accesorios[0].cargadores[i].precio}</h3>
                                                            <a href=""><div class="add-cart pl-5 pr-5 pt-2 pb-2 m-0">Agregar al carrito</div></a>
                                                            </div>`;
        }
         document.getElementById('cases_fundas').innerHTML = sprites;
         document.getElementById('micas_protectoras').innerHTML = m_p;
         document.getElementById('cargadores').innerHTML = c;
    }
}
xhr.send();