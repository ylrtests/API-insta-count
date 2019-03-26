
/**
 * 
 * Obtener Lista de Fans que le hicieron like a un post
 * BvF6u95lRqC
 */

// var postId;
// var w;

// var postId = prompt('ID del post:', '');
// 	openWindow();

// function openWindow(){
// 	w = window.open("https://www.instagram.com/p/" + postId, "_self");
// 	setTimeout(function(){
// 		console.log('genial chrome')
// 	},5000);
// }

function openAnchor(){
	document.getElementsByTagName('a')[3].click();
}

var offset = 0;
var tempOffset = 0;
var flag = 0;

openAnchor();

window.onscroll = function () {
	var d = document.documentElement;
	offset = d.scrollTop + window.innerHeight;
};

var intervalo = setInterval(function () {

	if (offset == tempOffset) {
		flag++;
		if (flag >= 15) {
		//if (flag >= 30) { Cuando se requieran todos los usuarios
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
	console.log('Estoy haciendo Scroll... Flag:'+flag);

}, 500);
//}, 1000); Cuando se requieran todos los usuarios

function obtenerLista() {
	var mainarray = document.getElementsByTagName("main")[0];
	//var mainarray = document.getElementsByClassName("SCxLW uzKWK ")[0];
	var nodos = mainarray.children[0].children[0].children;

	var usersList = [];


	for (i = 0; i < nodos.length; i++) {
		var temp = nodos[i].children[1].children[0].innerText;
		//console.log(temp);
		usersList.push(temp);
	}

	//usersList = JSON.stringify(usersList);

	var jsonObject = {
		"id_insta": window.location.pathname.substring(3).split('/liked_by/')[0],
		"fansUsernames": usersList
	}

	jsonObject = JSON.stringify(jsonObject);
	console.log(jsonObject);

	// fetch('http://instacount:8080/api/post/add/likes', {
	// 	method: 'post',
	// 	headers: {
	// 		'Content-Type': 'application/json'
	// 	},
	// 	body: JSON.stringify(jsonObject)
	// }).then(res => res.json())
	// 	.then(res => console.log(res));

}
