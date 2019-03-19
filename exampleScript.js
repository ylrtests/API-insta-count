
/**
 * 
 * Obtener Lista de Fans que le hicieron like a un post
 * Ojo: Añadir clase del elemento <main>, ya que esta puede cambiar.
 * 
 */

var mainarray = document.getElementsByClassName("SCxLW uzKWK ")[0];
var nodos = mainarray.children[0].children[0].children

var usersList = [];


for(i=0; i<nodos.length; i++){
	var temp = nodos[i].children[1].children[0].innerText;
	//console.log(temp);
	usersList.push(temp);
}

var jsonObject = JSON.stringify(usersList);
console.log(jsonObject);


/**
 * 
 * 
 * 
 *  Obtener Lista de Fans que el usuario sigue
 *  Ojo: Añadir clase del elemento <ul>, ya que esta puede cambiar.
 * 
 * 
 * 
 */
var mainarray = document.getElementsByClassName("jjbaz _6xe7A")[0];
var nodos = mainarray.children[0].children;

var usersList = [];

for(i=0; i<nodos.length; i++){
	var temp = nodos[i].children[0].children[0].children[1].children[0].innerText
	//console.log(temp);

	//ignorar usuarios verificados.
	if(!temp.includes('Verificado')){
		usersList.push(temp);
	}
	
}


var jsonObject = JSON.stringify(usersList);
console.log(jsonObject);