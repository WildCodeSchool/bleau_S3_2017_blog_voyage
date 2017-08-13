/**
 * Created by Florent on 25/03/2017.
 */

// Définition des variables

var sectionElt = document.getElementsByTagName('section');
var rowcustomElt = document.getElementsByClassName('row-custom');
var articleElt = document.getElementsByClassName('article-custom');
var imgElt = document.getElementsByTagName('img');
var containerDateElt = document.getElementsByClassName('container-dates');
var buttonPublishElt = document.getElementById('publish');
var buttonPublishEsElt = document.getElementById('publishEs');
var buttonSeeElt = document.getElementById('see');
var buttonSeeEsElt = document.getElementById('seeEs');
var formElt = document.querySelectorAll('.comments > form');
var commentsElt = document.getElementsByClassName('comments-block');
var modalImageElt = document.querySelectorAll('.modal-image > img');
var bodyElt = document.getElementsByTagName('body');
var articleCaptionButtonElts = document.querySelectorAll('.article-caption button');
var buttonCrossElt = document.getElementsByClassName('button-cross');



function load() {
if(rowcustomElt.length === 2){
    if (window.innerWidth <= 1024 && window.innerWidth > 768) {
        for (i = 0; i < articleElt.length; i++) {
            articleElt[i].style.width = "48.5%";
            articleElt[i].style.position = "absolute";
            articleElt[i].style.height = "auto";

            if (i > 0) {
                var n;
                if (i === 1) {
                    articleElt[i].style.left = "50.5%";
                }

                if (i % 2 === 0) {
                    var top = articleElt[i - 2].offsetTop + parseFloat(getComputedStyle(articleElt[i - 2]).height);
                    top = top + 20;
                    articleElt[i].style.top = "" + top + "px";
                    articleElt[i].style.left = '1%';
                    n = i;
                }

                if (i === n + 1) {
                    var top = articleElt[i - 2].offsetTop + parseFloat(getComputedStyle(articleElt[i - 2]).height);
                    top = top + 20;
                    articleElt[i].style.top = "" + top + "px";
                    articleElt[i].style.left = "50.5%";
                }
            }
            else {
                articleElt[i].style.left = '1%';
            }
        }
        var lastRowChildren = [
            articleElt[articleElt.length - 2],
            articleElt[articleElt.length - 1]
        ];

        var arrayPositions = [];
        for (i = 0; i < lastRowChildren.length; i++) {
            arrayPositions.push(lastRowChildren[i].offsetTop + parseFloat(getComputedStyle(lastRowChildren[i]).height));
        }

        var max = Math.max.apply(null, arrayPositions);
        rowcustomElt[0].style.height = max + "px";
    }

    if (window.innerWidth > 1024) {
        for (i = 0; i < articleElt.length; i++) {

            articleElt[i].style.width = "33%";
            articleElt[i].style.position = "absolute";
            articleElt[i].style.height = "auto";

            if (i > 0) {
                var m;
                var o;
                if (i === 1) {
                    articleElt[i].style.top = "0";
                    articleElt[i].style.left = "33.5%";
                }
                if (i === 2) {
                    articleElt[i].style.top = "0";
                    articleElt[i].style.left = "66.75%";
                }

                if (i % 3 === 0) {
                    var topSup1024 = articleElt[i - 3].offsetTop + parseFloat(getComputedStyle(articleElt[i - 3]).height);
                    topSup1024 = topSup1024 + 20;
                    articleElt[i].style.top = "" + topSup1024 + "px";
                    articleElt[i].style.left = '.25%';
                    m = i;
                }

                if (i === m + 1) {
                    var topSup1024 = articleElt[i - 3].offsetTop + parseFloat(getComputedStyle(articleElt[i - 3]).height);
                    topSup1024 = topSup1024 + 20;
                    articleElt[i].style.top = "" + topSup1024 + "px";
                    articleElt[i].style.left = "33.5%";
                    o = i;
                }

                if (i === o + 1) {
                    var topSup1024 = articleElt[i - 3].offsetTop + parseFloat(getComputedStyle(articleElt[i - 3]).height);
                    topSup1024 = topSup1024 + 20;
                    articleElt[i].style.top = "" + topSup1024 + "px";
                    articleElt[i].style.left = "66.75%";
                }
            }
            else {
                articleElt[i].style.left = '.25%';
            }
        }

        if(articleElt.length >= 3){
            var lastRowChildrenSup1024 = [
                    articleElt[articleElt.length - 3],
                    articleElt[articleElt.length - 2],
                    articleElt[articleElt.length - 1]
                ];
        }

        if(articleElt.length >= 2){
            var lastRowChildrenSup1024 = [
                    articleElt[articleElt.length - 2],
                    articleElt[articleElt.length - 1]
                ];
        }

        if(articleElt.length >= 1){
            var lastRowChildrenSup1024 = [
                    articleElt[articleElt.length - 1]
                ];
        }

        var arrayPositionsSup1024 = [];
        for (i = 0; i < lastRowChildrenSup1024.length; i++) {
            arrayPositionsSup1024.push(lastRowChildrenSup1024[i].offsetTop + parseFloat(getComputedStyle(lastRowChildrenSup1024[i]).height));
        }

        var maxSup1024 = Math.max.apply(null, arrayPositionsSup1024);
        rowcustomElt[0].style.height = maxSup1024 + "px";
    }
}

else{
    if (window.innerWidth >= 768) {
        for (i = 0; i < imgElt.length; i++) {

            // On mesure hauteur et largueur
            var imgHeight = parseFloat(getComputedStyle(imgElt[i]).height);
            var imgWidth = parseFloat(getComputedStyle(imgElt[i]).width);

            // Si format portrait, ajout classe correspondante
            if (imgHeight > imgWidth) {
                imgElt[i].classList.add('vertical-img-resize');
            }
        }
    }
}

	if(window.innerWidth <=380){
		for(i=0; i<articleCaptionButtonElts.length; i++){
			articleCaptionButtonElts[i].className = "btn btn-sm btn-danger";
		}
	}
}

for(i=0; i<articleElt.length; i++){
    articleElt[i].addEventListener('mouseover', function(){
        this.lastElementChild.classList.add('article-custom-show');
    });
    articleElt[i].addEventListener('mouseleave', function(){
        this.lastElementChild.classList.remove('article-custom-show');
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

if(buttonPublishElt){
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
}

if(buttonPublishEsElt){
    buttonPublishEsElt.addEventListener('click', function(){
        if(formElt[0].classList.contains('show')){
            formElt[0].classList.remove('show');
            buttonPublishEsElt.textContent = "Publicar un comentario";
        }
        else{
            formElt[0].classList.add('show');
            buttonPublishEsElt.textContent = "Ocultar el formulario";
        }
    });
}
	
if(buttonSeeElt){
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

if(buttonSeeEsElt){
    buttonSeeEsElt.addEventListener('click', function(){
        if(commentsElt[0].classList.contains('show')){
            commentsElt[0].classList.remove('show');
            buttonSeeEsElt.textContent = "Ver los comentarios";
        }
        else{
            commentsElt[0].classList.add('show');
            buttonSeeEsElt.textContent = "Ocultar los comentarios";
        }
    });
}

// Affichage en plein écran de(s) photo(s) de l'article


if(modalImageElt){
    for(i=0; i<modalImageElt.length; i++){
        modalImageElt[i].addEventListener("click", function(e){
           this.parentNode.classList.add('modal-image-big');
           bodyElt[0].style.overflow = "hidden";
		   this.parentNode.lastElementChild.style.display = "block";
		   
		   this.parentNode.lastElementChild.addEventListener("click", function(){
			   this.parentNode.classList.remove('modal-image-big');
			   bodyElt[0].style.overflow = "visible";
			   this.style.display = "none";
		   });
        });
    }
}