<?php
class Internaute {
    protected $cin;
    protected $nom;
    protected $prenom;
    protected $email;

    public function __construct($cin, $nom, $prenom, $email) {
        $this->cin = $cin;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
    }

    public function getInfo() {
        return "CIN: $this->cin, Nom: $this->nom, Prénom: $this->prenom, Email: $this->email";
    }
}
?>
