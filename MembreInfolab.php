<?php
require_once "Internaute.php";

class MembreInfolab extends Internaute {
    protected $departement;
    protected $statut;

    public function __construct($cin, $nom, $prenom, $email, $departement, $statut) {
        parent::__construct($cin, $nom, $prenom, $email);
        $this->departement = $departement;
        $this->statut = $statut;
    }
}
?>
