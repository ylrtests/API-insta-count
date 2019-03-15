var main = document.getElementsByClassName("SCxLW uzKWK")[0];
var nodos = main.children[0].children[0].children

var usersList = [];


for(i=0; i<nodos.length; i++){
	var temp = nodos[i].children[1].children[0].innerText;
	console.log(temp);
	usersList.push(temp);
}

var jsonObject = JSON.stringify(usersList);
console.log(jsonObject);
