<?php
require_once "Club.php";

class Enactus extends Club {
    public function __construct($nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        parent::__construct("Enactus", $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation);
    }
}
?>
