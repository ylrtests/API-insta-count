/**
 * 
 * 
 * 
 *  Obtener Lista de Fans que el usuario sigue o seguidores
 * 
 * 
 * 
 */
var offset = 0;
var tempOffset = 0;
var flag = 0;

window.onscroll = function () {
	var d = document.documentElement;
	offset = d.scrollTop + window.innerHeight;
};

var intervalo = setInterval(function () {

	if (offset == tempOffset) {
		flag++;
		if (flag >= 10) {
			console.log("Ya obtuve todos los usuarios...")
			clearInterval(intervalo);
			obtenerLista();
		}	
	}
	else {
		flag = 0;
	}

	tempOffset = offset;
	window.scrollTo(0, document.body.scrollHeight);
	console.log('Estoy haciendo Scroll...');

}, 500);

function obtenerLista() {
var ul = document.getElementsByTagName("ul")[0];
var nodos = ul.children[0].children;

var usersList = [];

for(i=0; i<nodos.length; i++){
	var temp = nodos[i].children[0].children[0].children[1].children[0].innerText
	console.log(temp);

	//ignorar usuarios verificados.
	if(!temp.includes('Verificado')){
		usersList.push(temp);
	}
	
}


var jsonObject = JSON.stringify(usersList);
console.log(jsonObject);
}
