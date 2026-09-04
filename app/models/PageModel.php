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
                'not-found' => 'Page introuvable - RentFlow',
                default => 'RentFlow',
            },
            'pageTitle' => match ($currentRoute) {
                'not-found' => '404',
                default => 'Template de depart',
            },
            'pageSubtitle' => match ($currentRoute) {
                'not-found' => 'La page demandee est introuvable.',
                default => 'Une plateforme moderne de location d’équipements.',
            },
            'navHomeClass' => $currentRoute === 'home' ? 'active' : '',
        
        ];
    }
}
