// Sélection du formulaire
var formElt = document.querySelectorAll('.form > form');

// Création bouton ajout d'images
var buttonPictureElt = document.getElementsByClassName('picture');

// Création bouton ajout de catégories
var buttonCategoryElt = document.getElementsByClassName('category');

// Création bouton ajout de textes
var buttonTextElt = document.getElementsByClassName('text');

// Création de la div pour les mots-clefs/catégories
var keywordRangeElt = document.createElement('div');
keywordRangeElt.setAttribute('id', 'keywordRange');

// Sélection de la div comprenant les boutons d'ajout/soumission
var buttonRangeElt = document.getElementById('buttonRange');

// On insére la div contenant les mots-clefs avant la div contenant les boutons d'ajout/soumission
buttonRangeElt.parentNode.insertBefore(keywordRangeElt, buttonRangeElt);

// Ecoute du clic sur le bouton d'ajout d'images
var k=1;
buttonPictureElt[0].addEventListener('click', function(e){
	// Evite la soumission automatique du formulaire.....
	e.preventDefault();
	
    // Création de la balise p qui contiendra l'input type file
    var pElt = document.createElement('p');
    pElt.style.background = '#f8f8f8';
    pElt.style.paddingTop = '5px';
    pElt.style.paddingBottom = '5px';
	
	var labelElt = document.createElement('label');
    labelElt.setAttribute('class', 'keyWordLabel');
    labelElt.textContent = 'Image ' +k;

    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.margin = '5px 0 0 0';
    pElt2.style.textAlign = 'right';
    pElt.style.background = 'white';
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

    pElt.appendChild(labelElt);
	pElt.appendChild(inputImgElt);
    pElt2.appendChild(buttonRemoveElt);
    pElt.appendChild(pElt2);
    buttonRangeElt.parentNode.insertBefore(pElt, buttonRangeElt);

    // On retire l'input si clic sur bouton supprimer correspondant
    buttonRemoveElt.addEventListener('click', function(e){
		e.preventDefault();
		if((labelElt.textContent).includes(''+k-1+'')){
			k--;
		}
        buttonRangeElt.parentNode.removeChild(pElt);
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
	
	k++;
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

    // Création du bouton de suppression
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
    spanElt.style.width = '95%';
    spanElt.style.margin= '0 auto';
    spanElt.setAttribute('class', 'inputValidation');
	
	var divElt = document.createElement('div');
	divElt.setAttribute('class', 'keywordDiv');
	
    // Création de la balise p qui contiendra le bouton de suppression
    var pElt2 = document.createElement('p');
    pElt2.style.width = '95%';
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
            labelElt.style.color = 'black';
        }
    });

    // On écoute la perte de focus
    inputElt.addEventListener('blur', function(e){
        if(this.parentNode.style.backgroundColor !== 'green'){
            this.parentNode.style.backgroundColor = '#f8f8f8';
            labelElt.style.color = 'black';
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

var l=1;
buttonTextElt[0].addEventListener("click", function(e){
	e.preventDefault();
	var inputTextElt = document.createElement('textarea');
	var pElt = document.createElement('p');
	var pElt2 = document.createElement('p');
	pElt2.style.textAlign = 'right';
	pElt2.style.margin = '0 auto';
	pElt2.style.width = '95%';
	
	var labelElt = document.createElement('label');
	labelElt.setAttribute('class', 'keyWordLabel');
	labelElt.textContent = 'Texte ' + l;
	
	 // Création du bouton de suppression
    var buttonRemoveElt = document.createElement('button');
    buttonRemoveElt.textContent = 'Supprimer';
	
	inputTextElt.setAttribute('name', 'content[]');
	pElt.appendChild(labelElt);
	pElt2.appendChild(buttonRemoveElt);
	pElt.appendChild(inputTextElt);
	pElt.appendChild(pElt2);
	buttonRangeElt.parentNode.insertBefore(pElt, buttonRangeElt);
	
	buttonRemoveElt.addEventListener('click', function(e){
		e.preventDefault();
		if((labelElt.textContent).includes(''+l-1+'')){
			l--;
		}
		buttonRangeElt.parentNode.removeChild(pElt);
	});
	
	l++;
});

