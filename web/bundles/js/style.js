/**
 * Created by Florent on 25/03/2017.
 */


// Définition des variables

var sectionElt = document.getElementsByTagName('section');
var articleElt = document.getElementsByClassName('article-custom');
var imgElt = document.getElementsByClassName('image');
var containerDateElt = document.getElementsByClassName('container-dates');
var buttonPublishElt = document.getElementById('publish');
var buttonSeeElt = document.getElementById('see');
var formElt = document.querySelectorAll('.comments > form');
var commentsElt = document.getElementsByClassName('comments-block');
var modalImageElt = document.querySelectorAll('.modal-image > img');
var bodyElt = document.getElementsByTagName('body');

function load(){
    if (window.innerWidth >= 768) {
        for (i=0; i < imgElt.length; i++) {
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
}

for(i=0; i<articleElt.length; i++){
    articleElt[i].addEventListener('mouseover', function(){
        this.lastElementChild.classList.add('article-caption-show');
    });
    articleElt[i].addEventListener('mouseleave', function(){
        this.lastElementChild.classList.remove('article-caption-show');
    });
}

// On définit une taille miminum du container de la page date lorsque le visiteur arrive
// sur la page afin que le footer soit bien placé en bas de la page
if(containerDateElt[0]) {
    containerDateElt[0].style.minHeight = window.innerHeight - 100 + 'px';
}

// On redimensionne la section de la page d'accueil en fonction de la largeur de la fenêtre
if (sectionElt[0]) {
    sectionElt[0].style.height = window.innerWidth / 2.08 + 'px';
}

if(buttonPublishElt && buttonSeeElt){
    buttonPublishElt.addEventListener('click', function(){
        if(formElt[0].classList.contains('show')){
            formElt[0].classList.remove('show');
            buttonPublishElt.textContent = "Publier un commentaire";
        }
        else{
            formElt[0].classList.add('show');
            buttonPublishElt.textContent = "Masquer le formulaire";
        }
    });

    buttonSeeElt.addEventListener('click', function(){
        if(commentsElt[0].classList.contains('show')){
            commentsElt[0].classList.remove('show');
            buttonSeeElt.textContent = "Voir les commentaires";
        }
        else{
            commentsElt[0].classList.add('show');
            buttonSeeElt.textContent = "Masquer les commentaires";
        }
    });
}

// Affichage en plein écran de(s) photo(s) de l'article

/*
if(modalImageElt){
    for(i=0; i<modalImageElt.length; i++){
        modalImageElt[i].addEventListener("click", function(e){
           this.parentNode.classList.add('modal-image-big');
           bodyElt[0].style.overflow = "hidden";

        });
    }
}
*/