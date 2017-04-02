/**
 * Created by Florent on 25/03/2017.
 */


// Définition des variables

var sectionElt = document.getElementsByTagName('section');
var articleElt = document.getElementsByClassName('item');
var articleDescriptionElt = document.getElementsByClassName('article-caption');

console.log(articleDescriptionElt.length);

for(i=0; i<articleElt.length; i++){
    articleElt[i].addEventListener('mouseover', function(){
        this.lastElementChild.classList.add('article-caption-show');
    });
    articleElt[i].addEventListener('mouseleave', function(){
        this.lastElementChild.classList.remove('article-caption-show');
    });
}

// On redéfinit la hauteur la section lors du redimensionnement de la fenêtre

window.onresize = resize;

function resize(){
    console.log(window.innerWidth);
    sectionElt[0].style.height = window.innerWidth/2.08 + 'px';
}
