<template>
  <AuthenticatedLayout>
    <div class="space-y-8">

      <!-- ═══════════════════════════════════════ -->
      <!-- PAGE HEADER                              -->
      <!-- ═══════════════════════════════════════ -->
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Vue d'ensemble</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tableau de bord du projet OrthoIntranet</p>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- KPI CARDS (dynamiques)                   -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          v-for="(kpi, i) in kpis"
          :key="i"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5 hover:shadow-sm transition-shadow"
        >
          <div class="flex items-center justify-between mb-3">
            <div :class="['w-9 h-9 rounded-lg flex items-center justify-center', kpi.bg]">
              <component :is="kpi.icon" :class="['w-[18px] h-[18px]', kpi.color]" :stroke-width="1.8" />
            </div>
            <span
              v-if="kpi.badge"
              :class="[
                'text-[11px] font-semibold px-1.5 py-0.5 rounded',
                kpi.badgeClass,
              ]"
            >
              {{ kpi.badge }}
            </span>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ kpi.value }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ kpi.label }}</div>
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
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6 flex flex-col">
          <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Progression Backlog</h3>
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
              <span class="font-semibold text-gray-700 dark:text-gray-300">{{ progressPercent }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
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
                <span class="text-sm text-gray-600 dark:text-gray-400">À faire</span>
              </div>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ storyCounts.todo }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-blue-500"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">En cours</span>
              </div>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ storyCounts.in_progress }}</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Terminé</span>
              </div>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ storyCounts.done }}</span>
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
      <!-- STREAMS SECTION (dynamique depuis DB)    -->
      <!-- ═══════════════════════════════════════ -->
      <div>
        <div class="flex items-center justify-between mb-5">
          <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Axes d'Activités</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ streams.length }} axes de transformation digitale</p>
          </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
          <a
            v-for="stream in streams"
            :key="stream.slug"
            :href="`/streams/${stream.slug}`"
            class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden hover:shadow-md hover:border-gray-300/80 dark:hover:border-gray-600/80 transition-all duration-200 no-underline"
            @click.prevent="$inertia.visit(`/streams/${stream.slug}`)"
          >
            <!-- Top accent bar -->
            <div class="h-1 w-full" :style="{ backgroundColor: stream.color }"></div>

            <div class="p-5">
              <!-- Header -->
              <div class="flex items-start gap-3.5 mb-3">
                <div
                  class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                  :style="{ backgroundColor: stream.color + '14' }"
                >
                  <component
                    :is="iconMap[stream.icon] || Settings"
                    class="w-5 h-5"
                    :style="{ color: stream.color }"
                    :stroke-width="1.8"
                  />
                </div>
                <div class="min-w-0">
                  <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ stream.name }}</h3>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ stream.user_stories_count }} user stories</p>
                </div>
              </div>

              <!-- Description -->
              <p class="text-[13px] text-gray-600 dark:text-gray-400 leading-relaxed mb-4">{{ stream.description }}</p>

              <!-- Per-stream progress -->
              <div class="mb-3">
                <div class="flex items-center justify-between text-[11px] text-gray-500 mb-1.5">
                  <span>Avancement</span>
                  <span class="font-semibold text-gray-700 dark:text-gray-300">{{ streamProgress(stream) }}%</span>
                </div>
                <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all duration-500"
                    :style="{ width: streamProgress(stream) + '%', backgroundColor: stream.color }"
                  ></div>
                </div>
              </div>

              <!-- Status breakdown mini -->
              <div class="flex items-center gap-3 text-[11px] text-gray-500">
                <span class="flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                  {{ stream.stories_todo_count }} à faire
                </span>
                <span class="flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  {{ stream.stories_in_progress_count }} en cours
                </span>
                <span class="flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  {{ stream.stories_done_count }} terminé
                </span>
              </div>
            </div>
          </a>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- ACTIVITÉ RÉCENTE                         -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Stories -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <Clock class="w-4 h-4 text-gray-400" :stroke-width="2" />
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Activité récente</h3>
            </div>
            <a
              href="/user-stories"
              class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors"
              @click.prevent="$inertia.visit('/user-stories')"
            >
              Tout voir →
            </a>
          </div>

          <div v-if="recentStories.length" class="space-y-3">
            <div
              v-for="story in recentStories"
              :key="story.id"
              class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <!-- Status dot -->
              <div class="flex-shrink-0 mt-1">
                <span
                  :class="[
                    'block w-2.5 h-2.5 rounded-full',
                    story.status === 'done' ? 'bg-emerald-500' :
                    story.status === 'in_progress' ? 'bg-blue-500' : 'bg-gray-300',
                  ]"
                ></span>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ story.title }}</h4>
                <div class="flex items-center gap-2 mt-1">
                  <span
                    v-if="story.stream"
                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium"
                    :style="{
                      backgroundColor: story.stream.color + '14',
                      color: story.stream.color,
                    }"
                  >
                    {{ story.stream.name }}
                  </span>
                  <span
                    :class="[
                      'px-1.5 py-0.5 rounded text-[10px] font-medium',
                      story.priority === 'high' ? 'bg-red-50 text-red-600' :
                      story.priority === 'medium' ? 'bg-amber-50 text-amber-600' :
                      'bg-gray-50 text-gray-500',
                    ]"
                  >
                    {{ { high: 'Haute', medium: 'Moyenne', low: 'Basse' }[story.priority] || story.priority }}
                  </span>
                  <span v-if="story.assignee" class="text-[10px] text-gray-400">
                    → {{ story.assignee.name }}
                  </span>
                </div>
              </div>
              <span class="flex-shrink-0 text-[10px] text-gray-400 mt-0.5">
                {{ formatDate(story.created_at) }}
              </span>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <BookOpen class="w-8 h-8 text-gray-300 mx-auto mb-2" :stroke-width="1.5" />
            <p class="text-sm text-gray-400">Aucune user story pour le moment</p>
          </div>
        </div>

        <!-- Roadmap (dynamique depuis sprints DB) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <Route class="w-4 h-4 text-gray-400" :stroke-width="2" />
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Roadmap Agile</h3>
            </div>
            <a
              href="/sprints"
              class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors"
              @click.prevent="$inertia.visit('/sprints')"
            >
              Voir tout →
            </a>
          </div>

          <div v-if="sprints?.length" class="relative">
            <!-- Vertical line -->
            <div class="absolute left-[18px] top-0 bottom-0 w-px bg-gradient-to-b from-blue-300 via-blue-400 to-indigo-500"></div>

            <div class="space-y-6">
              <div
                v-for="(sprint, index) in sprints"
                :key="sprint.id"
                class="relative flex items-start gap-4 pl-1"
              >
                <!-- Circle -->
                <div
                  :class="[
                    'relative z-10 flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold border-2',
                    sprint.status === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white' :
                    sprint.status === 'active'    ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200' :
                                                    'bg-white border-gray-300 text-gray-500',
                  ]"
                >
                  <CheckCircle2 v-if="sprint.status === 'completed'" class="w-4 h-4" :stroke-width="2.5" />
                  <template v-else>S{{ sprint.number }}</template>
                </div>

                <!-- Content -->
                <div class="flex-1 pb-1">
                  <div class="flex items-center gap-2 mb-0.5">
                    <h4 :class="['text-sm font-semibold', sprint.status === 'active' ? 'text-gray-900' : 'text-gray-600']">
                      {{ sprint.name }}
                    </h4>
                    <span
                      :class="[
                        'px-1.5 py-0.5 rounded text-[10px] font-medium',
                        sprint.status === 'completed' ? 'bg-emerald-50 text-emerald-700' :
                        sprint.status === 'active'    ? 'bg-blue-50 text-blue-700' :
                                                        'bg-gray-100 text-gray-500',
                      ]"
                    >
                      {{ { planned: 'Planifié', active: 'Actif', completed: 'Terminé' }[sprint.status] }}
                    </span>
                  </div>
                  <p v-if="sprint.goal" class="text-xs text-gray-500 leading-relaxed mb-1.5">{{ sprint.goal }}</p>
                  <div class="flex items-center gap-3 text-[11px] text-gray-400">
                    <span>{{ formatSprintDate(sprint.start_date) }} → {{ formatSprintDate(sprint.end_date) }}</span>
                    <span v-if="sprint.user_stories_count">· {{ sprint.stories_done_count }}/{{ sprint.user_stories_count }} stories</span>
                  </div>
                  <!-- Mini progress -->
                  <div v-if="sprint.user_stories_count" class="mt-2 w-full max-w-[200px] h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div
                      :class="[
                        'h-full rounded-full',
                        sprint.status === 'completed' ? 'bg-emerald-500' :
                        sprint.status === 'active'    ? 'bg-blue-500' : 'bg-gray-300',
                      ]"
                      :style="{ width: Math.round(((sprint.stories_done_count || 0) / sprint.user_stories_count) * 100) + '%' }"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6">
            <Milestone class="w-8 h-8 text-gray-300 mx-auto mb-2" :stroke-width="1.5" />
            <p class="text-sm text-gray-400">Aucun sprint planifié</p>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- ENGAGEMENTS + TECH STACK                 -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid lg:grid-cols-2 gap-6">
        <!-- Engagements -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6">
          <div class="flex items-center gap-2 mb-5">
            <Handshake class="w-4 h-4 text-gray-400" :stroke-width="2" />
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Nos Engagements</h3>
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
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ eng.title }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ eng.description }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tech Stack -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6">
          <div class="flex items-center gap-2 mb-5">
            <Server class="w-4 h-4 text-gray-400" :stroke-width="2" />
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Stack Technique</h3>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div
              v-for="(tech, i) in techStack"
              :key="i"
              class="text-center p-4 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-all"
            >
              <div :class="['w-10 h-10 mx-auto rounded-lg flex items-center justify-center mb-2', tech.bg]">
                <component :is="tech.icon" :class="['w-5 h-5', tech.color]" :stroke-width="1.8" />
              </div>
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ tech.name }}</h4>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ tech.role }}</p>
            </div>
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
  BookOpen, Milestone,
  ListChecks, CheckCircle2, BarChart3,
} from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  streams: Array,
  storyCounts: Object,
  recentStories: Array,
  sprints: Array,
  teamCount: Number,
  personaCount: Number,
});

/* ── Icon mapping for DB icon strings ── */
const iconMap = {
  Settings,
  Megaphone,
  GraduationCap,
};

/* ── Computed ── */

const totalStories = computed(() => {
  const c = props.storyCounts || { todo: 0, in_progress: 0, done: 0 };
  return c.todo + c.in_progress + c.done;
});

const progressPercent = computed(() => {
  if (!totalStories.value) return 0;
  return Math.round(((props.storyCounts?.done || 0) / totalStories.value) * 100);
});

const kpis = computed(() => [
  {
    value: totalStories.value,
    label: 'User Stories',
    icon: ListChecks,
    bg: 'bg-blue-50',
    color: 'text-blue-600',
    badge: `${props.streams?.length || 0} axes`,
    badgeClass: 'text-blue-700 bg-blue-50',
  },
  {
    value: props.storyCounts?.done || 0,
    label: 'Terminées',
    icon: CheckCircle2,
    bg: 'bg-emerald-50',
    color: 'text-emerald-600',
    badge: progressPercent.value + '%',
    badgeClass: 'text-emerald-700 bg-emerald-50',
  },
  {
    value: props.storyCounts?.in_progress || 0,
    label: 'En cours',
    icon: Zap,
    bg: 'bg-amber-50',
    color: 'text-amber-600',
  },
  {
    value: props.teamCount || 0,
    label: 'Membres équipe',
    icon: Users,
    bg: 'bg-violet-50',
    color: 'text-violet-600',
    badge: `${props.personaCount || 0} personas`,
    badgeClass: 'text-violet-700 bg-violet-50',
  },
]);

function streamProgress(stream) {
  const total = stream.user_stories_count || 0;
  if (!total) return 0;
  return Math.round(((stream.stories_done_count || 0) / total) * 100);
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  const now = new Date();
  const diffMs = now - d;
  const diffMin = Math.floor(diffMs / 60000);
  if (diffMin < 60) return `il y a ${diffMin}min`;
  const diffH = Math.floor(diffMin / 60);
  if (diffH < 24) return `il y a ${diffH}h`;
  const diffD = Math.floor(diffH / 24);
  if (diffD < 7) return `il y a ${diffD}j`;
  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}

function formatSprintDate(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr);
  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}

/* ── Static data (Engagements, Tech) ── */

const engagements = [
  { icon: Eye, title: 'Transparence totale', description: 'Suivi en temps réel de l\'avancement sur cet Intranet.', bg: 'bg-blue-50', color: 'text-blue-600' },
  { icon: MessageSquare, title: 'Communication continue', description: 'Notifications en direct, aucune mise à jour manquée.', bg: 'bg-violet-50', color: 'text-violet-600' },
  { icon: RefreshCcw, title: 'Itérations rapides', description: 'Sprints de 2 semaines avec démo. Ajustement immédiat.', bg: 'bg-emerald-50', color: 'text-emerald-600' },
  { icon: Lock, title: 'Sécurité données patient', description: 'Conformité RGPD, hébergement HDS, chiffrement E2E.', bg: 'bg-amber-50', color: 'text-amber-600' },
];

const techStack = [
  { name: 'Laravel 12', role: 'Backend & API', icon: Code, bg: 'bg-red-50', color: 'text-red-600' },
  { name: 'Vue.js 3', role: 'Interface SPA', icon: Globe, bg: 'bg-emerald-50', color: 'text-emerald-600' },
  { name: 'SQLite', role: 'Base de données', icon: Database, bg: 'bg-blue-50', color: 'text-blue-600' },
  { name: 'Agents IA', role: 'Claude / OpenAI', icon: Bot, bg: 'bg-violet-50', color: 'text-violet-600' },
];
</script>
