<?php
require_once "Internaute.php";

class Admin extends Internaute {
    public function __construct($cin, $nom, $prenom, $email) {
        parent::__construct($cin, $nom, $prenom, $email);
    }
}
?>
