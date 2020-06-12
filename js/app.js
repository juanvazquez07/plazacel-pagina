const api = "http://localhost:80/sitioPlazaCell/";
const client = "http://localhost:80/sitioPlazaCell/";

/*function getSesion() {
    var sesion = localStorage.getItem("ltareas_sesion");
    
    if (sesion != null && sesion != "")
    {
        var sesion_json = JSON.parse(sesion);

        return sesion_json;
    }
    
    return null;
}*/

function refreshToken() {
  //  var sesion = getSesion();

    if (sesion == null) {
        window.location.href = client;
    }

    var xhttp = new XMLHttpRequest();

   // xhttp.open("PATCH", api + "sesiones/" + sesion.id_sesion, false);
    xhttp.setRequestHeader("Authorization", "OTE4MWU0MzQ2Nzc1MjFiNjY2NDBkMTliZDQyOGUxY2NiMzkwZDVhYWFiMjM1ZWE2MTU5MTcyMzc5OQ==");
    xhttp.setRequestHeader("Content-Type", "application/json");

    var json = { "token_actualizacion": "OTE4MWU0MzQ2Nzc1MjFiNjY2NDBkMTliZDQyOGUxY2NiMzkwZDVhYWFiMjM1ZWE2MTU5MTcyMzc5OQ==" };
    var json_string = JSON.stringify(json);

    xhttp.send(json_string);

    var data = JSON.parse(xhttp.responseText);

    if (data.success === true){
        localStorage.setItem('ltareas_sesion', JSON.stringify(data.data));
        window.location.href = client;
    }
    else{
        alert(data.messages);
        window.location.href = client;
    }
}