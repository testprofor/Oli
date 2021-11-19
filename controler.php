<?php 
require_once 'model.php';
try {
	$dbh=connection_serveur();
	$recherche=input_util_recherche();
	$max_page=max_page($recherche, $dbh);
	$current_page=current_page();
	$min=premiere_fiche_page();
	$max=derniere_fiche_page();
	$results=recherche_dans_db($min, $max, $dbh, $recherche);
} catch (Exception $ex) {
	die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour" onclick="history.go(-1)"></form>');
}
require_once 'view.php';
?>
