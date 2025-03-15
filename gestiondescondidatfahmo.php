<?php
require_once "model/CondidateFahmologia.php";

class CandidatureController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction pour valider et nettoyer les entrées utilisateur.
     */
    private function validateInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    /**
     * Gérer l'upload du CV en toute sécurité.
     */
    private function uploadCV($file) {
        $targetDir = "uploads/cvs/";
        $fileName = basename($file["name"]);
        $targetFilePath = $targetDir . uniqid() . "_" . $fileName;
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Vérifier le format du fichier
        $allowedTypes = ['pdf', 'doc', 'docx'];
        if (!in_array($fileType, $allowedTypes)) {
            return ["error" => "Seuls les fichiers PDF, DOC et DOCX sont autorisés."];
        }

        // Vérifier la taille du fichier (max 2MB)
        if ($file["size"] > 2 * 1024 * 1024) {
            return ["error" => "Le fichier est trop volumineux (max 2MB)."];
        }

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return ["success" => $targetFilePath];
        } else {
            return ["error" => "Erreur lors du téléchargement du fichier."];
        }
    }

    /**
     * Fonction pour soumettre une candidature.
     */
    public function submitCandidature($data, $file) {
        try {
            // Validation et nettoyage des entrées utilisateur
            $cin = $this->validateInput($data['cin']);
            $nom = $this->validateInput($data['nom']);
            $prenom = $this->validateInput($data['prenom']);
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? $data['email'] : null;
            $departement = $this->validateInput($data['departement']);
            $motivation = $this->validateInput($data['motivation']);
            $experience = $this->validateInput($data['experience']);
            $experienceDetails = !empty($data['experienceDetails']) ? $this->validateInput($data['experienceDetails']) : null;
            $competences = $this->validateInput($data['competences']);
            $disponibilite = $this->validateInput($data['disponibilite']);
            $idee = !empty($data['idee']) ? $this->validateInput($data['idee']) : null;

            // Vérification que tous les champs obligatoires sont remplis
            if (!$cin || !$nom || !$prenom || !$email || !$departement || !$motivation || !$competences || !$disponibilite) {
                return ["error" => "Tous les champs obligatoires doivent être remplis."];
            }

            // Gestion de l'upload du CV
            $uploadResult = $this->uploadCV($file);
            if (isset($uploadResult["error"])) {
                return $uploadResult;
            }
            $cvFilePath = $uploadResult["success"];

            // Préparer et exécuter la requête SQL
            $query = "INSERT INTO condidatfahmologia (cin, nom, prenom, email, departement, motivation, experience, experienceDetails, competences, disponibilite, idee, cvFilePath) 
                      VALUES (:cin, :nom, :prenom, :email, :departement, :motivation, :experience, :experienceDetails, :competences, :disponibilite, :idee, :cvFilePath)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':cin' => $cin,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':departement' => $departement,
                ':motivation' => $motivation,
                ':experience' => $experience,
                ':experienceDetails' => $experienceDetails,
                ':competences' => $competences,
                ':disponibilite' => $disponibilite,
                ':idee' => $idee,
                ':cvFilePath' => $cvFilePath
            ]);

            return ["success" => "Candidature soumise avec succès pour Fahmologia."];

        } catch (Exception $e) {
            return ["error" => "Une erreur est survenue : " . $e->getMessage()];
        }
    }
}
?>
