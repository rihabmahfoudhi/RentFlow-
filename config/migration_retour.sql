-- ============================================================
-- Migration : Création de la table retour
-- Projet    : location_equipements (RentFlow)
-- Date      : 2026-08-18
-- ============================================================

CREATE TABLE IF NOT EXISTS retour (
    id_retour            INT          AUTO_INCREMENT PRIMARY KEY,
    location_id          INT          NOT NULL,
    date_retour          DATE         NOT NULL,
    etat_equipement      VARCHAR(50)  NOT NULL,
    jours_retard         INT          NOT NULL DEFAULT 0,
    frais_additionnels   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    statut               ENUM('En attente','Validé') NOT NULL DEFAULT 'En attente',
    date_enregistrement  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_retour_location
        FOREIGN KEY (location_id) REFERENCES location(id_location)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
