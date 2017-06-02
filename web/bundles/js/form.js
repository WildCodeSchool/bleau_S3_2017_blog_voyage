// Identification du formulaire
var formElt = document.querySelectorAll('.form > form');

// Identification du bouton de suppression de texte du premier élément "textarea"
// de la partie Add et de tous ceux de la partie Edit (lorsqu'on arrive sur la page"), si plusieurs textarea
var resetElt = document.getElementsByClassName("text-trash");

var resetFirstAddElt = document.getElementsByClassName("text-bin");

// Identification du bouton de suppression de "Image + texte"
var buttonRemoveContentElt = document.getElementsByClassName('remove-content');

// Identification du bouton de suppression des blocs de mots clefs déjà présents dans edit
var buttonDeleteKeyWordElt = document.getElementsByClassName('delete-keyword');

// Identification du premier textarea
var firstTextElt = document.getElementsByClassName("first-text");

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

// Sélection de l'input comprenant la longitude
var inputLongElt = document.getElementById('long');

// Sélection de l'input comprenant la latitude
var inputLatElt = document.getElementById('lat');

// On insére la div contenant les mots-clefs avant la div contenant les boutons d'ajout/soumission
if(buttonRangeElt){
	buttonRangeElt.parentNode.insertBefore(keywordRangeElt, buttonRangeElt);
}	

// Si l'utilisateur clique sur le bouton "vider le texte" du premier élément contenu
for(i=0; i<resetElt.length; i++){
	resetElt[i].addEventListener("click", function(e){
		e.preventDefault();
		this.parentNode.childNodes[3].value="";
	});
}

if(resetFirstAddElt){
	for(i=0; i<resetFirstAddElt.length; i++){
		resetFirstAddElt[i].addEventListener("click", function(e){
			e.preventDefault();
			this.parentNode.previousSibling.childNodes[3].value="";
		});
	}
}

// Ecoute du clic sur le bouton d'ajout de contenu
buttonContentElt[0].addEventListener('click', function(e){
	e.preventDefault();

    // Création du bouton de suppression de la div de contenu
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.setAttribute('class', 'btn btn-lg btn-danger')
    buttonRemoveElt.textContent = 'Supprimer Image + Texte';
	if(window.innerWidth<480){
		buttonRemoveElt.style.marginBottom = '10px';
		buttonRemoveElt.style.width = '100%';
	}

///////////////////////////////////////////PARTIE TEXTE////////////////////////
    var inputTextElt = document.createElement('textarea');
    inputTextElt.setAttribute('name', 'contentFr[]');
    inputTextElt.setAttribute('class', 'first-text');

    var inputTextEsElt = document.createElement('textarea');
    inputTextEsElt.setAttribute('name', 'contentEs[]');
    inputTextEsElt.setAttribute('class', 'first-text');
	
	// Création du bouton de suppression
    var buttonResetElt = document.createElement('button');
    buttonResetElt.setAttribute('class', 'btn btn-lg btn-danger');
    buttonResetElt.textContent = 'Vider le texte';

	var buttonResetEsElt = document.createElement('button');
    buttonResetEsElt.setAttribute('class', 'btn btn-lg btn-danger');
    buttonResetEsElt.textContent = 'Vider le texte';

    var pTextElt = document.createElement('p');
    pTextElt.style.margin = "25px 0 25px 0";

    var pTextElt2 = document.createElement('p');
    pTextElt2.style.margin = '0 auto';
    pTextElt2.style.textAlign = 'center';
    pTextElt2.style.paddingBottom = '15px';
    pTextElt2.style.paddingTop = '15px';

    // TODO: Remettre le bouton vider text en fr
	// pTextElt2.appendChild(buttonResetElt);
	
	var pTextElt3 = document.createElement('p');
    pTextElt3.style.margin = '0 auto';
    pTextElt3.style.textAlign = 'center';
    pTextElt3.style.paddingBottom = '15px';
    pTextElt3.style.paddingTop = '15px';

    // TODO: Remettre le bouton vider text en es
    // pTextElt3.appendChild(buttonResetEsElt);

    // Création du label pour l'input texte
    var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'label-text');
    labelElt.textContent = 'Texte en français';

    var labelEsElt = document.createElement('label');
    labelEsElt.setAttribute('class', 'label-text');
    labelEsElt.textContent = 'Texto en español';

	
	if(window.innerWidth<480){
		buttonResetElt.style.width = '100%';
	}

	// On imbrique les éléments pour le texte
	// FR
    pTextElt.appendChild(labelElt);
    pTextElt.appendChild(inputTextElt);
    pTextElt.appendChild(pTextElt2);
	
	// ES
	pTextElt.appendChild(labelEsElt);
	pTextElt.appendChild(inputTextEsElt);
    pTextElt.appendChild(pTextElt3);
	
	// Bouton de suppression du block
    pTextElt.appendChild(buttonRemoveElt);
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
	
	buttonResetEsElt.addEventListener("click", function(e){
        e.preventDefault();
        inputTextEsElt.value = "";
    });

    // On retire le bloc de contenu si clic sur bouton supprimer correspondant
    buttonRemoveElt.addEventListener('click', function(e){
        e.preventDefault();
        contentElt[0].removeChild(divElt);
    });
});

///////////////////////////////PARTIE CATEGORIES///////////////////////////////////

var j=1;
buttonCategoryElt[0].addEventListener('click', function(e){
	e.preventDefault(); 

    // Création de l'input et définition des attributs
    var inputCatFrElt = document.createElement('input');
    var inputCatEsElt = document.createElement('input');
	
	var divFrElt = document.createElement('div');
	var divEsElt = document.createElement('div');

    function setAttributes(tab, tab2){
        // On récupère les attributs tab[i] et leur valeur tab[i+1] avec une boucle et on les intègre à l'input
        for(i=0; i<tab.length; i+=2){
            inputCatFrElt.setAttribute(tab[i], tab[i+1]);
        }
		
		for(i=0; i<tab2.length; i+=2){
            inputCatEsElt.setAttribute(tab2[i], tab2[i+1]);
        }
    }
	
	tab = ['type' , 'text' , 'id' , 'category' , 'name' , 'categoryFr[]', 'required'];
	tab2 = ['type' , 'text' , 'id' , 'category' , 'name' , 'categoryEs[]', 'required'];
    
	// On appelle la fonction et on lui envoie les deux tableaux en argument
    setAttributes(tab, tab2);

    // Création du bouton de suppression
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.setAttribute('class', 'btn btn-large btn-danger');
    buttonRemoveElt.textContent = 'Supprimer';

    // Création du label, attribution d'une classe et ajout de contenu
    var labelFrElt = document.createElement('label');
    labelFrElt.setAttribute('class', 'label-category');
    labelFrElt.textContent = 'Nouveau mot-clef ' +j;
	
	// Création du label, attribution d'une classe et ajout de contenu
    var labelEsElt = document.createElement('label');
    labelEsElt.setAttribute('class', 'label-category');
    labelEsElt.textContent = 'Nueva palabra clave ' +j;

    // Création de la phrase de conseil français
    var pFrElt = document.createElement('p');
    pFrElt.textContent = 'Merci de définir une catégorie plus "précise" pour vos visiteurs';
    pFrElt.style.display = 'none';
    pFrElt.setAttribute('class', 'keyWordHelp');
	
	// Création de la phrase de conseil espagnol
    var pEsElt = document.createElement('p');
    pEsElt.textContent = 'Merci de définir une catégorie plus "précise" pour vos visiteurs';
    pEsElt.style.display = 'none';
    pEsElt.setAttribute('class', 'keyWordHelp');

    // Création phrase de validation
    var spanFrElt = document.createElement('span');
    spanFrElt.style.display = 'none';
    spanFrElt.style.color = 'black';
    spanFrElt.style.fontWeight = 'bold';
    spanFrElt.style.width = '95%';
    spanFrElt.style.margin= '0 auto';
    spanFrElt.setAttribute('class', 'inputValidation');
	
	    // Création phrase de validation
    var spanEsElt = document.createElement('span');
    spanEsElt.style.display = 'none';
    spanEsElt.style.color = 'black';
    spanEsElt.style.fontWeight = 'bold';
    spanEsElt.style.width = '95%';
    spanEsElt.style.margin= '0 auto';
    spanEsElt.setAttribute('class', 'inputValidation');
	
	var divElt = document.createElement('div');
	divElt.setAttribute('class', 'keywordDiv');
	
    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.margin = '0 auto';
    pElt2.style.paddingTop = '5px';
    pElt2.style.textAlign = 'right';
	pElt2.appendChild(buttonRemoveElt);

    // On ajoute le label, l'input text, le bouton de suppression du mot-clef, la phrase de conseil
    divFrElt.appendChild(labelFrElt);
    divFrElt.appendChild(inputCatFrElt);
    divFrElt.appendChild(spanFrElt);
    divFrElt.appendChild(pFrElt);
	
	divEsElt.appendChild(labelEsElt);
    divEsElt.appendChild(inputCatEsElt);
    divEsElt.appendChild(spanEsElt);
    divEsElt.appendChild(pEsElt);
	
	divElt.appendChild(divFrElt);
	divElt.appendChild(divEsElt);
	divElt.appendChild(buttonRemoveElt);
	keywordRangeElt.appendChild(divElt);
   
    buttonRemoveElt.addEventListener('click', function(e){
		e.preventDefault();
        keywordRangeElt.removeChild(divElt);
		j--;
    });

    var validationFr; 
	var validationEs;
	inputCatFrElt.addEventListener('input', function(e){
		this.parentNode.style.backgroundColor = '#77b5fe';
		pFrElt.style.color = 'black';

		var regexFr = new RegExp('autres*|Autres*');
		var keywordFr = e.target.value;

		if(keywordFr.length !== 0){
			if(regexFr.test(e.target.value)){
				this.parentNode.style.backgroundColor = '#b22222';
				pFrElt.style.display = 'block';
				pFrElt.style.color = 'white';
				spanFrElt.textContent = 'Mot clef invalide';
				spanFrElt.style.display = 'block';
				spanFrElt.style.color = 'white';
				labelFrElt.style.color = 'white';
				validationFr = false;
			}
			else{
				pFrElt.style.display = 'none';
				spanFrElt.textContent = 'Mot-clef valide';
				spanFrElt.style.display = 'block';
				spanFrElt.style.color = 'black';
				validationFr = true;
			}
		}
		if(keywordFr.length == 0){
			pFrElt.style.display = 'none';
			spanFrElt.style.display = 'none';
			this.parentNode.style.backgroundColor = '#f8f8f8';
		}
	});
	
    // On écoute la perte de focus
    inputCatFrElt.addEventListener('blur', function(e){
        if(this.parentNode.style.backgroundColor !== 'green'){
            this.parentNode.style.backgroundColor = '#f8f8f8';

        }
        if(validationFr == true){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = 'green';
                labelFrElt.style.color = 'white';
                spanFrElt.style.color = 'white';
            }
        }
        if(validationFr == false){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = '#b22222';
                labelFrElt.style.color = 'white';
                spanFrElt.style.color = 'white';
                pFrElt.style.color = 'white';
            }
        }
    });
		
	inputCatEsElt.addEventListener('input', function(e){
		this.parentNode.style.backgroundColor = '#77b5fe';
		pEsElt.style.color = 'black';

		var regexEs = new RegExp('otros*|Otros');
		var keywordEs = e.target.value;

		if(keywordEs.length !== 0){
			if(regexEs.test(e.target.value)){
				this.parentNode.style.backgroundColor = '#b22222';
				pEsElt.style.display = 'block';
				pEsElt.style.color = 'white';
				spanEsElt.textContent = 'Mot clef invalide';
				spanEsElt.style.display = 'block';
				spanEsElt.style.color = 'white';
				labelEsElt.style.color = 'white';
				validationEs = false;
			}
			else{
				pEsElt.style.display = 'none';
				spanEsElt.textContent = 'Mot-clef valide';
				spanEsElt.style.display = 'block';
				spanEsElt.style.color = 'black';
				validationEs = true;
			}
		}
		if(keywordEs.length == 0){
			pEsElt.style.display = 'none';
			spanEsElt.style.display = 'none';
			this.parentNode.style.backgroundColor = '#f8f8f8';
		}
	});
	
    // On écoute la perte de focus
    inputCatEsElt.addEventListener('blur', function(e){
        if(this.parentNode.style.backgroundColor !== 'green'){
            this.parentNode.style.backgroundColor = '#f8f8f8';

        }
        if(validationEs == true){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = 'green';
                labelEsElt.style.color = 'white';
                spanEsElt.style.color = 'white';
            }
        }
        if(validationEs == false){
            if(e.target.value.length > 0){
                this.parentNode.style.backgroundColor = '#b22222';
                labelEsElt.style.color = 'white';
                spanEsElt.style.color = 'white';
                pEsElt.style.color = 'white';
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

if(buttonEditElt){
	buttonEditElt.addEventListener("click", function(e){
		if(check == false){
			e.preventDefault();
			alert("Merci d'ajouter au moins une image et un texte");
		}	
	});
}

// Fonctions googleMaps

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        navigator.geolocation.watchPosition(function(position) {
                alert("i'm tracking you!");
            },
            function (error) {
                if (error.code == error.PERMISSION_DENIED)
                    alert("you denied me :-(");
            });
    } else {
        alert("Veuillez activer la géolocalisation");
    }
}

function showPosition(position) {
    inputLatElt.value =  position.coords.latitude; 
    inputLongElt.value =  position.coords.longitude;
}


