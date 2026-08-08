<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class UserModel extends Model
{
    /**
     * Roles possibles pour un utilisateur.
     *
     * @var array<int, string>
     */
    public const ROLES = ['Responsable', 'Agent', 'Client'];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT id_user, nom, prenom, email, telephone, role, date_creation
             FROM utilisateur
             ORDER BY nom ASC, prenom ASC'
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getClients(): array
    {
        return $this->fetchAll(
            'SELECT id_user, nom, prenom, email, telephone
             FROM utilisateur
             WHERE role = :role
             ORDER BY nom ASC, prenom ASC',
            ['role' => 'Client']
        );
    }

    /**
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT id_user, nom, prenom, email, telephone, role, date_creation
             FROM utilisateur
             WHERE id_user = :id',
            ['id' => $id]
        );
    }

    public function emailExists(string $email, ?int $ignoredUserId = null): bool
    {
        $params = ['email' => strtolower(trim($email))];
        $sql = 'SELECT id_user FROM utilisateur WHERE email = :email';

        if ($ignoredUserId !== null) {
            $sql .= ' AND id_user <> :id';
            $params['id'] = $ignoredUserId;
        }

        $row = $this->fetchOne(
            $sql,
            $params
        );

        return $row !== false;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function register(array $data): int
    {
        return $this->insert('utilisateur', [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => strtolower(trim((string) $data['email'])),
            'mot_de_passe' => $data['mot_de_passe'],
            'telephone' => $data['telephone'],
            'role' => $data['role'],
        ]);
    }

    /**
     * @return array<string, mixed>|false
     */
    public function findByEmail(string $email): array|false
    {
        return $this->fetchOne(
            'SELECT id_user, nom, prenom, email, mot_de_passe, telephone, role FROM utilisateur WHERE email = :email',
            ['email' => strtolower(trim($email))]
        );
    }

    public function recordLogin(int $userId, bool $connected): void
    {
        $this->prepare(
            'UPDATE utilisateur SET date_creation = CURRENT_TIMESTAMP WHERE id_user = :id',
            ['id' => $userId]
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        return $this->insert('utilisateur', $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateUser(int $id, array $data): int
    {
        return $this->update('utilisateur', $data, 'id_user = :id', ['id' => $id]);
    }

    public function deleteUser(int $id): int
    {
        return $this->delete('utilisateur', 'id_user = :id', ['id' => $id]);
    }
}
