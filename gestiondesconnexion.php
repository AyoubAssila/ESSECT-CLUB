<?php
session_start(); // Démarrer la session pour stocker les informations du membre

// Connexion à la base de données
$db = new PDO('mysql:host=localhost;dbname=nom_de_la_base_de_donnees', 'utilisateur', 'mot_de_passe');

class MembreController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
            // Le membre existe, stocker ses informations en session
            $_SESSION['membre'] = $membre;
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

    /**
     * Obtenir le nom de la table en fonction du club.
     */
    private function getTableNameForClub($clubName) {
        switch ($clubName) {
            case 'Infolab':
                return 'membreinfolab';
            case 'Enactus':
                return 'membreenactus';
            case 'Fahmologia':
                return 'membrefahmologia';
            case 'Radio':
                return 'membreradio';
            default:
                throw new Exception("Club non trouvé");
        }
    }
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'];
    $clubName = $_POST['club'];

    // Instancier le contrôleur et appeler la méthode login
    $membreController = new MembreController($db);
    $membreController->login($cin, $clubName);
}
?>