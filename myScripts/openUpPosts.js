var maindiv = document.getElementsByTagName("article")[0].children[0].children[0];

var filaInicial = prompt('Fila inicial:', '1');
var filaFinal = prompt('Fila final:', '2');


for (var i = filaInicial-1; i < filaFinal; i++){
    console.log("ejecuta: "+i)
    for(var j = 0; j < 3; j++){
        var w;
        var url =  maindiv.children[i].children[j].children[0].href;
       
        console.log(url);
        w = window.open(url, "_blank");
        setTimeout(function() { window.focus() },500);
    }
}




