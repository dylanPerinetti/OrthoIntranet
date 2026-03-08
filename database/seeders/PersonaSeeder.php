<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\UserStory;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        $drAndre = Persona::firstOrCreate(
            ['name' => 'Dr André'],
            [
                'role'         => 'Orthodontiste — Directeur du cabinet',
                'description'  => 'Praticien expérimenté avec 20 ans d\'expérience en orthodontie. Propriétaire du cabinet, il souhaite moderniser ses processus tout en capitalisant sur son expertise.',
                'goals'        => "Réduire la charge administrative de 60%\nPiloter l'activité avec des KPIs clairs\nCapitaliser sur ses 800 pages de cours",
                'pain_points'  => "Trop de temps passé sur l'administratif\nDifficulté à retrouver ses notes de cours\nManque de visibilité sur les performances du cabinet",
                'needs'        => "Dashboard de pilotage temps réel\nAssistant IA pour consulter ses cours\nAutomatisation des tâches répétitives",
                'avatar_color' => '#3b82f6',
                'sort_order'   => 1,
            ]
        );

        $secretaire = Persona::firstOrCreate(
            ['name' => 'Marie (Secrétaire)'],
            [
                'role'         => 'Secrétaire médicale',
                'description'  => 'Secrétaire du cabinet gérant l\'accueil, les rendez-vous et la relation patients. Première interlocutrice des patients.',
                'goals'        => "Gérer efficacement les rendez-vous\nRéduire les no-shows\nAméliorer la communication patient",
                'pain_points'  => "Trop d'appels à gérer simultanément\nRappels manuels chronophages\nDifficulté à prioriser les urgences",
                'needs'        => "Système de rappels automatiques (SMS/email)\nTri intelligent des appels\nPlanning optimisé",
                'avatar_color' => '#10b981',
                'sort_order'   => 2,
            ]
        );

        $assistante = Persona::firstOrCreate(
            ['name' => 'Sophie (Assistante)'],
            [
                'role'         => 'Assistante dentaire',
                'description'  => 'Assistante auprès du Dr André au fauteuil. Gère aussi une partie de la logistique (stocks, commandes).',
                'goals'        => "Préparer efficacement chaque consultation\nGérer les stocks sans rupture\nFidéliser les patients",
                'pain_points'  => "Inventaire manuel des stocks\nOublis de commandes\nPeu de temps pour les tâches relationnelles",
                'needs'        => "Gestion des stocks automatisée\nAlerte de réapprovisionnement\nOutils de fidélisation patient",
                'avatar_color' => '#8b5cf6',
                'sort_order'   => 3,
            ]
        );

        $patient = Persona::firstOrCreate(
            ['name' => 'Patient'],
            [
                'role'         => 'Patient du cabinet',
                'description'  => 'Patient adulte ou parent d\'enfant suivant un traitement orthodontique au cabinet.',
                'goals'        => "Être bien informé sur son traitement\nNe pas oublier ses rendez-vous\nSe sentir pris en charge",
                'pain_points'  => "Oubli des rendez-vous\nManque d'informations entre consultations\nTemps d'attente au cabinet",
                'needs'        => "Rappels automatiques\nPortail patient informatif\nCommunication fluide avec le cabinet",
                'avatar_color' => '#f59e0b',
                'sort_order'   => 4,
            ]
        );

        // Associer les personas aux user stories existantes (basé sur le titre)
        UserStory::where('title', 'LIKE', '%Secrétaire%')
            ->whereNull('persona_id')
            ->update(['persona_id' => $secretaire->id]);

        UserStory::where('title', 'LIKE', '%Dr André%')
            ->whereNull('persona_id')
            ->update(['persona_id' => $drAndre->id]);

        UserStory::where('title', 'LIKE', '%Assistante%')
            ->whereNull('persona_id')
            ->update(['persona_id' => $assistante->id]);
    }
}
