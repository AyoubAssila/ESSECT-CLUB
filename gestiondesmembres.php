<?php
require_once "web 2 projet/model/MembreInfolab.php";
require_once "web 2 projet/model/MembreEnactus.php";
require_once "web 2 projet/model/MembreRadio.php";
require_once "web 2 projet/model/MembreFahmologia.php";

class MembreController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // ==================================================
    // Méthode pour gérer le login
    // ==================================================

    /**
     * Vérifier si un membre existe dans la table correspondante au club.
     * Rediriger vers la page du club s'il existe, sinon rediriger vers la page de postulation.
     */
    public function login($cin, $clubName) {
        $tableName = $this->getTableNameForClub($clubName);

        // Vérifier si le membre existe dans la table du club
        $query = "SELECT * FROM $tableName WHERE cin = :cin";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cin' => $cin]);
        $membre = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($membre) {
            // Le membre existe, rediriger vers la page du club avec ses informations
            session_start();
            $_SESSION['membre'] = $membre; // Stocker les informations du membre en session
            $this->redirectToClubPage($clubName);
        } else {
            // Le membre n'existe pas, rediriger vers la page de postulation
            header("Location: postuler.html");
            exit();
        }
    }

    /**
     * Rediriger vers la page du club correspondant.
     */
    private function redirectToClubPage($clubName) {
        switch ($clubName) {
            case 'Infolab':
                header("Location: espace_membre_infolab.html");
                break;
            case 'Enactus':
                header("Location: espace_membre_enactus.html");
                break;
            case 'Fahmologia':
                header("Location: espace_membre_fahmologia.html");
                break;
            case 'Radio':
                header("Location: espace_membre_radio.html");
                break;
            default:
                throw new Exception("Club non trouvé");
        }
        exit();
    }

    // ==================================================
    // Méthodes existantes
    // ==================================================

    /**
     * Obtenir le nom de la table en fonction du club.
     */
    private function getTableNameForClub($clubName) {
        switch ($clubName) {
            case 'Radio':
                return 'membreradio';
            case 'Enactus':
                return 'membreenactus';
            case 'Infolab':
                return 'membreinfolab';
            case 'Fahmologia':
                return 'membrefahmologia';
            default:
                throw new Exception("Club non trouvé");
        }
    }
}
?>