<?php
require_once "Club.php";

class Infolab extends Club {
    public function __construct($nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        parent::__construct("Infolab", $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation);
    }
}
?>
