<template>
  <AuthenticatedLayout>
    <div class="space-y-8">

      <!-- ═══════════════════════════════════════ -->
      <!-- PAGE HEADER                              -->
      <!-- ═══════════════════════════════════════ -->
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Vue d'ensemble</h1>
        <p class="text-sm text-gray-500 mt-1">Tableau de bord du projet OrthoIntranet</p>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- KPI CARDS                                -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="(kpi, i) in kpis"
          :key="i"
          class="bg-white rounded-xl border border-gray-200/80 p-4 sm:p-5 hover:shadow-sm transition-shadow"
        >
          <div class="flex items-center justify-between mb-3">
            <div :class="['w-9 h-9 rounded-lg flex items-center justify-center', kpi.bg]">
              <component :is="kpi.icon" :class="['w-[18px] h-[18px]', kpi.color]" :stroke-width="1.8" />
            </div>
            <span
              v-if="kpi.trend"
              :class="[
                'text-[11px] font-semibold px-1.5 py-0.5 rounded',
                kpi.trendUp ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 bg-gray-50',
              ]"
            >
              {{ kpi.trend }}
            </span>
          </div>
          <div class="text-2xl font-bold text-gray-900">{{ kpi.value }}</div>
          <div class="text-xs text-gray-500 mt-0.5">{{ kpi.label }}</div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!--  VISION + BACKLOG STATS ROW              -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid lg:grid-cols-3 gap-6">
        <!-- Vision card (2 cols) -->
        <div class="lg:col-span-2 bg-[#1a1f36] rounded-xl p-6 sm:p-8 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
          <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/3"></div>

          <div class="relative">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/10 rounded-md text-xs font-medium text-blue-300 mb-4">
              <Sparkles class="w-3.5 h-3.5" :stroke-width="2" />
              Vision du Projet
            </span>
            <h2 class="text-xl sm:text-2xl font-bold leading-tight mb-3">
              Transformer le cabinet en<br class="hidden sm:block" />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">centre piloté par l'IA</span>
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed max-w-lg mb-5">
              Réduire la charge mentale du Dr André et optimiser le chiffre d'affaires grâce à une digitalisation intelligente de chaque aspect du cabinet.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white/[0.06] rounded-md text-xs text-gray-300">
                <BrainCircuit class="w-3.5 h-3.5 text-blue-400" :stroke-width="1.8" />
                IA Intégrée
              </span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white/[0.06] rounded-md text-xs text-gray-300">
                <Shield class="w-3.5 h-3.5 text-blue-400" :stroke-width="1.8" />
                Sécurité Médicale
              </span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white/[0.06] rounded-md text-xs text-gray-300">
                <TrendingUp class="w-3.5 h-3.5 text-blue-400" :stroke-width="1.8" />
                ROI Mesurable
              </span>
            </div>
          </div>
        </div>

        <!-- Backlog stats (1 col) -->
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 sm:p-6 flex flex-col">
          <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-semibold text-gray-900">Progression Backlog</h3>
            <a
              href="/backlog"
              class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors"
              @click.prevent="$inertia.visit('/backlog')"
            >
              Voir tout →
            </a>
          </div>

          <!-- Progress bar -->
          <div class="mb-5">
            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
              <span>Avancement global</span>
              <span class="font-semibold text-gray-700">{{ progressPercent }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
              <div
                class="h-full bg-gradient-to-r from-blue-500 to-emerald-500 rounded-full transition-all duration-500"
                :style="{ width: progressPercent + '%' }"
              ></div>
            </div>
          </div>

          <!-- Status breakdown -->
          <div class="space-y-3 flex-1">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span>
                <span class="text-sm text-gray-600">À faire</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ storyCounts.todo }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-blue-500"></span>
                <span class="text-sm text-gray-600">En cours</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ storyCounts.in_progress }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                <span class="text-sm text-gray-600">Terminé</span>
              </div>
              <span class="text-sm font-semibold text-gray-900">{{ storyCounts.done }}</span>
            </div>
          </div>

          <button
            class="mt-5 w-full py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors flex items-center justify-center gap-2"
            @click="$inertia.visit('/backlog')"
          >
            <Kanban class="w-4 h-4" :stroke-width="2" />
            Ouvrir le Kanban
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- STREAMS SECTION                          -->
      <!-- ═══════════════════════════════════════ -->
      <div>
        <div class="flex items-center justify-between mb-5">
          <div>
            <h2 class="text-lg font-bold text-gray-900">Axes d'Activités</h2>
            <p class="text-sm text-gray-500">Les 3 piliers de la transformation digitale</p>
          </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
          <StreamCard
            v-for="stream in streamCards"
            :key="stream.slug"
            v-bind="stream"
          />
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- ROADMAP + ENGAGEMENTS                    -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid lg:grid-cols-2 gap-6">
        <!-- Roadmap -->
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 sm:p-6">
          <div class="flex items-center gap-2 mb-5">
            <Route class="w-4 h-4 text-gray-400" :stroke-width="2" />
            <h3 class="text-sm font-semibold text-gray-900">Roadmap Agile</h3>
          </div>
          <RoadmapTimeline :steps="roadmapSteps" />
        </div>

        <!-- Engagements -->
        <div class="bg-white rounded-xl border border-gray-200/80 p-5 sm:p-6">
          <div class="flex items-center gap-2 mb-5">
            <Handshake class="w-4 h-4 text-gray-400" :stroke-width="2" />
            <h3 class="text-sm font-semibold text-gray-900">Nos Engagements</h3>
          </div>
          <div class="space-y-3">
            <div
              v-for="(eng, i) in engagements"
              :key="i"
              class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div :class="['w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0', eng.bg]">
                <component :is="eng.icon" :class="['w-4 h-4', eng.color]" :stroke-width="1.8" />
              </div>
              <div class="min-w-0">
                <h4 class="text-sm font-medium text-gray-900">{{ eng.title }}</h4>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ eng.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- TECH STACK                               -->
      <!-- ═══════════════════════════════════════ -->
      <div class="bg-white rounded-xl border border-gray-200/80 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-5">
          <Server class="w-4 h-4 text-gray-400" :stroke-width="2" />
          <h3 class="text-sm font-semibold text-gray-900">Stack Technique</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
          <div
            v-for="(tech, i) in techStack"
            :key="i"
            class="text-center p-4 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50/50 transition-all"
          >
            <div :class="['w-10 h-10 mx-auto rounded-lg flex items-center justify-center mb-2', tech.bg]">
              <component :is="tech.icon" :class="['w-5 h-5', tech.color]" :stroke-width="1.8" />
            </div>
            <h4 class="text-sm font-medium text-gray-900">{{ tech.name }}</h4>
            <p class="text-[11px] text-gray-500 mt-0.5">{{ tech.role }}</p>
          </div>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import {
  Sparkles, BrainCircuit, Shield, TrendingUp,
  Clock, Target, Zap, Users,
  Settings, Megaphone, GraduationCap,
  Route, Handshake, Server, Kanban,
  Eye, MessageSquare, RefreshCcw, Lock,
  Code, Globe, Database, Bot,
  UserCircle, BookOpen, Rocket, ClipboardCheck,
} from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StreamCard from '@/Components/StreamCard.vue';
import RoadmapTimeline from '@/Components/RoadmapTimeline.vue';

const props = defineProps({
  streams: Array,
  storyCounts: Object,
});

const totalStories = computed(() => {
  const c = props.storyCounts || { todo: 0, in_progress: 0, done: 0 };
  return c.todo + c.in_progress + c.done;
});

const progressPercent = computed(() => {
  if (!totalStories.value) return 0;
  return Math.round(((props.storyCounts?.done || 0) / totalStories.value) * 100);
});

const kpis = [
  { value: '-60%', label: 'Temps admin', icon: Clock, bg: 'bg-blue-50', color: 'text-blue-600', trend: 'Objectif', trendUp: true },
  { value: '+35%', label: 'Taux fidélisation', icon: Target, bg: 'bg-emerald-50', color: 'text-emerald-600', trend: 'Objectif', trendUp: true },
  { value: '800', label: 'Pages de cours IA', icon: Zap, bg: 'bg-amber-50', color: 'text-amber-600' },
  { value: '3', label: 'Agents IA dédiés', icon: Users, bg: 'bg-violet-50', color: 'text-violet-600' },
];

const streamCards = [
  {
    slug: 'operationnel',
    title: 'Opérationnel',
    subtitle: 'Agent Secrétariat IA',
    description: 'Automatisation des tâches quotidiennes pour libérer du temps au personnel soignant.',
    features: [
      'Secrétariat intelligent (rappels, confirmations)',
      'Gestion logistique & planning optimisé',
      'Suivi des stocks et commandes auto',
      'Tri et priorisation des urgences',
    ],
    status: 'Sprint 1 — Actif',
    icon: Settings,
    accentColor: '#10b981',
  },
  {
    slug: 'marketing',
    title: 'Marketing',
    subtitle: 'Agent Croissance IA',
    description: 'Stratégie de fidélisation et acquisition pour développer le portefeuille patients.',
    features: [
      'Fidélisation : anniversaires, relances',
      'Campagnes réseaux sociaux auto',
      'Développement réseau praticiens',
      'Analyse performance des canaux',
    ],
    status: 'Sprint 2 — Planifié',
    icon: Megaphone,
    accentColor: '#8b5cf6',
  },
  {
    slug: 'expertise-dr-z',
    title: 'Expertise — Dr Z',
    subtitle: 'Agent Expert Orthodontie',
    description: 'Capitalisation sur 800 pages de cours pour un assistant IA d\'aide au diagnostic.',
    features: [
      'Base de connaissances IA',
      'Aide au plan de traitement',
      'Consultation littérature en langage naturel',
      'Apprentissage par cas cliniques',
    ],
    status: 'Sprint 3 — Innovation',
    icon: GraduationCap,
    accentColor: '#3b82f6',
  },
];

const roadmapSteps = [
  { title: 'Définition des Personas', description: 'Identifier les utilisateurs clés et comprendre leurs besoins.', icon: UserCircle, tag: 'Découverte', active: true },
  { title: 'Rédaction des User Stories', description: 'Formuler les besoins sous forme "En tant que…"', icon: BookOpen, tag: 'Cadrage', active: true },
  { title: 'Développement par Sprints', description: 'Cycles de 2 semaines avec livraison incrémentale.', icon: Rocket, tag: 'Build', active: false },
  { title: 'Validation sur l\'Intranet', description: 'Feedback en temps réel, ajustements rapides.', icon: ClipboardCheck, tag: 'Review', active: false },
];

const engagements = [
  { icon: Eye, title: 'Transparence totale', description: 'Suivi en temps réel de l\'avancement sur cet Intranet.', bg: 'bg-blue-50', color: 'text-blue-600' },
  { icon: MessageSquare, title: 'Communication continue', description: 'Notifications en direct, aucune mise à jour manquée.', bg: 'bg-violet-50', color: 'text-violet-600' },
  { icon: RefreshCcw, title: 'Itérations rapides', description: 'Sprints de 2 semaines avec démo. Ajustement immédiat.', bg: 'bg-emerald-50', color: 'text-emerald-600' },
  { icon: Lock, title: 'Sécurité données patient', description: 'Conformité RGPD, hébergement HDS, chiffrement E2E.', bg: 'bg-amber-50', color: 'text-amber-600' },
];

const techStack = [
  { name: 'Laravel 12', role: 'Backend & API', icon: Code, bg: 'bg-red-50', color: 'text-red-600' },
  { name: 'Vue.js 3', role: 'Interface SPA', icon: Globe, bg: 'bg-emerald-50', color: 'text-emerald-600' },
  { name: 'SQLite / PgSQL', role: 'Base de données', icon: Database, bg: 'bg-blue-50', color: 'text-blue-600' },
  { name: 'Agents IA', role: 'Claude / OpenAI', icon: Bot, bg: 'bg-violet-50', color: 'text-violet-600' },
];
</script>
