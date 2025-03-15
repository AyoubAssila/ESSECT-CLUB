<?php
require_once "Club.php";

class Fahmologia extends Club {
    public function __construct($nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        parent::__construct("fahmologia", $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation);
    }
}
?>
