<?php

namespace Database\Seeders;

use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Database\Seeder;

class UserStorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (! $admin) {
            $admin = User::first();
        }
        if (! $admin) {
            return;
        }

        $operationnel = Stream::where('slug', 'operationnel')->first();
        $marketing    = Stream::where('slug', 'marketing')->first();
        $expertise    = Stream::where('slug', 'expertise-dr-z')->first();

        if (! $operationnel || ! $marketing || ! $expertise) {
            return;
        }

        $stories = [
            [
                'stream_id'           => $operationnel->id,
                'title'               => 'En tant que Secrétaire, je veux un système de rappels SMS automatiques afin de réduire les rendez-vous manqués',
                'description'         => 'Mise en place d\'un module d\'envoi de SMS automatiques 24h et 2h avant chaque rendez-vous patient. Intégration avec le planning du cabinet.',
                'acceptance_criteria'  => "- Le SMS est envoyé 24h avant le RDV\n- Le SMS est envoyé 2h avant le RDV\n- Le patient peut confirmer/annuler par réponse SMS\n- Le taux de no-show est mesuré",
                'status'              => 'in_progress',
                'priority'            => 'high',
                'story_points'        => 8,
                'sort_order'          => 1,
            ],
            [
                'stream_id'           => $operationnel->id,
                'title'               => 'En tant que Dr André, je veux un dashboard des KPIs quotidiens afin de piloter l\'activité du cabinet',
                'description'         => 'Tableau de bord affichant les métriques clés : nombre de patients du jour, CA prévisionnel, taux de remplissage du planning.',
                'acceptance_criteria'  => "- Affichage des RDV du jour\n- KPI CA prévisionnel\n- Taux de remplissage planning\n- Comparaison semaine précédente",
                'status'              => 'todo',
                'priority'            => 'high',
                'story_points'        => 5,
                'sort_order'          => 2,
            ],
            [
                'stream_id'           => $marketing->id,
                'title'               => 'En tant qu\'Assistante, je veux envoyer des e-mails d\'anniversaire automatiques afin de fidéliser les patients',
                'description'         => 'Envoi automatique d\'un e-mail personnalisé pour chaque anniversaire patient avec un message du Dr André et une offre de contrôle gratuit.',
                'acceptance_criteria'  => "- E-mail envoyé le jour de l'anniversaire\n- Template personnalisé avec le prénom\n- Tracking des ouvertures\n- Rapport mensuel",
                'status'              => 'todo',
                'priority'            => 'medium',
                'story_points'        => 3,
                'sort_order'          => 1,
            ],
            [
                'stream_id'           => $expertise->id,
                'title'               => 'En tant que Dr André, je veux interroger ma base de cours en langage naturel afin de retrouver rapidement une technique orthodontique',
                'description'         => 'Interface de chat IA connectée aux 800 pages de cours d\'orthodontie. Le Dr André peut poser une question et recevoir une réponse sourcée.',
                'acceptance_criteria'  => "- Indexation des 800 pages de cours\n- Interface chat intuitive\n- Réponses avec sources et pages\n- Temps de réponse < 5 secondes",
                'status'              => 'todo',
                'priority'            => 'medium',
                'story_points'        => 13,
                'sort_order'          => 1,
            ],
            [
                'stream_id'           => $operationnel->id,
                'title'               => 'En tant que Secrétaire, je veux un tri automatique des urgences afin de prioriser les appels importants',
                'description'         => 'Système de classification IA des appels entrants par niveau d\'urgence (critique, normal, faible) avec notification push au Dr André pour les urgences.',
                'acceptance_criteria'  => "- Classification en 3 niveaux d'urgence\n- Notification push pour les urgences critiques\n- Historique des appels classifiés\n- Ajustement manuel possible",
                'status'              => 'done',
                'priority'            => 'high',
                'story_points'        => 5,
                'sort_order'          => 1,
            ],
        ];

        foreach ($stories as $story) {
            UserStory::firstOrCreate(
                ['title' => $story['title']],
                array_merge($story, ['created_by' => $admin->id])
            );
        }
    }
}
