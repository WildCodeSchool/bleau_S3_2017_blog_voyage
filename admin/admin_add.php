<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Ajouter un article</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type='text/css' href="assets/css/admin_style.css">
</head>
<body>
	<div class="container container_admin_menu">

		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="index.php">Menu Admin</a></li>
						<li><a href="#portfolio">Menu Edit</a></li>
						<li><a href="contact.php">Newsletter</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<h2>Ajouter un article</h2>
		<form class="form-horizontal">
			<!--Ajout de margin sur form-->
			<div class="form-group">
				<label class="control-label col-xs-3" for="titre">Titre:</label>
				<div class="col-xs-9">
					<input type="titre" class="form-control" id="titre" name="titre" placeholder="Titre de l'article">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-3" for="auteur">Auteur:</label>
				<div class="col-xs-9">          
					<input type="auteur" class="form-control" id="auteur" name="auteur" placeholder="Nom de l'auteur">
				</div>
			</div>
			<div class="form-group">
				<textarea class="form-control" placeholder="Message"></textarea>
			</div>	
			<div class="form-group">
				<div class="col-xs-3">    
					<button type="button" class="btn glyphicon glyphicon-picture"> Ajout d'image</button>
				</div>
			</div>
			<div class="form-group">        
				<div class="col-xs-offset-0 col-xs-4">
					<div class="checkbox">
						<label><input type="checkbox">Newsletter</label>
					</div>
				</div>
				<div class="col-xs-4">
					<input type="reset" value="Effacer" id="reset" name="reset"/>
				</div>

				<div class="col-xs-4">					
					<button type="submit" class="btn btn-default">ajouter</button>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-offset-3 col-xs-6">

					<button type="button" class="btn btn-info btn-block">Déconnexion</button>
				</div>
			</div>

		</form>
	</div>
</body>
</html>
