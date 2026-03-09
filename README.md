# OrthoIntranet

Intranet de gestion de projet pour un cabinet d'orthodontie — développé avec **Laravel 12**, **Vue 3**, **Inertia.js** et **Tailwind CSS 4**.

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Vue 3, Inertia.js |
| Style | Tailwind CSS 4 (Vite 7) |
| Base de données | SQLite |
| Tests | PHPUnit (13 tests / 49 assertions) |

## Fonctionnalités

- **Authentification** — Login sécurisé, gestion de profil avec photo d'avatar
- **RBAC** — 3 rôles (admin, secrétaire, assistante) avec policies Laravel
- **Dashboard** — KPIs dynamiques, résumé par stream et par sprint
- **Backlog Kanban** — Drag & drop, cartes enrichies (priorité, persona, sprint, critères), filtres par stream
- **User Stories** — CRUD complet avec modal avancée : badges Fibonacci, pills priorité/statut, checklist critères d'acceptation
- **Streams** — 3 axes de travail avec codes couleur
- **Personas** — CRUD avec avatar coloré et tri personnalisé
- **Sprints / Roadmap** — Planification en sprints avec statuts (planifié, actif, terminé)
- **Recherche globale** — Recherche instantanée sur users, stories, personas et streams
- **Audit logs** — Journal d'audit complet de toutes les actions CRUD (utilisateur, IP, date)
- **Thème** — Light / Dark / Système (suivi OS) avec persistance localStorage
- **Admin** — Gestion utilisateurs, consultation des logs d'audit

## Utilisateurs seedés

| Nom | Email | Rôle | Mot de passe |
|---|---|---|---|
| Dr André Ziegler | andre@orthodz.fr | admin | password |
| Marie Dupont | marie@orthodz.fr | secrétaire | password |
| Sophie Martin | sophie@orthodz.fr | assistante | password |

## Installation

```bash
git clone https://github.com/dylanPerinetti/OrthoIntranet.git
cd OrthoIntranet
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
php artisan serve
```

## Tests

```bash
php artisan test
```

## Licence

Projet privé — Cabinet OrthoD'Z.
