// Identification du bloc d'alert si mots-clef en double
var divAlertElt = document.getElementById('alert-message');

if(divAlertElt){
	setTimeout(function(){
		opacity = 1;
		height = 200;
		x=0;
		var alertMessage = setInterval(function(){
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
	}, 4000);
}	