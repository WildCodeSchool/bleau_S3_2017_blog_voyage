// Définition des variables

var sectionElt = document.getElementsByTagName('section');

// On redéfinit la hauteur la section lors du redimensionnement de la fenêtre

window.onresize = resize;

function resize(){
	console.log(window.innerWidth);
	sectionElt[0].style.height = window.innerWidth/2.08 + 'px';
}