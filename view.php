<!doctype html>
	<html lang="fr">
	<head>
		<title>Labo 2 : La Médiathèque</title> <!--OBLIGATOIRE-->
		<meta charset="utf-8" /> <!-- L'alphabet utilisé sur notre site-->
		<script src="https://use.fontawesome.com/releases/v5.11.2/js/all.js"></script>
		<link rel="stylesheet" type="text/css" href="styles/normalize.css">
		<link rel="stylesheet" type="text/css" href="styles/styles.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap" rel="stylesheet">
	</head>
	<body>
		<header>
			<h1>La médiathèque</h1>
			<form method="POST" action="controler.php">
				<input type="text" name="recherche" id="RechercheUserId" placeholder="Entrez votre recherche"/>
				<button type="submit" name="action" value="rechercher" title="RechercheIcon"><img src="./images/search2.png" width="14px" alt="" /></button>
			</form>
		</header>
			<div class="navigation">
				<?php page_count_print($current_page, $max_page, $recherche); ?>
			</div>
			
				<?php film_entry_print($results); ?>
	</body>
	</html>