// Identification du formulaire
var formElt = document.querySelectorAll('.form > form');

// Identification du bouton de suppression de texte du premier élément "textarea"
// de la partie Add et de tous ceux de la partie Edit (lorsqu'on arrive sur la page"), si plusieurs textarea
var resetElt = document.getElementsByClassName("text-trash");

// Identification du bouton de suppression de "Image + texte"
var buttonRemoveContentElt = document.getElementsByClassName('remove-content');

// Identification du bouton de suppression des blocs de mots clefs déjà présents dans edit
var buttonDeleteKeyWordElt = document.getElementsByClassName('delete-keyword');

// Identification du premier textarea
var firstTextElt = document.getElementsByClassName("first-text");

// Identification du bloc d'alert si mots-clef en double
var divAlertElt = document.getElementById('alert-message');

// Identification du bouton d'édition
var buttonEditElt = document.getElementById('submit');

// Création bouton ajout de contenu (image + texte)
var buttonContentElt = document.getElementsByClassName('content');

// Création bouton ajout de catégories
var buttonCategoryElt = document.getElementsByClassName('category');

// Identification de la balise qui contiendra les éléments images et textes
var contentElt = document.getElementsByClassName('content-block');

// Création de la div pour les mots-clefs/catégories
var keywordRangeElt = document.createElement('div');
keywordRangeElt.setAttribute('id', 'keywordRange');

// Sélection de la div comprenant les boutons d'ajout/soumission
var buttonRangeElt = document.getElementById('buttonRange');

// On insére la div contenant les mots-clefs avant la div contenant les boutons d'ajout/soumission
buttonRangeElt.parentNode.insertBefore(keywordRangeElt, buttonRangeElt);

console.log(firstTextElt.length);
console.log(buttonRemoveContentElt.length);

// Si l'utilisateur clique sur le bouton "vider le texte" du premier élément contenu
for(i=0; i<resetElt.length; i++){
	resetElt[i].addEventListener("click", function(e){
		e.preventDefault();
		this.parentNode.parentNode.childNodes[3].childNodes[1].value="";
	});
}

// Ecoute du clic sur le bouton d'ajout de contenu
buttonContentElt[0].addEventListener('click', function(e){
	// Evite la soumission automatique du formulaire.....
	e.preventDefault();

    // Création du bouton de suppression de la div de contenu
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.setAttribute('class', 'btn btn-large btn-danger')
    buttonRemoveElt.textContent = 'Supprimer';

///////////////////////////////////////////PARTIE TEXTE////////////////////////
    var inputTextElt = document.createElement('textarea');
    inputTextElt.setAttribute('name', 'content[]');
    inputTextElt.setAttribute('class', 'first-text');

    var pTextElt = document.createElement('p');
    pTextElt.style.margin = "25px 0 25px 0";

    var pTextElt2 = document.createElement('p');
    pTextElt2.style.display = 'flex';
    pTextElt2.style.justifyContent = 'space-around';
    pTextElt2.style.margin = '0 auto';
    pTextElt2.style.paddingBottom = '15px';
    pTextElt2.style.paddingTop = '15px';

    // Création du label pour l'input texte
    var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'label-text');
    labelElt.textContent = 'Texte ';

    // Création du bouton de suppression
    var buttonResetElt = document.createElement('button');
    buttonResetElt.setAttribute('class', 'btn btn-large btn-danger');
    buttonResetElt.textContent = 'Vider le texte';

	// On imbrique les éléments pour le texte
    pTextElt.appendChild(labelElt);
    pTextElt2.appendChild(buttonRemoveElt);
    pTextElt2.appendChild(buttonResetElt);
    pTextElt.appendChild(inputTextElt);
    pTextElt.appendChild(pTextElt2);

 //////////////////////////PARTIE IMAGE///////////////////////////////////////////////////////////

    // Création de la balise p qui contiendra l'input type file
    var pImgElt = document.createElement('p');

    pImgElt.style.paddingTop = '5px';
    pImgElt.style.paddingBottom = '5px';

    // Création du label pour l'input image
	var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'label-image');
    labelElt.textContent = 'Image ';

    /***************************************************************************/
    // Création de l'input
    var inputImgElt = document.createElement('input');

    function setAttributes(tab){
        // On récupère les attributs tab[i] et leur valeur tab[i+1] avec une boucle et on les intègre à l'input
        for(i=0; i<tab.length; i+=2){
            inputImgElt.setAttribute(tab[i], tab[i+1]);
        }
    }
	
    // On appelle la fonction et on lui envoie un tableau en argument
    setAttributes(tab = ['type', 'file', 'name', 'src[]', 'class', 'inputFileNoUpload', 'required']);

    /*********************************************************************************************/

    pImgElt.appendChild(labelElt);
    pImgElt.appendChild(inputImgElt);

    // On écoute si fichier upload vide ou non et change la classe en vert ou rouge selon les cas
    inputImgElt.addEventListener('change', function(e){
        if(e.target.value !== ''){
            this.className = 'inputFileUpload';
        }
        else{
            this.className = 'inputFileNoUpload';
        }
    });

    var divElt = document.createElement('div');
    divElt.style.backgroundColor = "white";
    divElt.style.margin = "25px 0 50px 0";
    var pRemoveElt = document.createElement('p');


    divElt.appendChild(pImgElt);
    divElt.appendChild(pTextElt);
    contentElt[0].appendChild(divElt);

    buttonResetElt.addEventListener("click", function(e){
        e.preventDefault();
        inputTextElt.value = "";
    });

    // On retire le bloc de contenu si clic sur bouton supprimer correspondant
    buttonRemoveElt.addEventListener('click', function(e){
        e.preventDefault();
		/*
        if((labelElt.textContent).includes(''+k-1+'')){
            k--;
        }
		*/
        contentElt[0].removeChild(divElt);
    });
	
	// k++;
});

///////////////////////////////PARTIE CATEGORIES///////////////////////////////////

var j=1;
buttonCategoryElt[0].addEventListener('click', function(e){
	e.preventDefault(); // Ajouté cela empêche l'envoi automatique du formulaire sans appuyer pourtant sur le bouton submit

    // Création de l'input et définition des attributs
    var inputElt = document.createElement('input');

    function setAttributes(tab){
        // On récupère les attributs tab[i] et leur valeur tab[i+1] avec une boucle et on les intègre à l'input
        for(i=0; i<tab.length; i+=2){
            inputElt.setAttribute(tab[i], tab[i+1]);
        }
    }
    // On appelle la fonction et on lui envoie un tableau en argument
    setAttributes(tab = ['type' , 'text' , 'id' , 'category' , 'name' , 'category[]', 'required']);

    // Création du bouton de suppression
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.setAttribute('class', 'btn btn-large btn-danger');
    buttonRemoveElt.textContent = 'Supprimer';

    // Création du label, attribution d'une classe et ajout de contenu
    var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'label-category');
    labelElt.textContent = 'Nouveau mot-clef ' +j;

    // Création de la phrase de conseil
    var pElt = document.createElement('p');
    pElt.textContent = 'Merci de définir une catégorie plus "précise" pour vos visiteurs';
    pElt.style.display = 'none';
    pElt.setAttribute('class', 'keyWordHelp');

    // Création phrase de validation
    var spanElt = document.createElement('span');
    spanElt.style.display = 'none';
    spanElt.style.color = 'black';
    spanElt.style.fontWeight = 'bold';
    spanElt.style.width = '95%';
    spanElt.style.margin= '0 auto';
    spanElt.setAttribute('class', 'inputValidation');
	
	var divElt = document.createElement('div');
	divElt.setAttribute('class', 'keywordDiv');
	
    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.margin = '0 auto';
    pElt2.style.paddingTop = '5px';
    pElt2.style.textAlign = 'right';

    // On ajoute le label, l'input text, le bouton de suppression du mot-clef, la phrase de conseil
    divElt.appendChild(labelElt);
    divElt.appendChild(inputElt);
    divElt.appendChild(spanElt);
    pElt2.appendChild(buttonRemoveElt);
    divElt.appendChild(pElt);
    divElt.appendChild(pElt2);
	keywordRangeElt.appendChild(divElt);
   
    buttonRemoveElt.addEventListener('click', function(e){
		e.preventDefault();
        keywordRangeElt.removeChild(divElt);
		j--;
    });

    var validation;
    inputElt.addEventListener('input', function(e){
        this.parentNode.style.backgroundColor = '#77b5fe';
        pElt.style.color = 'black';

        var regex = new RegExp('autres*|Autres*');
        var keyword = e.target.value;

        if(keyword.length !== 0){
            if(regex.test(e.target.value)){
                this.parentNode.style.backgroundColor = '#b22222';
                pElt.style.display = 'block';
                pElt.style.color = 'white';
                spanElt.textContent = 'Mot clef invalide';
                spanElt.style.display = 'block';
                spanElt.style.color = 'white';
                labelElt.style.color = 'white';
                validation = false;
            }
            else{
                pElt.style.display = 'none';
                spanElt.textContent = 'Mot-clef valide';
                spanElt.style.display = 'block';
                spanElt.style.color = 'black';
                validation = true;
            }
        }
        if(keyword.length == 0){
            pElt.style.display = 'none';
            spanElt.style.display = 'none';
            this.parentNode.style.backgroundColor = '#f8f8f8';
        }
    });

    // On écoute la perte de focus
    inputElt.addEventListener('blur', function(e){
        if(this.parentNode.style.backgroundColor !== 'green'){
            this.parentNode.style.backgroundColor = '#f8f8f8';

        }
        if(validation == true){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = 'green';
                labelElt.style.color = 'white';
                spanElt.style.color = 'white';
            }
        }
        if(validation == false){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = '#b22222';
                labelElt.style.color = 'white';
                spanElt.style.color = 'white';
                pElt.style.color = 'white';
            }
        }

    });

    j++;
});

/// fichier d'édition  
for(i=0; i<buttonDeleteKeyWordElt.length; i++){
	buttonDeleteKeyWordElt[i].addEventListener("click", function(e){
		e.preventDefault();
		this.parentNode.remove();
	});
}

for(i=0; i<buttonRemoveContentElt.length; i++) {
    buttonRemoveContentElt[i].addEventListener('click', function (e) {
        e.preventDefault();
        this.parentNode.remove();
    });
}

// On vérifie s'il y a bien au moins un bloc "texte + image" envoyé dans le formulaire d'édition.

var check = false;
window.addEventListener("scroll", function(){
	if(firstTextElt.length > 0){
		check = true;
		firstTextElt[0].required = true;
	}
	else{
		check = false;
	}
});

buttonEditElt.addEventListener("click", function(e){
	if(check == false){
		e.preventDefault();
		alert("Merci d'ajouter au moins une image et un texte");
	}	
});

if(divAlertElt){
	setTimeout(function(){
		opacity = 1;
		height = 200;
		x=0;
		var alertMessage = setInterval(function(){
			console.log(x);
			x += 1;
			divAlertElt.style.opacity = "" + opacity + "";
			opacity -= 0.025;
			if(opacity <= 0.1){
				divAlertElt.style.height = "" + height + "px";
				height -= 50;
			}
			if(x == 60)
			{
				clearInterval(alertMessage);
			}
		}, 100);
			
	}, 1000);
}	


