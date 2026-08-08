<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PageModel;
use App\Models\UserModel;

final class SiteController extends Controller
{
    private PageModel $pageModel;
    private UserModel $userModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
        $this->userModel = new UserModel();
    }

    public function home(): void
    {
        $data = $this->pageModel->getPageData('home');
        $data['flash'] = $this->getFlash();

        $this->render('home', $data);
    }

    public function about(): void
    {
        $this->render('about', $this->pageModel->getPageData('about'));
    }

    public function services(): void
    {
        $this->render('services', $this->pageModel->getPageData('services'));
    }

    public function team(): void
    {
        $this->redirect('home');
    }

    public function contact(): void
    {
        $this->render('contact', $this->pageModel->getPageData('contact'));
    }

    public function loginForm(): void
    {
        $data = [
            'flash' => $this->getFlash(),
            'errors' => [],
        ];

        $this->render('login', $data);
    }

    /**
     * @param array<string, mixed> $postData
     */
    public function login(array $postData = []): void
    {
        $postData = $postData ?: $_POST;
        $email = strtolower(trim((string) ($postData['email'] ?? '')));
        $password = (string) ($postData['mot_de_passe'] ?? '');

        $user = $this->userModel->findByEmail($email);

        if ($user === false || !password_verify($password, (string) ($user['mot_de_passe'] ?? ''))) {
            $this->setFlash('danger', 'Identifiants invalides.');
            $this->render('login', [
                'flash' => $this->getFlash(),
                'errors' => ['global' => 'Email ou mot de passe incorrect.'],
            ]);
            return;
        }

        $_SESSION['user_id'] = (int) $user['id_user'];
        $_SESSION['nom'] = (string) $user['nom'];
        $_SESSION['prenom'] = (string) $user['prenom'];
        $_SESSION['role'] = (string) $user['role'];

        $this->userModel->recordLogin((int) $user['id_user'], true);

        if ((string) $user['role'] === 'Client') {
            $this->redirect('client-dashboard');
        }

        $this->redirect('admin-dashboard');
    }

    public function registerForm(): void
    {
        $data = [
            'flash' => $this->getFlash(),
            'formData' => [],
            'errors' => [],
            'roles' => [
                'Responsable' => 'Responsable Inventaire',
                'Agent' => 'Agent',
                'Client' => 'Client',
            ],
        ];

        $this->render('register', $data);
    }

    /**
     * @param array<string, mixed> $postData
     */
    public function register(array $postData = []): void
    {
        $postData = $postData ?: $_POST;
        $errors = [];
        $formData = [];

        foreach (['nom', 'prenom', 'email', 'mot_de_passe', 'telephone', 'role'] as $field) {
            $formData[$field] = trim((string) ($postData[$field] ?? ''));
        }

        if ($formData['nom'] === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if ($formData['prenom'] === '') {
            $errors['prenom'] = 'Le prénom est obligatoire.';
        }

        if ($formData['email'] === '' || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Une adresse email valide est requise.';
        } elseif ($this->userModel->emailExists($formData['email'])) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }

        if ($formData['mot_de_passe'] === '') {
            $errors['mot_de_passe'] = 'Le mot de passe est obligatoire.';
        } elseif (strlen($formData['mot_de_passe']) < 8) {
            $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($formData['telephone'] === '') {
            $errors['telephone'] = 'Le téléphone est obligatoire.';
        }

        $allowedRoles = ['Responsable' => 'Responsable Inventaire', 'Agent' => 'Agent', 'Client' => 'Client'];
        if (!isset($allowedRoles[$formData['role']])) {
            $errors['role'] = 'Veuillez choisir un rôle valide.';
        }

        if ($errors !== []) {
            $this->setFlash('danger', 'Veuillez corriger les erreurs du formulaire.');

            $this->render('register', [
                'flash' => $this->getFlash(),
                'formData' => $formData,
                'errors' => $errors,
                'roles' => $allowedRoles,
            ]);
            return;
        }

        $userId = $this->userModel->register([
            'nom' => $formData['nom'],
            'prenom' => $formData['prenom'],
            'email' => $formData['email'],
            'mot_de_passe' => password_hash($formData['mot_de_passe'], PASSWORD_DEFAULT),
            'telephone' => $formData['telephone'],
            'role' => $formData['role'],
        ]);

        if ($userId > 0) {
            $this->setFlash('success', 'Votre compte a été créé avec succès.');
            $this->redirect('home');
        }

        $this->setFlash('danger', 'Une erreur est survenue lors de la création du compte.');
        $this->render('register', [
            'flash' => $this->getFlash(),
            'formData' => $formData,
            'errors' => ['global' => 'Impossible d’enregistrer l’utilisateur pour le moment.'],
            'roles' => $allowedRoles,
        ]);
    }

    public function notFound(): void
    {
        $this->render('not-found', $this->pageModel->getPageData('not-found'));
    }
}
