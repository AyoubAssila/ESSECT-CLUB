<?php
require_once "Internaute.php";

class Condidatefahmologia extends Internaute {
    protected $departement;
    protected $motivation;
    protected $experience;
    protected $experienceDetails;
    protected $competences;
    protected $disponibilite;   
    protected $idee;
    protected $cvFilePath;

    public function __construct($cin, $nom, $prenom, $email, $departement, $motivation, $experience, $experienceDetails, $competences, $disponibilite, $idee, $cvFilePath) {
        parent::__construct($cin, $nom, $prenom, $email);
        $this->departement = $departement;
        $this->motivation = $motivation;
        $this->experience = $experience;
        $this->experienceDetails = $experienceDetails;
        $this->competences = $competences;
        $this->disponibilite = $disponibilite;
        $this->idee = $idee;
        $this->cvFilePath = $cvFilePath;
    }
}
?>
