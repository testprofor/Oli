<?php 

function connection_serveur(){
	$dbh = new PDO(
        "mysql:dbname=mediatheque;host=localhost;port=3308",
        "root",
        "",
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
    return $dbh;
}

function film_entry_print($results){
	foreach ($results as $key => $value) {

        echo '<div class="div1" ><table><tr>';
        echo '<td rowspan="3"><img src="./images/affiches/'.$value["films_affiche"].'" width="200px"></td>';
        echo '<td class="titre">'.$value["films_titre"].'</td>';
        echo '<td class="date-real">'.$value["films_annee"].'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td colspan="2">'.$value["genres_nom"].'</br>Realisateur : '.$value["real_nom"].'</br>Acteurs : '.$value["acteurs_nom"].'</br>Durée : '.$value["films_duree"].' min'.'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td colspan="2">'.$value["films_resume"];
        echo '</td>';
        echo '</tr></table></div></br>';

    }
}

function recherche_dans_db($min, $max, $dbh, $recherche) {
   // if ($recherche!="") {
        $rech="%".$recherche."%";

        $sql="SELECT films_titre, films_resume, films_annee, films_affiche, films_duree, group_concat(distinct acteurs_nom) as acteurs_nom, group_concat(distinct genres_nom) as genres_nom, real_nom 
        FROM films
        LEFT JOIN realisateurs ON real_id=films_real_id 
        LEFT JOIN films_genres ON films_id=fg_films_id 
        LEFT JOIN genres ON genres_id=fg_genres_id 
        LEFT JOIN films_acteurs ON films_id=fa_films_id 
        LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id 
        WHERE real_nom LIKE :rechercher 
        || films_titre LIKE :rechercher 
        || films_resume LIKE :rechercher 
        || genres_nom LIKE :rechercher 
        || acteurs_nom LIKE :rechercher 
        group by films_id
        LIMIT :min_lim, :max_lim";
        $stmt = $dbh -> prepare($sql);
        $stmt -> bindParam('min_lim', $min, PDO::PARAM_INT);
        $stmt -> bindParam('max_lim', $max, PDO::PARAM_INT);
        $stmt -> bindParam('rechercher', $rech, PDO::PARAM_STR);
        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $tab=$stmt->fetchAll();
    //}
    /*else{

        $sql="SELECT films_titre, films_resume, films_annee, films_affiche, films_duree, group_concat(distinct acteurs_nom) as acteurs_nom, group_concat(distinct genres_nom) as genres_nom, real_nom 
        FROM films
        LEFT JOIN realisateurs ON real_id=films_real_id 
        LEFT JOIN films_genres ON films_id=fg_films_id 
        LEFT JOIN genres ON genres_id=fg_genres_id 
        LEFT JOIN films_acteurs ON films_id=fa_films_id 
        LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id 
        group by films_id
        LIMIT :min_lim, :max_lim";

        $stmt = $dbh -> prepare($sql);
        $stmt -> bindParam('min_lim', $min, PDO::PARAM_INT);
        $stmt -> bindParam('max_lim', $max, PDO::PARAM_INT);

        $stmt -> execute();
        $stmt -> setFetchMode(PDO::FETCH_ASSOC);
        $tab=$stmt->fetchAll();
        
    }*/
    return $tab;
}

function premiere_fiche_page(){
    if (isset($_GET['current_page'])){
        $min=$_GET['current_page']*10-10;
    }else{
        $min=0;
    }
    return $min;
}

function derniere_fiche_page(){
    return 10;
}


function input_util_recherche(){
    if (isset($_POST['action']) && ($_POST['action']=="rechercher")){
        $recherche=$_POST['recherche']; 
    }else if (isset($_GET['recherche'])){
        $recherche=$_GET['recherche'];
    }else{
    $recherche="";
}
return $recherche;
}

function page_count_print($current_page, $max_page, $recherche){
    if ($current_page>1){

        echo '<a class="left" href="controler.php?current_page='.(($current_page)-1).'&recherche='.$recherche.'">Page précédente</a>';
    }else{
        echo '<span class="left"></span>';
    }

    echo '  <p class="center" >Page '.$current_page.' sur '.$max_page.'</p>  ';

    if ($current_page<$max_page){
        echo '<a class="right" href="controler.php?current_page='.(($current_page)+1).'&recherche='.$recherche.'">Page suivante</a>';
    }else{
        echo '<span class="right"></span>';
    }  
}

function current_page(){
    if (isset($_GET['current_page'])) {
        $current_page=$_GET['current_page'];

    }else{
        $current_page=1;
    }
    return $current_page;
}

function max_page($recherche, $dbh){
    $rech="%".$recherche."%";
    $sql="SELECT count(distinct films_id) as nb
    FROM films
    LEFT JOIN realisateurs ON real_id=films_real_id 
    LEFT JOIN films_genres ON films_id=fg_films_id 
    LEFT JOIN genres ON genres_id=fg_genres_id 
    LEFT JOIN films_acteurs ON films_id=fa_films_id 
    LEFT JOIN acteurs ON fa_acteurs_id=acteurs_id 
    WHERE real_nom LIKE :rech 
    || films_titre LIKE :rech  
    || films_resume LIKE :rech  
    || genres_nom LIKE :rech  
    || acteurs_nom LIKE :rech ";
    $stmt = $dbh -> prepare($sql);
    $stmt -> bindParam('rech', $rech, PDO::PARAM_STR);
    $stmt -> execute();
    $return=$stmt->fetchAll();
    foreach ($return as $key => $value) {
        $max=$value["nb"];
    }
    if ($max%10 != 0){
        $max_page=(($max-($max%10))/10)+1;
    }else{
        $max_page=$max;
    }
    return $max_page;
}



