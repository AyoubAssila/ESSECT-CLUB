<?php
require_once "Club.php";

class Radio extends Club {
    public function __construct($nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        parent::__construct("Radio", $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation);
    }
}
?>
