<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class EquipmentModel extends Model
{
    /**
     * Etats possibles pour un equipement.
     *
     * @var array<int, string>
     */
    public const ETATS = ['Disponible', 'En location', 'Maintenance', 'Endommage'];

    /**
     * Recupere tous les equipements avec le nom de leur categorie (jointure).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT e.id_eq, e.nom, e.description, e.prix_jour, e.stock,
                    e.seuil_alerte, e.etat, e.categorie_id, c.nom AS categorie_nom
             FROM equipement e
             INNER JOIN categorie c ON c.id_categorie = e.categorie_id
             ORDER BY e.nom ASC'
        );
    }

    /**
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT e.id_eq, e.nom, e.description, e.prix_jour, e.stock,
                    e.seuil_alerte, e.etat, e.categorie_id, c.nom AS categorie_nom
             FROM equipement e
             INNER JOIN categorie c ON c.id_categorie = e.categorie_id
             WHERE e.id_eq = :id',
            ['id' => $id]
        );
    }

    /**
     * Un equipement ne peut pas etre supprime tant qu'il est en cours de location.
     * (Cette verification se base sur l'etat en attendant l'entite Location.)
     */
    public function isCurrentlyRented(int $id): bool
    {
        $row = $this->fetchOne(
            'SELECT etat FROM equipement WHERE id_eq = :id',
            ['id' => $id]
        );

        return $row !== false && ($row['etat'] ?? '') === 'En location';
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return $this->insert('equipement', $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateEquipment(int $id, array $data): int
    {
        return $this->update('equipement', $data, 'id_eq = :id', ['id' => $id]);
    }

    public function deleteEquipment(int $id): int
    {
        return $this->delete('equipement', 'id_eq = :id', ['id' => $id]);
    }
        /**
     * Recherche multicritere des equipements : par ID, par etat et/ou par categorie.
     * Chaque critere est optionnel (null ou vide = ignore).
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(?int $id, ?string $etat, ?int $categorieId): array
    {
        $sql    = 'SELECT e.id_eq, e.nom, e.description, e.prix_jour, e.stock,
                    e.seuil_alerte, e.etat, e.categorie_id, c.nom AS categorie_nom
             FROM equipement e
             INNER JOIN categorie c ON c.id_categorie = e.categorie_id
             WHERE 1 = 1';
        $params = [];

        if ($id !== null && $id > 0) {
            $sql .= ' AND e.id_eq = :id';
            $params['id'] = $id;
        }

        if ($etat !== null && $etat !== '') {
            $sql .= ' AND e.etat = :etat';
            $params['etat'] = $etat;
        }

        if ($categorieId !== null && $categorieId > 0) {
            $sql .= ' AND e.categorie_id = :categorie_id';
            $params['categorie_id'] = $categorieId;
        }

        $sql .= ' ORDER BY e.nom ASC';

        return $this->fetchAll($sql, $params);
    }

    /**
     * Récupère les équipements d'une catégorie donnée (avec jointure catégorie).
     * Les équipements "Disponible" apparaissent en premier.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getByCategory(int $categoryId): array
    {
        return $this->fetchAll(
            'SELECT e.id_eq, e.nom, e.description, e.prix_jour, e.stock,
                    e.seuil_alerte, e.etat, e.categorie_id, c.nom AS categorie_nom
             FROM equipement e
             INNER JOIN categorie c ON c.id_categorie = e.categorie_id
             WHERE e.categorie_id = :cat_id
             ORDER BY
                CASE e.etat WHEN \'Disponible\' THEN 0 ELSE 1 END ASC,
                e.nom ASC',
            ['cat_id' => $categoryId]
        );
    }
}
