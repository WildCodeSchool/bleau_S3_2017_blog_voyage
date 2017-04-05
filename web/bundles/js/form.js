// Sélection du formulaire
var formElt = document.querySelectorAll('.form > form');

// Création bouton ajout d'images
var buttonPictureElt = document.getElementsByClassName('picture');

// Création bouton ajout de catégories
var buttonCategoryElt = document.getElementsByClassName('category');

// Création bouton ajout de textes
var buttonTextElt = document.getElementsByClassName('text');

// Création du bouton de soumission du formulaire et ajout d'attributs
var submitElt = document.createElement('input');
submitElt.setAttribute('type', 'submit');
submitElt.setAttribute('value', 'Valider');

// Sélection de la dernière div
var buttonRangeElt = document.getElementById('buttonRange');

// Ecoute du clic sur le bouton d'ajout d'images
buttonPictureElt[0].addEventListener('click', function(e){
	// Evite la soumission automatique du formulaire.....
	e.preventDefault();
	
    // Création de la balise p qui contiendra l'input type file
    var pElt = document.createElement('p');
    pElt.style.background = '#f8f8f8';
    pElt.style.paddingTop = '5px';
    pElt.style.paddingBottom = '5px';

    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.margin = '5px 0 0 0';
    pElt2.style.textAlign = 'right';
    pElt.style.background = '#f8f8f8';
    var inputImgElt = document.createElement('input');
   
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.textContent = 'Supprimer';
    buttonRemoveElt.style.right = '0';

    /*********************************************************************************************/

    function setAttributes(tab){
        // On récupère les attributs tab[i] et leur valeur tab[i+1] avec une boucle et on les intègre à l'input
        for(i=0; i<tab.length; i+=2){
            inputImgElt.setAttribute(tab[i], tab[i+1]);
        }
    }
	
    // On appelle la fonction et on lui envoie un tableau en argument
    setAttributes(tab = ['type', 'file', 'name', 'src[]', 'class', 'inputFileNoUpload']);

    /*********************************************************************************************/

    pElt.appendChild(inputImgElt);
    pElt2.appendChild(buttonRemoveElt);
    pElt.appendChild(pElt2);
    formElt[0].insertBefore(pElt, buttonRangeElt);

    // Création image

    // On retire l'input si clic sur bouton supprimer correspondant
    buttonRemoveElt.addEventListener('click', function(e){
        formElt[0].removeChild(pElt);
    });

    // On écoute si fichier upload vide ou non et change la classe en vert ou rouge selon les cas
    inputImgElt.addEventListener('change', function(e){
        if(e.target.value !== ''){
            this.className = 'inputFileUpload';
        }
        else{
            this.className = 'inputFileNoUpload';
        }
    });
});

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
    setAttributes(tab = ['type' , 'text' , 'id' , 'category' , 'name' , 'category[]']);

    // Création du bouton
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.textContent = 'Supprimer';

    // Création du label, attribution d'une classe et ajout de contenu
    var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'keyWordLabel');
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
    spanElt.setAttribute('class', 'inputValidation');
	
	var divElt = document.createElement('div');
	
    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.width = '100%';
    pElt2.style.textAlign = 'right';

    // On ajoute le label, l'input text, le bouton de suppression du mot-clef, la phrase de conseil
    divElt.appendChild(labelElt);
    divElt.appendChild(inputElt);
    divElt.appendChild(spanElt);
    pElt2.appendChild(buttonRemoveElt);
    divElt.appendChild(pElt);
    divElt.appendChild(pElt2);
    formElt[0].insertBefore(divElt, buttonRangeElt);

    buttonRemoveElt.addEventListener('click', function(e){
		e.preventDefault();
        formElt[0].removeChild(divElt);
    });

    var validation;
    inputElt.addEventListener('input', function(e){
        this.parentNode.style.backgroundColor = '#77b5fe';
        labelElt.style.color = 'black';
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
            labelElt.style.color = 'rgb(104,104,104)';
        }
    });

    // On écoute la perte de focus
    inputElt.addEventListener('blur', function(e){
        if(this.parentNode.style.backgroundColor !== 'green'){
            this.parentNode.style.backgroundColor = '#f8f8f8';
            labelElt.style.color = 'rgb(104,104,104)';
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

buttonTextElt[0].addEventListener("click", function(e){
	e.preventDefault();
	var inputTextElt = document.createElement('textarea');
	var pElt = document.createElement('p');
	
	inputTextElt.setAttribute('name', 'content[]');
	pElt.appendChild(inputTextElt);
	formElt[0].insertBefore(pElt, buttonRangeElt);
});

