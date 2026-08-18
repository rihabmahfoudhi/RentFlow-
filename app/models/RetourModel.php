<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class RetourModel extends Model
{
    /**
     * Statuts possibles pour un retour.
     *
     * @var array<int, string>
     */
    public const STATUTS = ['En attente', 'Validé'];

    // ──────────────────────────────────────────────────────────────
    // Lecture
    // ──────────────────────────────────────────────────────────────

    /**
     * Récupère toutes les locations actives (Acceptée ou En cours)
     * pour alimenter le <select> du formulaire "Enregistrer un retour".
     *
     * @return array<int, array<string, mixed>>
     */
    public function getLocationsEnCours(): array
    {
        return $this->fetchAll(
            "SELECT l.id_location, l.date_debut, l.date_fin, l.prix_total, l.statut,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.email AS client_email,
                    e.nom AS equipement_nom, e.prix_jour, e.id_eq, e.etat AS etat_equipement,
                    l.client_id, l.equipement_id
             FROM location l
             INNER JOIN utilisateur u ON u.id_user = l.client_id
             INNER JOIN equipement  e ON e.id_eq   = l.equipement_id
             WHERE l.statut IN ('Acceptée', 'En cours')
             ORDER BY l.id_location DESC"
        );
    }

    /**
     * Récupère tous les retours avec jointures complètes.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT r.id_retour, r.location_id, r.date_retour, r.etat_equipement,
                    r.jours_retard, r.frais_additionnels, r.statut, r.date_enregistrement,
                    l.date_debut, l.date_fin, l.prix_total,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.email AS client_email,
                    e.nom AS equipement_nom, e.id_eq
             FROM retour r
             INNER JOIN location    l ON l.id_location = r.location_id
             INNER JOIN utilisateur u ON u.id_user     = l.client_id
             INNER JOIN equipement  e ON e.id_eq       = l.equipement_id
             ORDER BY r.date_enregistrement DESC, r.id_retour DESC'
        );
    }

    /**
     * Récupère un retour par son ID (avec jointures).
     *
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT r.id_retour, r.location_id, r.date_retour, r.etat_equipement,
                    r.jours_retard, r.frais_additionnels, r.statut, r.date_enregistrement,
                    l.date_debut, l.date_fin, l.prix_total, l.client_id, l.equipement_id, l.statut AS location_statut,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.email AS client_email,
                    e.nom AS equipement_nom, e.id_eq, e.stock
             FROM retour r
             INNER JOIN location    l ON l.id_location = r.location_id
             INNER JOIN utilisateur u ON u.id_user     = l.client_id
             INNER JOIN equipement  e ON e.id_eq       = l.equipement_id
             WHERE r.id_retour = :id',
            ['id' => $id]
        );
    }

    /**
     * Récupère les infos d'une location pour le formulaire (AJAX).
     *
     * @return array<string, mixed>|false
     */
    public function findLocationById(int $id): array|false
    {
        return $this->fetchOne(
            "SELECT l.id_location, l.date_debut, l.date_fin, l.prix_total, l.statut,
                    u.nom AS client_nom, u.prenom AS client_prenom,
                    e.nom AS equipement_nom, e.prix_jour, e.id_eq
             FROM location l
             INNER JOIN utilisateur u ON u.id_user = l.client_id
             INNER JOIN equipement  e ON e.id_eq   = l.equipement_id
             WHERE l.id_location = :id",
            ['id' => $id]
        );
    }

    // ──────────────────────────────────────────────────────────────
    // Écriture
    // ──────────────────────────────────────────────────────────────

    /**
     * Enregistre un retour (statut "En attente").
     * N'effectue PAS encore les mises à jour de stock/état/location.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return $this->insert('retour', $data);
    }

    /**
     * Validation définitive par le Responsable Inventaire.
     *
     * Dans une transaction unique :
     *  1. Retour → statut "Validé"
     *  2. Équipement → etat mis à jour + stock + 1
     *  3. Location → statut "Terminée"
     *
     * @throws \RuntimeException si une étape échoue
     */
    public function valider(int $idRetour): void
    {
        $retour = $this->findById($idRetour);

        if ($retour === false) {
            throw new \RuntimeException('Retour introuvable.');
        }

        if ((string) ($retour['statut'] ?? '') !== 'En attente') {
            throw new \RuntimeException('Ce retour a déjà été validé.');
        }

        $idLocation   = (int) $retour['location_id'];
        $idEquipement = (int) $retour['id_eq'];
        $etatFinal    = (string) $retour['etat_equipement'];
        $stockActuel  = (int) $retour['stock'];

        $this->db->beginTransaction();

        try {
            // 1. Retour → Validé
            $stmt1 = $this->db->prepare(
                "UPDATE retour SET statut = 'Validé' WHERE id_retour = :id"
            );
            $stmt1->execute(['id' => $idRetour]);

            // 2. Équipement → état + stock + 1
            $stmt2 = $this->db->prepare(
                'UPDATE equipement SET etat = :etat, stock = :stock WHERE id_eq = :id'
            );
            $stmt2->execute([
                'etat'  => $etatFinal,
                'stock' => $stockActuel + 1,
                'id'    => $idEquipement,
            ]);

            // 3. Location → Terminée
            $stmt3 = $this->db->prepare(
                "UPDATE location SET statut = 'Terminée' WHERE id_location = :id"
            );
            $stmt3->execute(['id' => $idLocation]);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw new \RuntimeException('Erreur lors de la validation : ' . $e->getMessage(), 0, $e);
        }
    }
}
