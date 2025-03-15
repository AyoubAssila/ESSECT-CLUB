<?php
class Club {
    protected $nom;
    protected $nombreMembres;
    protected $nombreDepartements;
    protected $fondateur;
    protected $president;
    protected $dateCreation;

    public function __construct($nom, $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        $this->nom = $nom;
        $this->nombreMembres = $nombreMembres;
        $this->nombreDepartements = $nombreDepartements;
        $this->fondateur = $fondateur;
        $this->president = $president;
        $this->dateCreation = $dateCreation;
    }

    public function getDetails() {
        return "Club: $this->nom, Membres: $this->nombreMembres, Départements: $this->nombreDepartements, Fondateur: $this->fondateur, Président: $this->president, Création: $this->dateCreation";
    }
}
?>
