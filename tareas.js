
let categorias = [];
//Listeners
eventListener();

function eventListener() {
    //Cambia categoría
   // listaCategorias.addEventListener("change", getTareas);

    //Contenido cargado
    document.addEventListener("DOMContentLoaded", documentListo);
}

//Funciones

//Obtener las tareas en base a los filtros
function getProductos() {
    //var sesion = getSesion();

    //if (sesion == null) {
      //  window.location.href = client;
    //}

    var xhttp = new XMLHttpRequest();

   // var categoria_id = listaCategorias.value;
   // if (categoria_id != 0) {
     //   xhttp.open("GET", api + "tareas/categoria_id=" + categoria_id, true);
    //}
    //else {
        xhttp.open("GET", api + "tareas", true);
   // }
   
    //xhttp.setRequestHeader("Authorization", sesion.token_acceso);

    xhttp.onload = function() {
        if (this.status == 200) {
            const data = JSON.parse(this.responseText);
            //console.log(data);
            if(data.success == true){
                var productos = data.data.productos;
                let m_p = '';
                let c = '';
                let sprites = '';
                let descripcion = "";
                productos.forEach(producto => {
                    descripcion = producto.descripcion !== null ? producto.descripcion : "";
                    
                    if(producto.ide <= 5){
                        sprites += 
                        `<div class="div-img contenedor img-fluid">
                        <a href="productPage.html"><img src="${producto.imagen}" alt="" class="img img-fluid"></a>
                        <h1>CASES Y FUNDAS</h1>
                        <p>${producto.nombre}</p>
                        <h3>$${producto.precio}</h3>
                        <a href="carrito.html"><div class="add-cart pl-5 pr-5 pt-2 pb-2 m-0 agregar_carrito" data-id="${producto.ide}">Agregar al carrito</div></a>
                        </div>`;
                    }else{
                        m_p += 
                        `<div class="div-img contenedor img-fluid">
                        <a href="productPage.html"><img src="${producto.imagen}" alt="" class="img img-fluid"></a>
                        <h1>MICAS PROTECTORAS</h1>
                        <p>${producto.nombre}</p>
                        <h3>$${producto.precio}</h3>
                        <a href="carrito.html"><div class="add-cart pl-5 pr-5 pt-2 pb-2 m-0 agregar_carrito" data-id="${producto.ide}">Agregar al carrito</div></a>
                        </div>`;
                    
                    }
                });
                document.getElementById('cases_fundas').innerHTML = sprites;
                document.getElementById('micas_protectoras').innerHTML = m_p;
            }
             //console.log(itm.celulares_accesorios[0].cases_fundas);
            
            
           // document.getElementById('micas_protectoras').innerHTML = m_p;
            //document.getElementById('cargadores').innerHTML = c;
        }
        
    };

    xhttp.send();
}

//Cargar Tareas en la lista
function documentListo() {
   // getCategorias();
    getProductos();
}