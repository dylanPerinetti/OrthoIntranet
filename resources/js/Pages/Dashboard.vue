<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/60">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-200/50">
            <Stethoscope class="w-5 h-5 text-white" :stroke-width="2" />
          </div>
          <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">OrthoIntranet</h1>
            <p class="text-xs text-slate-400 -mt-0.5">Plateforme de Pilotage IA</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <span class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Environnement de présentation
          </span>
          <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white text-sm font-semibold">
            A
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10 space-y-12">

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 1 : Vision Stratégique                         -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="relative">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden">
          <!-- Decorative elements -->
          <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
          <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/3"></div>
          <div class="absolute top-1/2 right-1/4 w-2 h-2 bg-cyan-300 rounded-full animate-pulse"></div>
          <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5 bg-blue-300 rounded-full animate-pulse delay-500"></div>

          <div class="relative grid md:grid-cols-2 gap-8 items-center">
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium mb-6">
                <Sparkles class="w-4 h-4 text-cyan-300" :stroke-width="2" />
                Vision du Projet
              </div>

              <h2 class="text-3xl md:text-4xl font-bold leading-tight mb-4">
                Transformer le cabinet en
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-200">centre piloté par l'IA</span>
              </h2>

              <p class="text-blue-100 text-lg leading-relaxed mb-6">
                Réduire la charge mentale du Dr André et optimiser le chiffre d'affaires grâce à une digitalisation intelligente de chaque aspect du cabinet.
              </p>

              <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-sm">
                  <BrainCircuit class="w-4 h-4 text-cyan-300" :stroke-width="1.8" />
                  IA Intégrée
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-sm">
                  <Shield class="w-4 h-4 text-cyan-300" :stroke-width="1.8" />
                  Sécurité Médicale
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-sm">
                  <TrendingUp class="w-4 h-4 text-cyan-300" :stroke-width="1.8" />
                  ROI Mesurable
                </span>
              </div>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
                <Clock class="w-6 h-6 text-cyan-300 mb-2" :stroke-width="1.8" />
                <div class="text-2xl font-bold">-60%</div>
                <div class="text-blue-200 text-sm mt-1">Temps admin</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
                <Target class="w-6 h-6 text-cyan-300 mb-2" :stroke-width="1.8" />
                <div class="text-2xl font-bold">+35%</div>
                <div class="text-blue-200 text-sm mt-1">Taux fidélisation</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
                <Zap class="w-6 h-6 text-cyan-300 mb-2" :stroke-width="1.8" />
                <div class="text-2xl font-bold">800</div>
                <div class="text-blue-200 text-sm mt-1">Pages de cours IA</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
                <Users class="w-6 h-6 text-cyan-300 mb-2" :stroke-width="1.8" />
                <div class="text-2xl font-bold">3</div>
                <div class="text-blue-200 text-sm mt-1">Agents IA dédiés</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 2 : Les 3 Streams                              -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center">
            <Layers class="w-4 h-4 text-white" :stroke-width="2" />
          </div>
          <div>
            <h2 class="text-2xl font-bold text-slate-900">Streams d'Activités</h2>
            <p class="text-sm text-slate-500">Les 3 piliers de la transformation digitale</p>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <!-- Stream Opérationnel -->
          <StreamCard
            title="Opérationnel"
            subtitle="Agent Secrétariat IA"
            description="Automatisation des tâches quotidiennes du cabinet pour libérer du temps au personnel soignant et supprimer les goulots d'étranglement administratifs."
            :features="[
              'Secrétariat intelligent (rappels, confirmations)',
              'Gestion logistique & planning optimisé',
              'Suivi des stocks et commandes auto',
              'Tri et priorisation des urgences',
            ]"
            status="Priorité haute — Sprint 1"
            :icon="Settings"
            accentClass="bg-gradient-to-r from-emerald-500 to-teal-500"
            iconBgClass="bg-emerald-50"
            iconColorClass="text-emerald-600"
            dotBgClass="bg-emerald-50"
            dotColorClass="text-emerald-500"
            badgeClass="bg-emerald-50 text-emerald-700"
          />

          <!-- Stream Marketing -->
          <StreamCard
            title="Marketing"
            subtitle="Agent Croissance IA"
            description="Stratégie de fidélisation interne et acquisition externe automatisée pour développer le portefeuille patients de manière durable."
            :features="[
              'Fidélisation : anniversaires, relances mails',
              'Campagnes réseaux sociaux automatisées',
              'Développement réseau de praticiens',
              'Analyse de performance des canaux',
            ]"
            status="Sprint 2 — Planifié"
            :icon="Megaphone"
            accentClass="bg-gradient-to-r from-violet-500 to-purple-500"
            iconBgClass="bg-violet-50"
            iconColorClass="text-violet-600"
            dotBgClass="bg-violet-50"
            dotColorClass="text-violet-500"
            badgeClass="bg-violet-50 text-violet-700"
          />

          <!-- Stream Expertise Dr Z -->
          <StreamCard
            title="Expertise — Dr Z"
            subtitle="Agent Expert Orthodontie"
            description="Capitalisation sur 800 pages de cours d'orthodontie pour créer un assistant IA d'aide au diagnostic et au plan de traitement."
            :features="[
              'Base de connaissances orthodontiques IA',
              'Aide au plan de traitement personnalisé',
              'Consultation de la littérature en langage naturel',
              'Apprentissage continu par les cas cliniques',
            ]"
            status="Sprint 3 — Innovation"
            :icon="GraduationCap"
            accentClass="bg-gradient-to-r from-blue-500 to-cyan-500"
            iconBgClass="bg-blue-50"
            iconColorClass="text-blue-600"
            dotBgClass="bg-blue-50"
            dotColorClass="text-blue-500"
            badgeClass="bg-blue-50 text-blue-700"
          />
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 3 : Roadmap Agile                              -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section>
        <div class="grid lg:grid-cols-2 gap-8">
          <!-- Method explanation -->
          <div>
            <div class="flex items-center gap-3 mb-6">
              <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center">
                <Route class="w-4 h-4 text-white" :stroke-width="2" />
              </div>
              <div>
                <h2 class="text-2xl font-bold text-slate-900">Roadmap Agile</h2>
                <p class="text-sm text-slate-500">Notre méthode de travail itérative</p>
              </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
              <RoadmapTimeline :steps="roadmapSteps" />
            </div>
          </div>

          <!-- Engagement / How we work -->
          <div>
            <div class="flex items-center gap-3 mb-6">
              <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center">
                <Handshake class="w-4 h-4 text-white" :stroke-width="2" />
              </div>
              <div>
                <h2 class="text-2xl font-bold text-slate-900">Nos Engagements</h2>
                <p class="text-sm text-slate-500">Transparence et collaboration</p>
              </div>
            </div>

            <div class="space-y-4">
              <div
                v-for="(engagement, index) in engagements"
                :key="index"
                class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow"
              >
                <div :class="['w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0', engagement.bg]">
                  <component :is="engagement.icon" :class="['w-5 h-5', engagement.color]" :stroke-width="1.8" />
                </div>
                <div>
                  <h4 class="text-sm font-semibold text-slate-900">{{ engagement.title }}</h4>
                  <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ engagement.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 4 : Backlog Placeholder                        -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center">
            <ListTodo class="w-4 h-4 text-white" :stroke-width="2" />
          </div>
          <div>
            <h2 class="text-2xl font-bold text-slate-900">Prochainement</h2>
            <p class="text-sm text-slate-500">Le backlog collaboratif arrive bientôt</p>
          </div>
        </div>

        <BacklogPlaceholder />
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 5 : Architecture Technique (mini)              -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section>
        <div class="flex items-center gap-3 mb-8">
          <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center">
            <Server class="w-4 h-4 text-white" :stroke-width="2" />
          </div>
          <div>
            <h2 class="text-2xl font-bold text-slate-900">Architecture Technique</h2>
            <p class="text-sm text-slate-500">Stack moderne et évolutive</p>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div
              v-for="(tech, index) in techStack"
              :key="index"
              class="text-center p-4 rounded-xl border border-slate-100 hover:border-slate-200 hover:shadow-sm transition-all"
            >
              <div :class="['w-12 h-12 mx-auto rounded-xl flex items-center justify-center mb-3', tech.bg]">
                <component :is="tech.icon" :class="['w-6 h-6', tech.color]" :stroke-width="1.8" />
              </div>
              <h4 class="text-sm font-semibold text-slate-900">{{ tech.name }}</h4>
              <p class="text-xs text-slate-500 mt-1">{{ tech.role }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Footer -->
      <footer class="pt-8 pb-4 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-4">
        <StatusBar />
        <p class="text-xs text-slate-400">
          Conçu avec <span class="text-red-400">&hearts;</span> pour le Dr André — Projet OrthoIntranet
        </p>
      </footer>

    </main>
  </div>
</template>

<script setup>
import {
  Stethoscope,
  Sparkles,
  BrainCircuit,
  Shield,
  TrendingUp,
  Clock,
  Target,
  Zap,
  Users,
  Layers,
  Settings,
  Megaphone,
  GraduationCap,
  Route,
  Handshake,
  ListTodo,
  Server,
  Eye,
  MessageSquare,
  RefreshCcw,
  Lock,
  Code,
  Globe,
  Database,
  Bot,
  UserCircle,
  BookOpen,
  Rocket,
  ClipboardCheck,
} from 'lucide-vue-next';

import StreamCard from '@/Components/StreamCard.vue';
import RoadmapTimeline from '@/Components/RoadmapTimeline.vue';
import BacklogPlaceholder from '@/Components/BacklogPlaceholder.vue';
import StatusBar from '@/Components/StatusBar.vue';

const roadmapSteps = [
  {
    title: 'Définition des Personas',
    description: 'Identifier les utilisateurs clés : Secrétaire, Assistante dentaire, Patient, Dr André. Comprendre leurs besoins et irritants quotidiens.',
    icon: UserCircle,
    tag: 'Phase Découverte',
    active: true,
  },
  {
    title: 'Rédaction des User Stories',
    description: 'Formuler les besoins sous forme de "En tant que [persona], je veux [action] afin de [bénéfice]" pour chaque stream.',
    icon: BookOpen,
    tag: 'Phase Cadrage',
    active: true,
  },
  {
    title: 'Développement par Sprints',
    description: 'Cycles de 2 semaines : développement, tests, livraison incrémentale. Chaque sprint livre de la valeur visible.',
    icon: Rocket,
    tag: 'Phase Build',
    active: false,
  },
  {
    title: 'Validation sur l\'Intranet',
    description: 'Le Dr André valide chaque fonctionnalité directement sur cette plateforme. Feedback en temps réel, ajustements rapides.',
    icon: ClipboardCheck,
    tag: 'Phase Review',
    active: false,
  },
];

const engagements = [
  {
    icon: Eye,
    title: 'Transparence totale',
    description: 'Suivi en temps réel de l\'avancement sur cet Intranet. Vous voyez exactement où nous en sommes à tout moment.',
    bg: 'bg-blue-50',
    color: 'text-blue-600',
  },
  {
    icon: MessageSquare,
    title: 'Communication continue',
    description: 'Notifications en direct (Laravel Reverb). Aucun email perdu, aucune mise à jour manquée.',
    bg: 'bg-violet-50',
    color: 'text-violet-600',
  },
  {
    icon: RefreshCcw,
    title: 'Itérations rapides',
    description: 'Sprints de 2 semaines avec démo. Si quelque chose ne convient pas, on ajuste immédiatement.',
    bg: 'bg-emerald-50',
    color: 'text-emerald-600',
  },
  {
    icon: Lock,
    title: 'Sécurité des données patient',
    description: 'Conformité RGPD, hébergement HDS (Hébergeur de Données de Santé), chiffrement bout en bout.',
    bg: 'bg-amber-50',
    color: 'text-amber-600',
  },
];

const techStack = [
  { name: 'Laravel 12', role: 'Backend & API', icon: Code, bg: 'bg-red-50', color: 'text-red-600' },
  { name: 'Vue.js 3', role: 'Interface SPA', icon: Globe, bg: 'bg-emerald-50', color: 'text-emerald-600' },
  { name: 'SQLite / PostgreSQL', role: 'Base de données', icon: Database, bg: 'bg-blue-50', color: 'text-blue-600' },
  { name: 'Agents IA', role: 'Claude / OpenAI', icon: Bot, bg: 'bg-violet-50', color: 'text-violet-600' },
];
</script>
