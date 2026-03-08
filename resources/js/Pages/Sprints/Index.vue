<template>
  <AuthenticatedLayout>
    <div class="space-y-8">

      <!-- ═══════════════════════════════════════ -->
      <!-- PAGE HEADER                              -->
      <!-- ═══════════════════════════════════════ -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Roadmap Agile</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ sprints.length }} sprint{{ sprints.length > 1 ? 's' : '' }} ·
            {{ unassignedCount }} stor{{ unassignedCount > 1 ? 'ies' : 'y' }} non assignée{{ unassignedCount > 1 ? 's' : '' }}
          </p>
        </div>
        <button
          @click="openCreate()"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors"
        >
          <Plus class="w-4 h-4" :stroke-width="2" />
          Nouveau Sprint
        </button>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- PROGRESS OVERVIEW                        -->
      <!-- ═══════════════════════════════════════ -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
              <Milestone class="w-[18px] h-[18px] text-blue-600 dark:text-blue-400" :stroke-width="1.8" />
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ sprints.length }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sprints planifiés</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
              <Play class="w-[18px] h-[18px] text-emerald-600 dark:text-emerald-400" :stroke-width="1.8" />
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ activeSprint?.name || '—' }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sprint actif</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
              <ListChecks class="w-[18px] h-[18px] text-amber-600 dark:text-amber-400" :stroke-width="1.8" />
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalStories }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Stories assignées</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-9 h-9 rounded-lg bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
              <BarChart3 class="w-[18px] h-[18px] text-violet-600 dark:text-violet-400" :stroke-width="1.8" />
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ globalProgress }}%</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Progression globale</div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- TIMELINE HORIZONTAL                      -->
      <!-- ═══════════════════════════════════════ -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5 sm:p-6 overflow-x-auto">
        <div class="flex items-center gap-2 mb-5">
          <Route class="w-4 h-4 text-gray-400" :stroke-width="2" />
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Timeline</h3>
        </div>

        <div class="relative flex items-start gap-0 min-w-max">
          <!-- Connection line -->
          <div class="absolute top-5 left-5 right-5 h-0.5 bg-gray-200 dark:bg-gray-700"></div>

          <div
            v-for="(sprint, idx) in sprints"
            :key="sprint.id"
            class="relative flex flex-col items-center px-6 min-w-[180px]"
          >
            <!-- Circle -->
            <div
              :class="[
                'relative z-10 w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all',
                sprint.status === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white' :
                sprint.status === 'active'    ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200 ring-4 ring-blue-100' :
                                                'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-500 text-gray-500 dark:text-gray-300',
              ]"
            >
              <Check v-if="sprint.status === 'completed'" class="w-5 h-5" :stroke-width="2.5" />
              <template v-else>{{ sprint.number }}</template>
            </div>

            <!-- Label -->
            <div class="mt-3 text-center max-w-[160px]">
              <p :class="['text-sm font-semibold', sprint.status === 'active' ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300']">
                {{ sprint.name }}
              </p>
              <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                {{ formatDate(sprint.start_date) }} — {{ formatDate(sprint.end_date) }}
              </p>
              <span
                :class="[
                  'inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-full text-[10px] font-medium',
                  statusClasses[sprint.status],
                ]"
              >
                <span :class="['w-1.5 h-1.5 rounded-full', statusDots[sprint.status]]"></span>
                {{ statusLabels[sprint.status] }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- SPRINT CARDS                             -->
      <!-- ═══════════════════════════════════════ -->
      <div class="space-y-5">
        <div
          v-for="sprint in sprints"
          :key="sprint.id"
          :class="[
            'bg-white dark:bg-gray-800 rounded-xl border overflow-hidden transition-all',
            sprint.status === 'active' ? 'border-blue-200 dark:border-blue-800 shadow-sm' : 'border-gray-200/80 dark:border-gray-700/80',
          ]"
        >
          <!-- Sprint header -->
          <div
            :class="[
              'px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3',
              sprint.status === 'active' ? 'bg-blue-50/50 dark:bg-blue-900/20' : 'bg-gray-50/50 dark:bg-gray-700/30',
            ]"
          >
            <div class="flex items-center gap-3">
              <div
                :class="[
                  'w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold',
                  sprint.status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' :
                  sprint.status === 'active'    ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400' :
                                                  'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
                ]"
              >
                S{{ sprint.number }}
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="text-[15px] font-semibold text-gray-900 dark:text-white">{{ sprint.name }}</h3>
                  <span
                    :class="[
                      'px-2 py-0.5 rounded-full text-[10px] font-medium',
                      statusClasses[sprint.status],
                    ]"
                  >
                    {{ statusLabels[sprint.status] }}
                  </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                  {{ formatDate(sprint.start_date) }} → {{ formatDate(sprint.end_date) }}
                  <span v-if="daysInfo(sprint)" class="ml-1 text-gray-400 dark:text-gray-500">· {{ daysInfo(sprint) }}</span>
                </p>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <button
                @click="openEdit(sprint)"
                class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                title="Modifier"
              >
                <Pencil class="w-4 h-4" :stroke-width="1.8" />
              </button>
              <button
                @click="deleteSprint(sprint)"
                class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                title="Supprimer"
              >
                <Trash2 class="w-4 h-4" :stroke-width="1.8" />
              </button>
            </div>
          </div>

          <!-- Sprint body -->
          <div class="px-5 sm:px-6 py-5">
            <!-- Goal -->
            <p v-if="sprint.goal" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
              <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 block mb-1">Objectif</span>
              {{ sprint.goal }}
            </p>

            <!-- Progress -->
            <div class="mb-4">
              <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                <span>{{ sprint.user_stories_count }} stories · {{ sprint.stories_done_count }} terminée{{ sprint.stories_done_count > 1 ? 's' : '' }}</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ sprintProgress(sprint) }}%</span>
              </div>
              <div class="w-full h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                <div
                  :class="[
                    'h-full rounded-full transition-all duration-500',
                    sprint.status === 'completed' ? 'bg-emerald-500' :
                    sprint.status === 'active'    ? 'bg-gradient-to-r from-blue-500 to-cyan-500' :
                                                    'bg-gray-300',
                  ]"
                  :style="{ width: sprintProgress(sprint) + '%' }"
                ></div>
              </div>
            </div>

            <!-- Mini status breakdown -->
            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mb-5">
              <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-sm bg-gray-300"></span>
                {{ sprint.stories_todo_count }} à faire
              </span>
              <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-sm bg-blue-500"></span>
                {{ sprint.stories_in_progress_count }} en cours
              </span>
              <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-sm bg-emerald-500"></span>
                {{ sprint.stories_done_count }} terminée{{ sprint.stories_done_count > 1 ? 's' : '' }}
              </span>
            </div>

            <!-- Stories list -->
            <div v-if="sprint.user_stories?.length" class="space-y-2">
              <div
                v-for="story in sprint.user_stories"
                :key="story.id"
                class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-all"
              >
                <span
                  :class="[
                    'w-2.5 h-2.5 rounded-full flex-shrink-0',
                    story.status === 'done' ? 'bg-emerald-500' :
                    story.status === 'in_progress' ? 'bg-blue-500' : 'bg-gray-300',
                  ]"
                ></span>
                <span class="flex-1 text-sm text-gray-700 dark:text-gray-300 truncate">{{ story.title }}</span>
                <span
                  v-if="story.stream"
                  class="hidden sm:inline-flex px-1.5 py-0.5 rounded text-[10px] font-medium"
                  :style="{ backgroundColor: story.stream.color + '14', color: story.stream.color }"
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
                  {{ { high: 'Haute', medium: 'Moyenne', low: 'Basse' }[story.priority] }}
                </span>
                <span
                  v-if="story.assignee"
                  class="hidden sm:inline-flex items-center gap-1 text-[10px] text-gray-400"
                >
                  <User2 class="w-3 h-3" :stroke-width="2" />
                  {{ story.assignee.name }}
                </span>
              </div>
            </div>
            <div v-else class="text-center py-6">
              <ListChecks class="w-6 h-6 text-gray-300 mx-auto mb-1" :stroke-width="1.5" />
              <p class="text-xs text-gray-400">Aucune user story assignée</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-if="!sprints.length" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-12 text-center">
        <Milestone class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" :stroke-width="1.5" />
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Aucun sprint</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Créez votre premier sprint pour commencer à organiser la roadmap.</p>
        <button
          @click="openCreate()"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors"
        >
          <Plus class="w-4 h-4" :stroke-width="2" />
          Créer un sprint
        </button>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- MODAL CREATE/EDIT                        -->
      <!-- ═══════════════════════════════════════ -->
      <Teleport to="body">
        <Transition name="modal">
          <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
              <!-- Modal header -->
              <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-700/30">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                  {{ editing ? 'Modifier le sprint' : 'Nouveau Sprint' }}
                </h3>
                <button @click="showModal = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                  <X class="w-4 h-4" :stroke-width="2" />
                </button>
              </div>

              <!-- Modal body -->
              <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nom du sprint *</label>
                  <input
                    v-model="form.name"
                    type="text"
                    placeholder="Sprint 1 — Fondations"
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                  />
                  <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Numéro *</label>
                    <input
                      v-model="form.number"
                      type="number"
                      min="1"
                      class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                    />
                    <p v-if="form.errors.number" class="text-xs text-red-500 mt-1">{{ form.errors.number }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Statut *</label>
                    <select
                      v-model="form.status"
                      class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                    >
                      <option value="planned">Planifié</option>
                      <option value="active">Actif</option>
                      <option value="completed">Terminé</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Objectif du sprint</label>
                  <textarea
                    v-model="form.goal"
                    rows="3"
                    placeholder="Quel est l'objectif principal de ce sprint ?"
                    class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none"
                  ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date de début *</label>
                    <input
                      v-model="form.start_date"
                      type="date"
                      class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                    />
                    <p v-if="form.errors.start_date" class="text-xs text-red-500 mt-1">{{ form.errors.start_date }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date de fin *</label>
                    <input
                      v-model="form.end_date"
                      type="date"
                      class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                    />
                    <p v-if="form.errors.end_date" class="text-xs text-red-500 mt-1">{{ form.errors.end_date }}</p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                  <button
                    type="button"
                    @click="showModal = false"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                  >
                    Annuler
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors disabled:opacity-50 flex items-center gap-2"
                  >
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" :stroke-width="2" />
                    {{ editing ? 'Enregistrer' : 'Créer le sprint' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </Transition>
      </Teleport>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
  Plus, Pencil, Trash2, X, Loader2,
  Milestone, Play, ListChecks, BarChart3,
  Route, Check, User2,
} from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  sprints: Array,
  unassignedCount: Number,
});

/* ── Status display ── */
const statusLabels = { planned: 'Planifié', active: 'Actif', completed: 'Terminé' };
const statusClasses = {
  planned:   'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
  active:    'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
  completed: 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
};
const statusDots = {
  planned:   'bg-gray-400',
  active:    'bg-blue-500',
  completed: 'bg-emerald-500',
};

/* ── Computed ── */
const activeSprint = computed(() => props.sprints.find(s => s.status === 'active'));

const totalStories = computed(() => props.sprints.reduce((sum, s) => sum + (s.user_stories_count || 0), 0));

const totalDone = computed(() => props.sprints.reduce((sum, s) => sum + (s.stories_done_count || 0), 0));

const globalProgress = computed(() => {
  if (!totalStories.value) return 0;
  return Math.round((totalDone.value / totalStories.value) * 100);
});

function sprintProgress(sprint) {
  if (!sprint.user_stories_count) return 0;
  return Math.round(((sprint.stories_done_count || 0) / sprint.user_stories_count) * 100);
}

function formatDate(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr);
  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
}

function daysInfo(sprint) {
  if (!sprint.start_date || !sprint.end_date) return '';
  const now = new Date();
  const start = new Date(sprint.start_date);
  const end = new Date(sprint.end_date);

  if (sprint.status === 'completed') return 'Terminé';
  if (sprint.status === 'active') {
    const remaining = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
    if (remaining < 0) return 'En retard';
    return `${remaining}j restants`;
  }
  const daysUntil = Math.ceil((start - now) / (1000 * 60 * 60 * 24));
  if (daysUntil <= 0) return 'Prêt';
  return `dans ${daysUntil}j`;
}

/* ── Modal / CRUD ── */
const showModal = ref(false);
const editing = ref(null);

const form = useForm({
  name: '',
  number: 1,
  goal: '',
  start_date: '',
  end_date: '',
  status: 'planned',
});

function openCreate() {
  editing.value = null;
  form.reset();
  form.number = (props.sprints.length > 0 ? Math.max(...props.sprints.map(s => s.number)) + 1 : 1);

  // Default: start after last sprint ends, 2-week duration
  const last = props.sprints[props.sprints.length - 1];
  if (last?.end_date) {
    const start = new Date(last.end_date);
    start.setDate(start.getDate() + 1);
    form.start_date = start.toISOString().split('T')[0];
    const end = new Date(start);
    end.setDate(end.getDate() + 13);
    form.end_date = end.toISOString().split('T')[0];
  }

  showModal.value = true;
}

function openEdit(sprint) {
  editing.value = sprint;
  form.name = sprint.name;
  form.number = sprint.number;
  form.goal = sprint.goal || '';
  form.start_date = sprint.start_date?.split('T')[0] || '';
  form.end_date = sprint.end_date?.split('T')[0] || '';
  form.status = sprint.status;
  showModal.value = true;
}

function submitForm() {
  if (editing.value) {
    form.put(`/sprints/${editing.value.id}`, {
      onSuccess: () => { showModal.value = false; },
    });
  } else {
    form.post('/sprints', {
      onSuccess: () => { showModal.value = false; },
    });
  }
}

function deleteSprint(sprint) {
  if (!confirm(`Supprimer « ${sprint.name} » ? Les stories seront désassignées.`)) return;
  form.delete(`/sprints/${sprint.id}`);
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
