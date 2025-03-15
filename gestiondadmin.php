<?php
require_once "web 2 projet/model/Admin.php";
require_once "web 2 projet/model/Club.php";
require_once "web 2 projet/model/Candidature.php"; // Pour gérer les candidatures
require_once "web 2 projet/model/Membre.php"; // Pour gérer les membres

class AdminController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // ==================================================
    // Statistiques des clubs
    // ==================================================

    /**
     * Générer des statistiques pour les clubs.
     */
    public function generateStatistics() {
        $statistics = [];

        // Statistiques générales des clubs
        $query = "SELECT nom, nombreMembres, nombreDepartements FROM club";
        $stmt = $this->db->query($query);
        $clubsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clubsData as $clubData) {
            $statistics['clubs'][] = [
                'club' => $clubData['nom'],
                'membres' => $clubData['nombreMembres'],
                'departements' => $clubData['nombreDepartements']
            ];
        }

        // Statistiques des membres par club
        $statistics['membres'] = [
            'Radio' => $this->getMembreStatistics('membreradio'),
            'Enactus' => $this->getMembreStatistics('membreenactus'),
            'Infolab' => $this->getMembreStatistics('membreinfolab'),
            'Fahmologia' => $this->getMembreStatistics('membrefahmologia')
        ];

        // Statistiques des candidatures par club
        $statistics['candidatures'] = $this->getCandidatureStatistics();

        return $statistics;
    }

    /**
     * Récupérer les statistiques des membres d'un club spécifique.
     */
    private function getMembreStatistics($tableName) {
        $query = "SELECT dept, statut, COUNT(*) as count FROM $tableName GROUP BY dept, statut";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les statistiques des candidatures.
     */
    private function getCandidatureStatistics() {
        $query = "SELECT club, COUNT(*) as count FROM candidatures GROUP BY club";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================================================
    // Gestion des membres
    // ==================================================

    /**
     * Récupérer tous les membres d'un club spécifique.
     */
    public function getMembresByClub($clubName) {
        $tableName = $this->getTableNameForClub($clubName);
        $query = "SELECT * FROM $tableName";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter un nouveau membre à un club.
     */
    public function addMembre($clubName, $cin, $nom, $prenom, $email, $departement, $statut) {
        $tableName = $this->getTableNameForClub($clubName);
        $query = "INSERT INTO $tableName (cin, nom, prenom, email, dept, statut) VALUES (:cin, :nom, :prenom, :email, :dept, :statut)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':cin' => $cin,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':dept' => $departement,
            ':statut' => $statut
        ]);
        return "Membre ajouté avec succès au club $clubName.";
    }

    // ==================================================
    // Gestion des candidatures
    // ==================================================

    /**
     * Ajouter un candidat selon son CIN.
     */
    public function addCandidat($cin, $nom, $prenom, $email, $club) {
        $query = "INSERT INTO candidatures (cin, nom, prenom, email, club) VALUES (:cin, :nom, :prenom, :email, :club)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':cin' => $cin,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':club' => $club
        ]);
        return "Candidat ajouté avec succès.";
    }

    /**
     * Valider une candidature.
     */
    public function validateCandidature($candidatureId) {
        $query = "UPDATE candidatures SET statut = 'validé' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $candidatureId]);
        return "Candidature validée avec succès.";
    }

    /**
     * Refuser une candidature.
     */
    public function rejectCandidature($candidatureId) {
        $query = "UPDATE candidatures SET statut = 'refusé' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $candidatureId]);
        return "Candidature refusée.";
    }

    // ==================================================
    // Méthodes utilitaires
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

    // ==================================================
    // Nouvelles fonctions ajoutées
    // ==================================================

    /**
     * Récupérer les statistiques d'un club spécifique.
     */
    public function getClubStatistics($clubName) {
        $statistics = [];

        // Récupérer les informations générales du club
        $query = "SELECT nombreMembres, nombreDepartements FROM club WHERE nom = :clubName";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':clubName' => $clubName]);
        $clubData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clubData) {
            $statistics['totalMembres'] = $clubData['nombreMembres'];
            $statistics['totalDepartements'] = $clubData['nombreDepartements'];

            // Récupérer les membres par département
            $tableName = $this->getTableNameForClub($clubName);
            $query = "SELECT dept, COUNT(*) as count FROM $tableName GROUP BY dept";
            $stmt = $this->db->query($query);
            $statistics['membresParDept'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les membres par statut
            $query = "SELECT statut, COUNT(*) as count FROM $tableName GROUP BY statut";
            $stmt = $this->db->query($query);
            $statistics['membresParStatut'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer le nombre total de candidatures pour ce club
            $query = "SELECT COUNT(*) as totalCandidatures FROM candidatures WHERE club = :clubName";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':clubName' => $clubName]);
            $candidatureData = $stmt->fetch(PDO::FETCH_ASSOC);
            $statistics['totalCandidatures'] = $candidatureData['totalCandidatures'];
        }

        return $statistics;
    }

    /**
     * Récupérer les détails d'un club.
     */
    public function getClubDetails($clubName) {
        $query = "SELECT * FROM club WHERE nom = :clubName";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':clubName' => $clubName]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour les informations d'un club.
     */
    public function updateClub($clubName, $nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {
        $query = "UPDATE club SET nombreMembres = :nombreMembres, nombreDepartements = :nombreDepartements, fondateur = :fondateur, president = :president, dateCreation = :dateCreation WHERE nom = :clubName";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':nombreMembres' => $nombreMembres,
            ':nombreDepartements' => $nombreDepartements,
            ':fondateur' => $fondateur,
            ':president' => $president,
            ':dateCreation' => $dateCreation,
            ':clubName' => $clubName
        ]);
        return "Club mis à jour avec succès.";
    }

    /**
     * Supprimer un club.
     */
    public function deleteClub($clubName) {
        // Supprimer les membres du club
        $tableName = $this->getTableNameForClub($clubName);
        $query = "DELETE FROM $tableName";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Supprimer le club
        $query = "DELETE FROM club WHERE nom = :clubName";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':clubName' => $clubName]);

        return "Club supprimé avec succès.";
    }
}
?>