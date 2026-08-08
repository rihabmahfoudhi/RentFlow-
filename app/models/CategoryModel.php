<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CategoryModel extends Model
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT id_categorie, nom, description FROM categorie ORDER BY nom ASC'
        );
    }

    /**
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT id_categorie, nom, description FROM categorie WHERE id_categorie = :id',
            ['id' => $id]
        );
    }

    /**
     * Vérifier si une catégorie a des équipements liés
     */
    public function hasEquipment(int $categoryId): bool
    {
        $result = $this->fetchOne(
            'SELECT COUNT(*) as count FROM equipement WHERE id_categorie = :id',
            ['id' => $categoryId]
        );
        
        return (int) ($result['count'] ?? 0) > 0;
    }

    public function create(string $name, ?string $description = null): int
    {
        return $this->insert('categorie', [
            'nom' => $name,
            'description' => $description,
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateCategory(int $id, array $data): int
    {
        return $this->update('categorie', $data, 'id_categorie = :id', ['id' => $id]);
    }

    public function deleteCategory(int $id): int
    {
        return $this->delete('categorie', 'id_categorie = :id', ['id' => $id]);
    }
}
