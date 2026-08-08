<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class PageModel extends Model
{
    /**
     * @return array<string, mixed>
     */
    public function getPageData(string $currentRoute): array
    {
        return [
            'siteName' => 'RentFlow',
            'metaTitle' => match ($currentRoute) {
                'about' => 'A propos - RentFlow',
                'services' => 'Services - RentFlow',
                'contact' => 'Contact - RentFlow',
                'not-found' => 'Page introuvable - RentFlow',
                default => 'RentFlow',
            },
            'pageTitle' => match ($currentRoute) {
                'about' => 'A propos',
                'services' => 'Services',
                'contact' => 'Contact',
                'not-found' => '404',
                default => 'Template de depart',
            },
            'pageSubtitle' => match ($currentRoute) {
                'about' => 'Une base propre pour demarrer un nouveau site.',
                'services' => 'Des blocs reutilisables pour votre futur projet.',
                'contact' => 'Un point de contact simple a brancher plus tard.',
                'not-found' => 'La page demandee est introuvable.',
                default => 'Une plateforme moderne de location d’équipements.',
            },
            'navHomeClass' => $currentRoute === 'home' ? 'active' : '',
            'navAboutClass' => $currentRoute === 'about' ? 'active' : '',
            'navServicesClass' => $currentRoute === 'services' ? 'active' : '',
            'navContactClass' => $currentRoute === 'contact' ? 'active' : '',
            'highlights' => [
                ['title' => 'Base propre', 'description' => 'Pas de base de donnees, pas de metier ancien, pas de logique inutile.'],
                ['title' => 'Structure claire', 'description' => 'Des pages simples pour repartir rapidement sur un nouveau besoin.'],
                ['title' => 'Design reutilisable', 'description' => 'Bootstrap et une mise en page moderne deja en place.'],
            ],
            'steps' => [
                'Dupliquer ce dossier pour un nouveau projet.',
                'Modifier les contenus, couleurs et textes.',
                'Brancher ensuite vos vraies pages et traitements.',
            ],
        ];
    }
}
