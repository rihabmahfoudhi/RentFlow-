<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\EquipmentModel;
use App\Models\LocationModel;

final class DashboardController extends Controller
{
    private UserModel $userModel;
    private CategoryModel $categoryModel;
    private EquipmentModel $equipmentModel;
    private LocationModel $locationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->categoryModel = new CategoryModel();
        $this->equipmentModel = new EquipmentModel();
        $this->locationModel = new LocationModel();
    }

    public function clientDashboard(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Client', 'Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/client', [
            'user' => $this->currentUser(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function adminDashboard(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/admin', [
            'user' => $this->currentUser(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function categories(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/categories', [
            'user' => $this->currentUser(),
            'categories' => $this->categoryModel->getAll(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function categoriesAction(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $action = (string) ($_POST['action'] ?? '');
        $postData = $_POST ?? [];

        if ($action === 'add') {
            $this->addCategory($postData);
        } elseif ($action === 'edit') {
            $this->editCategory($postData);
        } elseif ($action === 'delete') {
            $this->deleteCategory($postData);
        }

        $this->redirect('categories');
    }

    private function addCategory(array $data): void
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        if (empty($nom)) {
            $this->setFlash('danger', 'Le nom de la catégorie est requis.');
            return;
        }

        try {
            $this->categoryModel->create($nom, $description ?: null);
            $this->setFlash('success', 'Catégorie ajoutée avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    private function editCategory(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);
        $nom = trim((string) ($data['nom'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        if (empty($id) || empty($nom)) {
            $this->setFlash('danger', 'Données invalides.');
            return;
        }

        try {
            $this->categoryModel->updateCategory($id, [
                'nom' => $nom,
                'description' => $description ?: null,
            ]);
            $this->setFlash('success', 'Catégorie mise à jour avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    private function deleteCategory(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if (empty($id)) {
            $this->setFlash('danger', 'Données invalides.');
            return;
        }

        if ($this->categoryModel->hasEquipment($id)) {
            $this->setFlash('warning', 'Impossible de supprimer cette catégorie car elle contient des équipements.');
            return;
        }

        try {
            $this->categoryModel->deleteCategory($id);
            $this->setFlash('success', 'Catégorie supprimée avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function equipment(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/Equipment', [
            'user' => $this->currentUser(),
            'equipements' => $this->equipmentModel->getAll(),
            'categories' => $this->categoryModel->getAll(),
            'etats' => EquipmentModel::ETATS,
            'flash' => $this->getFlash(),
        ]);
    }
    

    public function equipmentAction(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $action = (string) ($_POST['action'] ?? '');
        $postData = $_POST ?? [];

        if ($action === 'add') {
            $this->addEquipment($postData);
        } elseif ($action === 'edit') {
            $this->editEquipment($postData);
        } elseif ($action === 'delete') {
            $this->deleteEquipment($postData);
        }

        $this->redirect('equipements');
    }

    /**
     * Valide et normalise les champs communs (ajout/modification) d'un equipement.
     * Retourne le tableau de donnees pretes pour la BDD, ou null si invalide (avec flash message).
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    private function validateEquipmentData(array $data): ?array
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $prixJour = (string) ($data['prix_jour'] ?? '');
        $stock = (string) ($data['stock'] ?? '');
        $seuilAlerte = (string) ($data['seuil_alerte'] ?? '');
        $etat = (string) ($data['etat'] ?? '');
        $categorieId = (int) ($data['categorie_id'] ?? 0);

        if ($nom === '') {
            $this->setFlash('danger', 'Le nom de l\'équipement est requis.');
            return null;
        }

        if (!is_numeric($prixJour) || (float) $prixJour < 0) {
            $this->setFlash('danger', 'Le prix par jour doit être un nombre positif.');
            return null;
        }

        if (!ctype_digit($stock) || (int) $stock < 0) {
            $this->setFlash('danger', 'Le stock doit être un entier positif.');
            return null;
        }

        if (!ctype_digit($seuilAlerte) || (int) $seuilAlerte < 0) {
            $this->setFlash('danger', 'Le seuil d\'alerte doit être un entier positif.');
            return null;
        }

        if (!in_array($etat, EquipmentModel::ETATS, true)) {
            $this->setFlash('danger', 'L\'état sélectionné est invalide.');
            return null;
        }

        if ($categorieId <= 0 || $this->categoryModel->findById($categorieId) === false) {
            $this->setFlash('danger', 'Veuillez sélectionner une catégorie valide.');
            return null;
        }

        return [
            'nom' => $nom,
            'description' => $description !== '' ? $description : null,
            'prix_jour' => (float) $prixJour,
            'stock' => (int) $stock,
            'seuil_alerte' => (int) $seuilAlerte,
            'etat' => $etat,
            'categorie_id' => $categorieId,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function addEquipment(array $data): void
    {
        $equipmentData = $this->validateEquipmentData($data);

        if ($equipmentData === null) {
            return;
        }

        try {
            $this->equipmentModel->create($equipmentData);
            $this->setFlash('success', 'Équipement ajouté avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function editEquipment(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Équipement introuvable.');
            return;
        }

        $existing = $this->equipmentModel->findById($id);

        if ($existing === false) {
            $this->setFlash('danger', 'Équipement introuvable.');
            return;
        }

        $equipmentData = $this->validateEquipmentData($data);

        if ($equipmentData === null) {
            return;
        }

        try {
            $this->equipmentModel->updateEquipment($id, $equipmentData);
            $this->setFlash('success', 'Équipement mis à jour avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function deleteEquipment(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Données invalides.');
            return;
        }

        if ($this->equipmentModel->isCurrentlyRented($id)) {
            $this->setFlash('warning', 'Impossible de supprimer cet équipement : il est actuellement en location.');
            return;
        }

        try {
            $this->equipmentModel->deleteEquipment($id);
            $this->setFlash('success', 'Équipement supprimé avec succès.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function locations(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/locations', [
            'user' => $this->currentUser(),
            'locations' => $this->locationModel->getAll(),
            'clients' => $this->userModel->getClients(),
            'equipements' => $this->equipmentModel->getAll(),
            'statuts' => LocationModel::STATUTS,
            'flash' => $this->getFlash(),
        ]);
    }

    public function locationsAction(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $action = (string) ($_POST['action'] ?? '');
        $postData = $_POST ?? [];

        if ($action === 'add') {
            $this->addLocation($postData);
        } elseif ($action === 'edit') {
            $this->editLocation($postData);
        } elseif ($action === 'delete') {
            $this->deleteLocation($postData);
        }

        $this->redirect('locations');
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    private function validateLocationData(array $data): ?array
    {
        $clientId = (int) ($data['client_id'] ?? 0);
        $equipementId = (int) ($data['equipement_id'] ?? 0);
        $dateDebut = trim((string) ($data['date_debut'] ?? ''));
        $dateFin = trim((string) ($data['date_fin'] ?? ''));
        $statut = (string) ($data['statut'] ?? 'En attente');

        if ($clientId <= 0) {
            $this->setFlash('danger', 'Veuillez selectionner un client.');
            return null;
        }

        $client = $this->userModel->findById($clientId);

        if ($client === false || ($client['role'] ?? '') !== 'Client') {
            $this->setFlash('danger', 'Le client selectionne est invalide.');
            return null;
        }

        if ($equipementId <= 0) {
            $this->setFlash('danger', 'Veuillez selectionner un equipement.');
            return null;
        }

        $equipement = $this->equipmentModel->findById($equipementId);

        if ($equipement === false) {
            $this->setFlash('danger', 'L\'equipement selectionne est invalide.');
            return null;
        }

        $debut = \DateTimeImmutable::createFromFormat('Y-m-d', $dateDebut);
        $fin = \DateTimeImmutable::createFromFormat('Y-m-d', $dateFin);

        if (!$debut || $debut->format('Y-m-d') !== $dateDebut) {
            $this->setFlash('danger', 'La date de debut est invalide.');
            return null;
        }

        if (!$fin || $fin->format('Y-m-d') !== $dateFin) {
            $this->setFlash('danger', 'La date de fin est invalide.');
            return null;
        }

        if ($fin < $debut) {
            $this->setFlash('danger', 'La date de fin doit etre superieure ou egale a la date de debut.');
            return null;
        }

        if (!in_array($statut, LocationModel::STATUTS, true)) {
            $this->setFlash('danger', 'Le statut selectionne est invalide.');
            return null;
        }

        $days = max(1, (int) $debut->diff($fin)->days + 1);
        $prixTotal = $days * (float) ($equipement['prix_jour'] ?? 0);

        return [
            'client_id' => $clientId,
            'equipement_id' => $equipementId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'prix_total' => $prixTotal,
            'statut' => $statut,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function addLocation(array $data): void
    {
        $locationData = $this->validateLocationData($data);

        if ($locationData === null) {
            return;
        }

        try {
            $this->locationModel->create($locationData);
            $this->setFlash('success', 'Location ajoutee avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function editLocation(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0 || $this->locationModel->findById($id) === false) {
            $this->setFlash('danger', 'Location introuvable.');
            return;
        }

        $locationData = $this->validateLocationData($data);

        if ($locationData === null) {
            return;
        }

        try {
            $this->locationModel->updateLocation($id, $locationData);
            $this->setFlash('success', 'Location mise a jour avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la mise a jour : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function deleteLocation(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Donnees invalides.');
            return;
        }

        try {
            $this->locationModel->deleteLocation($id);
            $this->setFlash('success', 'Location supprimee avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function users(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $this->render('dashboards/users', [
            'user' => $this->currentUser(),
            'users' => $this->userModel->getAll(),
            'roles' => UserModel::ROLES,
            'flash' => $this->getFlash(),
        ]);
    }

    public function usersAction(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('home');
        }

        if (!in_array($this->currentRole(), ['Agent', 'Responsable'], true)) {
            $this->redirect($this->homeRedirectRoute());
        }

        $action = (string) ($_POST['action'] ?? '');
        $postData = $_POST ?? [];

        if ($action === 'add') {
            $this->addUser($postData);
        } elseif ($action === 'edit') {
            $this->editUser($postData);
        } elseif ($action === 'delete') {
            $this->deleteUser($postData);
        }

        $this->redirect('utilisateurs');
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    private function validateUserData(array $data, ?int $existingUserId = null): ?array
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $role = (string) ($data['role'] ?? '');
        $password = (string) ($data['mot_de_passe'] ?? '');

        if ($nom === '') {
            $this->setFlash('danger', 'Le nom est obligatoire.');
            return null;
        }

        if ($prenom === '') {
            $this->setFlash('danger', 'Le prenom est obligatoire.');
            return null;
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('danger', 'Une adresse email valide est requise.');
            return null;
        }

        if ($this->userModel->emailExists($email, $existingUserId)) {
            $this->setFlash('danger', 'Cette adresse email est deja utilisee.');
            return null;
        }

        if (!in_array($role, UserModel::ROLES, true)) {
            $this->setFlash('danger', 'Veuillez choisir un role valide.');
            return null;
        }

        if ($existingUserId === null && $password === '') {
            $this->setFlash('danger', 'Le mot de passe est obligatoire.');
            return null;
        }

        if ($password !== '' && strlen($password) < 8) {
            $this->setFlash('danger', 'Le mot de passe doit contenir au moins 8 caracteres.');
            return null;
        }

        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone !== '' ? $telephone : null,
            'role' => $role,
        ];

        if ($password !== '') {
            $userData['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return $userData;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function addUser(array $data): void
    {
        $userData = $this->validateUserData($data);

        if ($userData === null) {
            return;
        }

        try {
            $this->userModel->create($userData);
            $this->setFlash('success', 'Utilisateur ajoute avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function editUser(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0 || $this->userModel->findById($id) === false) {
            $this->setFlash('danger', 'Utilisateur introuvable.');
            return;
        }

        $userData = $this->validateUserData($data, $id);

        if ($userData === null) {
            return;
        }

        try {
            $this->userModel->updateUser($id, $userData);

            if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
                $_SESSION['nom'] = (string) $userData['nom'];
                $_SESSION['prenom'] = (string) $userData['prenom'];
                $_SESSION['role'] = (string) $userData['role'];
            }

            $this->setFlash('success', 'Utilisateur mis a jour avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la mise a jour : ' . $e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function deleteUser(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Donnees invalides.');
            return;
        }

        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            $this->setFlash('warning', 'Vous ne pouvez pas supprimer votre propre compte connecte.');
            return;
        }

        try {
            $this->userModel->deleteUser($id);
            $this->setFlash('success', 'Utilisateur supprime avec succes.');
        } catch (\Exception $e) {
            $this->setFlash('danger', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function logout(): void
    {
        if ($this->isAuthenticated()) {
            $this->userModel->recordLogin((int) $_SESSION['user_id'], false);
        }

        session_destroy();
        $this->setFlash('info', 'Vous avez été déconnecté.');
        $this->redirect('home');
    }

    private function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    private function currentRole(): string
    {
        return (string) ($_SESSION['role'] ?? '');
    }

    private function currentUser(): array
    {
        return [
            'id' => (int) ($_SESSION['user_id'] ?? 0),
            'nom' => (string) ($_SESSION['nom'] ?? ''),
            'prenom' => (string) ($_SESSION['prenom'] ?? ''),
            'role' => $this->currentRole(),
        ];
    }

    private function homeRedirectRoute(): string
    {
        return $this->currentRole() === 'Client' ? 'client-dashboard' : 'admin-dashboard';
    }
}
