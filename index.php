<?php 

    define('ROOT', __DIR__);
    define('DS', DIRECTORY_SEPARATOR);

    //creation des varibales filtrées
    $id = filter_input(INPUT_POST,'id');
    $pays = filter_input(INPUT_POST,'pays');
    $nom = filter_input(INPUT_POST,'nom');
    $prenom = filter_input(INPUT_POST,'prenom');
    $date = filter_input(INPUT_POST,'date');

    //varible pour la recherche 
    $sid = filter_input(INPUT_POST,'sid');
    $snom = filter_input(INPUT_POST,'snom');
    $sprenom = filter_input(INPUT_POST,'sprenom');
    $spays = filter_input(INPUT_POST,'spays');

    
    //appel de notre fichier class
    require (ROOT . DS . "class.php");
    var_dump(ROOT . DS ."class.php");
    //etablissement d'une nouvelle connexion
    $dbh = new Connect;

    //si la page compte au moin un post la condition s'execute
    if(count($_POST) > 0){
        if($_POST['sub'] == 1) {
            //ajout des paramètres dans le constructeur
            $bdd = new Player($id,$pays,$nom,$prenom,$date);
            //appel de la méthode
            $bdd->write(); 
        }

        if($_POST['sub'] == 2){
            $poster = new Player(); 
        }

        if($_POST["suppr"] == 1){
        $delete = new Player(); 
        }

        
    }
    //si $dbh est un objet on echo reussite
    if($dbh){
        echo 'connexion reussite';
    } else
        echo "connexion échouée";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>inscrivez un joueur dans la bdd</h1>
    <form  method='post'>
        <label for="id">id</label>
        <input type="text" name="id"> 
        <label for="pays">pays</label>
        <input type="text" name="pays" >
        <label for="nom">nom</label>
        <input type="text" name="nom" >
        <label for="prenom">prenom</label>
        <input type="text" name="prenom"> 
        <label for="date">date de naissance</label>
        <input type="date" name="date"> 
        <button type="submit" name="sub" value="1">envoyer</button>
    </form>

    <h2>recherchez un joueur</h2>
    <form  method="post">
        <label for="sid">id</label>
        <input type="text" name="sid">
        <label for="nom">nom</label>
        <input type="text" name="snom">
        <label for="prenom">prenom</label>
        <input type="text" name="sprenom">
        <label for="pays">pays</label>
        <input type="text" name="spays">  
        <button type="submit" name="sub" value="2">envoyer</button>
        <button type="submit"  name="suppr" value="1">supprimer</button>
        <?php 
            if(count($_POST) > 0){
                $poster->varDump($poster->poster($_POST["sid"], $_POST["snom"],$_POST["sprenom"],$_POST["spays"]));
            }
        ?>
    </form>
    <a href="contrat.php">créer un contrat</a>
</body>
</html>