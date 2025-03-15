<?php
require_once "web 2 projet/model/Club.php";
require_once "web 2 projet/model/Radio.php";
require_once "web 2 projet/model/Enactus.php";
require_once "web 2 projet/model/Infolab.php";
require_once "web 2 projet/model/Fahmologia.php";

class ClubController {
    private $clubModel;

    public function __construct($nombreMembres, $nombreDepartements, $fondateur, $president, $dateCreation) {

    }

    /**
     * Afficher les détails d’un club.
     */
    public function showClubDetails($clubName) {
        $club = null;

        switch ($clubName) {
            case 'Radio':
                $club = new Radio(70, 4, 'Oucema abdelkerim', 'Abdelkerim Oucema', '2025-02-06');
                break;
            case 'Enactus':
                $club = new Enactus(100, 3, '', 'Rayen Souli', '2016-11-23');
                break;
            case 'Infolab':
                $club = new Infolab(116, 3, 'Afef Slama', 'Nour Cherni', '2022-09-25');
                break;
            case 'Fahmologia':
                $club = new Fahmologia(50, 4, 'Mehdi Chérif', 'Oumaima Ben Nasr', '2022-11-12');
                break;
            default:
                throw new Exception("Club non trouvé");
        }

        return $club->getDetails();
    }

    /**
     * Lister tous les clubs.
     */
    public function listClubs() {
        $clubs = [
            new Radio(70, 4, 'Oucema abdelkerim', 'Abdelkerim Oucema', '2025-02-06'),
            new Enactus(100, 3, '', 'Rayen Souli', '2016-11-23'),
            new Infolab(116, 3, 'Afef Slama', 'Nour Cherni', '2022-09-25'),
            new Fahmologia(50, 4, 'Mehdi Chérif', 'Oumaima Ben Nasr', '2022-11-12')
        ];

        $clubDetails = [];
        foreach ($clubs as $club) {
            $clubDetails[] = $club->getDetails();
        }

        return $clubDetails;
    }
}
?>