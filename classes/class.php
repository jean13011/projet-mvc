<?php

/**
 *  creation d'une classe qui instance ma base de donnée
 * @return connexion a la bdd
 */
class Connect
{
    //paramètres pour le constructor
    protected $bdd = '';
    protected $connec_host = '';
    protected $connec_dbname = '';
    protected $connec_pseudo = '';
    protected $connec_mdp = '';
    
    //assignation des paramètres du constructor avec un try catch
    public function __construct($connec_host = 'localhost:3306', $connec_dbname = 'foot2', $connec_pseudo = 'root', $connec_mdp = ''){
        try {
            $this->bdd = new PDO('mysql:host='.$connec_host.';dbname='.$connec_dbname, $connec_pseudo, $connec_mdp);
            $this->bdd->exec("SET CHARACTER SET utf8");
            $this->bdd->exec("SET NAMES utf8");
        }
        catch(PDOException $e) {
            die('<h3>Erreur !</h3>');
        }
    }

    /**
     * fonction d'accroche de connexion pour les autres class
     * @return connexion bdd
     */
    public function connexion(){
        return $this->bdd;
    }
}

/**
 * nouvelle class player qui est la fille de la classe mère "Connect"
 */
class Player extends Connect
{
    private string $id = '';
    private $country = '';
    private $name= '';
    private $surname = '';
    private $birth = '';

    /**
     * creation d'un constructor pour mon nouveau joueur 
     * @param string
     */
    public function __construct( $i='', string $c='', string $n='', string $s='', string $b=''){
        $this->id=$i;
        $this->country=$c;
        $this->name=$n;
        $this->surname=$s;
        $this->birth=$b;
        parent::__construct();
    }

    /**
     * sort tout les matricule 
     * @param string
     */
    public function checkInBdd(string $post){
        $result = $this->bdd->prepare(
            "SELECT ID_JOUEUR FROM `joueur` "
        );
        $result->execute();
        $req = $result->fetchAll;
        return $req;
    }

    /**
     * insere les données du formulaire dans la bdd
     * @return bool
     */
    private function insert(){   
        $result = $this->bdd->prepare(
            "INSERT INTO `joueur` (ID_JOUEUR, ID_PAYS, NOM_JOUEUR, PRENOM_JOUEUR, DATE_NAISSANCE_JOUEUR) 
            VALUES (:id, :country, :name, :surname, :birth) "
        );
        $result->bindParam(':id', $this->id);
        $result->bindParam(':country', $this->country);
        $result->bindParam(':name', $this->name);
        $result->bindParam(':surname', $this->surname);
        $result->bindParam(':birth', $this->birth);

        $req = $result->execute(); 
        echo "on est passé par l'insert";
        return $req; 
    }

    /**
     * update un joueur si le id est deja prit(il faut tout repréciser)
     */
    private function update(){  
        $result = $this->bdd->prepare(
            "UPDATE `joueur` 
            SET ID_JOUEUR = :id, 
            ID_PAYS = :codepays, 
            NOM_JOUEUR = :nom, 
            PRENOM_JOUEUR = :prenom  
            WHERE ID_JOUEUR  = :id"
    );
        $result->bindParam(':id', $this->id);
        $result->bindParam(':codepays', $this->country);
        $result->bindParam(':nom', $this->name);
        $result->bindParam(':prenom', $this->surname);
        $req = $result->execute();
        echo "on est passé par l'update";
    }
    
    /**
     * en fonction du matricule rentré cette fonction renvoie soit vers l'insert si aucun matricule soit vers l'update
     */
    public function write(){
        $result = $this->bdd->prepare(
            "SELECT * FROM `joueur`
            WHERE ID_JOUEUR = :id"
        );
        $result->bindParam(':id', $this->id);
        $result->execute(); 
        $req = $result->fetch();
        if(!$req){
            $this->insert();
        }else {
           $this->update();
        }
    }

    /**
     * l'utilisateur rentre un nom ou un prenom ou un pays et on le trouve dans la bdd
     * @param string
     * @return array
     */
    public function poster($id, $snom, $sprenom, $spays){
        $sql = "SELECT * FROM `joueur` 
                INNER JOIN pays 
                on joueur.ID_PAYS = pays.ID_PAYS 
                WHERE NOM_JOUEUR like :nom 
                AND PRENOM_JOUEUR like :prenom
                AND ID_JOUEUR = :id
                AND (pays.ID_PAYS like :pays 
                OR pays.ACRO_PAYS like :pays 
                OR pays.NOM_PAYS like :pays)";

       $result  = $this->bdd->prepare($sql);

       //concaténation pour le LIKE)
       $snom="%".$snom."%";
       $sprenom="%".$sprenom."%";
       $spays="%".$spays."%";

       $result->bindParam(":nom", $snom);
       $result->bindParam(":prenom", $sprenom);
       $result->bindParam(":id", $id);
       $result->bindParam(":pays", $spays);

       $result->execute(); 

       $req = $result->fetchAll();
       echo 'recherche...';
       return $req;
   }

   private function delete(){
       $result = $this->bdd->prepare("DELETE FROM `joueur` WHERE ID_JOUEUR = :id");
       $result->bindParam(":id", $this->id);
       $req = $result->execute();
       echo "joueur supprimé";
   }
}


