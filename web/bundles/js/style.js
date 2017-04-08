/**
 * Created by Florent on 25/03/2017.
 */


// Définition des variables

var sectionElt = document.getElementsByTagName('section');
var articleElt = document.getElementsByClassName('article-custom');
var articleDescriptionElt = document.getElementsByClassName('article-caption');
var imgElt = document.getElementsByClassName('image');
var containerDateElt = document.getElementsByClassName('container-dates');

containerDateElt[0].style.minHeight = window.innerHeight - 100 + 'px';

if(window.innerWidth>=768)
{
    for (i = 0; i < imgElt.length; i++) {
        // On mesure hauteur et largueur
        var imgHeight = parseFloat(getComputedStyle(imgElt[i]).height);
        var imgWidth = parseFloat(getComputedStyle(imgElt[i]).width);

        // Si format portrait, ajout classe correspondante
        if (imgHeight > imgWidth) {
            imgElt[i].classList.add('vertical-img-resize');
        }
        else {
            imgElt[i].classList.add('horizontal-img-resize');
        }
    }
}

for(i=0; i<articleElt.length; i++){
    articleElt[i].addEventListener('mouseover', function(){
        this.lastElementChild.classList.add('article-caption-show');
    });
    articleElt[i].addEventListener('mouseleave', function(){
        this.lastElementChild.classList.remove('article-caption-show');
    });
}

// On redéfinit la hauteur la section lors du redimensionnement de la fenêtre

sectionElt[0].style.height = window.innerWidth/2.08 + 'px';
