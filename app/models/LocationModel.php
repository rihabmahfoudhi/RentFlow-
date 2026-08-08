<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class LocationModel extends Model
{
    /**
     * Statuts possibles pour une location.
     *
     * @var array<int, string>
     */
    public const STATUTS = ['En attente', 'Validee', 'En cours', 'Terminee', 'Annulee'];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT l.id_location, l.client_id, l.equipement_id, l.date_debut, l.date_fin,
                    l.prix_total, l.statut, l.date_creation,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.email AS client_email,
                    e.nom AS equipement_nom, e.prix_jour
             FROM location l
             INNER JOIN utilisateur u ON u.id_user = l.client_id
             INNER JOIN equipement e ON e.id_eq = l.equipement_id
             ORDER BY l.date_creation DESC, l.id_location DESC'
        );
    }

    /**
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT l.id_location, l.client_id, l.equipement_id, l.date_debut, l.date_fin,
                    l.prix_total, l.statut, l.date_creation,
                    u.nom AS client_nom, u.prenom AS client_prenom, u.email AS client_email,
                    e.nom AS equipement_nom, e.prix_jour
             FROM location l
             INNER JOIN utilisateur u ON u.id_user = l.client_id
             INNER JOIN equipement e ON e.id_eq = l.equipement_id
             WHERE l.id_location = :id',
            ['id' => $id]
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return $this->insert('location', $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateLocation(int $id, array $data): int
    {
        return $this->update('location', $data, 'id_location = :id', ['id' => $id]);
    }

    public function deleteLocation(int $id): int
    {
        return $this->delete('location', 'id_location = :id', ['id' => $id]);
    }
}
