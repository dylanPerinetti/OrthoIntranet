<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Backlog</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Gérez les User Stories du projet</p>
        </div>
        <button
          @click="openCreateModal()"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors shadow-sm"
        >
          <Plus class="w-4 h-4" :stroke-width="2" />
          Nouvelle User Story
        </button>
      </div>

      <!-- Filter bar -->
      <div class="flex items-center gap-3 flex-wrap">
        <div class="flex items-center gap-1.5 text-xs text-gray-500">
          <Filter class="w-3.5 h-3.5" :stroke-width="2" />
          <span>Filtres :</span>
        </div>
        <button
          v-for="stream in streams"
          :key="stream.id"
          @click="toggleStreamFilter(stream.id)"
          :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-xs font-medium border transition-colors',
            activeStreamFilter === stream.id
              ? 'border-gray-900 bg-gray-900 text-white'
              : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300',
          ]"
        >
          <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: stream.color }"></span>
          {{ stream.name }}
        </button>
        <button
          v-if="activeStreamFilter"
          @click="activeStreamFilter = null"
          class="text-xs text-gray-400 hover:text-gray-600 transition-colors"
        >
          Effacer
        </button>
      </div>

      <!-- Kanban Board -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5">
        <div
          v-for="column in filteredColumns"
          :key="column.key"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 flex flex-col min-h-[450px]"
          @dragover.prevent="onDragOver($event, column.key)"
          @dragleave="onDragLeave($event)"
          @drop="onDrop($event, column.key)"
        >
          <!-- Column header -->
          <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: column.color }"></span>
              <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ column.label }}</h3>
            </div>
            <span class="text-[11px] text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md font-medium tabular-nums">
              {{ column.items.length }}
            </span>
          </div>

          <!-- Cards container -->
          <div class="flex-1 p-3 space-y-2.5 overflow-y-auto">
            <div
              v-for="story in column.items"
              :key="story.id"
              draggable="true"
              @dragstart="onDragStart($event, story)"
              @dragend="onDragEnd"
              class="bg-white dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 shadow-sm hover:shadow transition-all cursor-grab active:cursor-grabbing p-3.5 group"
            >
              <!-- Top row: priority + stream -->
              <div class="flex items-center justify-between mb-2">
                <span
                  :class="['inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide', priorityClass(story.priority)]"
                >
                  {{ priorityLabel(story.priority) }}
                </span>
                <span
                  v-if="story.stream"
                  class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                  :style="{ backgroundColor: story.stream.color + '12', color: story.stream.color }"
                >
                  {{ story.stream.name }}
                </span>
              </div>

              <!-- Title -->
              <h4 class="text-[13px] font-semibold text-gray-900 dark:text-white leading-snug mb-1">{{ story.title }}</h4>

              <!-- Description -->
              <p v-if="story.description" class="text-[11px] text-gray-500 leading-relaxed line-clamp-2 mb-2.5">
                {{ story.description }}
              </p>

              <!-- Footer -->
              <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                  <div
                    v-if="story.assignee"
                    class="w-5 h-5 rounded-md bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white text-[10px] font-bold"
                    :title="story.assignee.name"
                  >
                    {{ story.assignee.name.charAt(0) }}
                  </div>
                  <span v-if="story.story_points" class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded font-medium tabular-nums">
                    {{ story.story_points }}pt
                  </span>
                </div>
                <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button
                    @click.stop="openEditModal(story)"
                    class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition-colors"
                    title="Modifier"
                  >
                    <Pencil class="w-3 h-3" :stroke-width="2" />
                  </button>
                  <button
                    @click.stop="deleteStory(story)"
                    class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors"
                    title="Supprimer"
                  >
                    <Trash2 class="w-3 h-3" :stroke-width="2" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="column.items.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
              <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mb-2">
                <Inbox class="w-5 h-5 text-gray-400" :stroke-width="1.5" />
              </div>
              <p class="text-xs text-gray-400">Aucune story</p>
              <p class="text-[11px] text-gray-300 mt-0.5">Glissez-déposez ou créez-en une</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════ -->
    <!-- MODAL CREATE / EDIT                     -->
    <!-- ═══════════════════════════════════════ -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4 pt-12 sm:pt-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal"></div>

        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 scale-95 translate-y-4"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
          appear
        >
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
          <!-- Header -->
          <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div :class="[
                'w-9 h-9 rounded-xl flex items-center justify-center',
                editingStory ? 'bg-blue-50 dark:bg-blue-900/30' : 'bg-emerald-50 dark:bg-emerald-900/30',
              ]">
                <Pencil v-if="editingStory" class="w-4 h-4 text-blue-600 dark:text-blue-400" :stroke-width="2" />
                <Sparkles v-else class="w-4 h-4 text-emerald-600 dark:text-emerald-400" :stroke-width="2" />
              </div>
              <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                  {{ editingStory ? 'Modifier la User Story' : 'Nouvelle User Story' }}
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Remplissez les champs ci-dessous</p>
              </div>
            </div>
            <button @click="closeModal" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 dark:text-gray-500 transition-colors">
              <X class="w-5 h-5" :stroke-width="2" />
            </button>
          </div>

          <!-- Body (scrollable) -->
          <form @submit.prevent="submitForm" class="flex-1 overflow-y-auto">
            <div class="px-6 py-5 space-y-6">

              <!-- ── Section 1 : Identité ── -->
              <div class="space-y-4">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  <FileText class="w-3.5 h-3.5" :stroke-width="2" />
                  Identité
                </div>

                <!-- Titre -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Titre <span class="text-red-500">*</span></label>
                  <input
                    ref="titleInput"
                    v-model="storyForm.title"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all"
                    placeholder="En tant que [persona], je veux [action]…"
                  />
                  <p v-if="storyForm.errors.title" class="mt-1 text-xs text-red-500">{{ storyForm.errors.title }}</p>
                </div>

                <!-- Description -->
                <div>
                  <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <span class="text-[10px] tabular-nums" :class="storyForm.description.length > 500 ? 'text-red-500' : 'text-gray-400 dark:text-gray-500'">{{ storyForm.description.length }}/500</span>
                  </div>
                  <textarea
                    v-model="storyForm.description"
                    rows="3"
                    maxlength="500"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all resize-none"
                    placeholder="Décrivez le besoin en détail…"
                  ></textarea>
                </div>

                <!-- Critères d'acceptation -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Critères d'acceptation</label>
                  <textarea
                    v-model="storyForm.acceptance_criteria"
                    rows="2"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all resize-none font-mono text-xs"
                    placeholder="- [ ] Critère 1&#10;- [ ] Critère 2"
                  ></textarea>
                </div>
              </div>

              <!-- ── Section 2 : Classification ── -->
              <div class="space-y-4">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  <Tag class="w-3.5 h-3.5" :stroke-width="2" />
                  Classification
                </div>

                <!-- Stream — pills visuels -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Stream <span class="text-red-500">*</span></label>
                  <div class="flex flex-wrap gap-2">
                    <button
                      v-for="s in streams"
                      :key="s.id"
                      type="button"
                      @click="storyForm.stream_id = s.id"
                      :class="[
                        'inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium border-2 transition-all',
                        storyForm.stream_id === s.id
                          ? 'border-current shadow-sm'
                          : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700/30',
                      ]"
                      :style="storyForm.stream_id === s.id ? { borderColor: s.color, backgroundColor: s.color + '10', color: s.color } : {}"
                    >
                      <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" :style="{ backgroundColor: s.color }"></span>
                      {{ s.name }}
                    </button>
                  </div>
                </div>

                <!-- Priorité — pills colorés -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Priorité <span class="text-red-500">*</span></label>
                  <div class="flex gap-2">
                    <button
                      v-for="p in priorityOptions"
                      :key="p.value"
                      type="button"
                      @click="storyForm.priority = p.value"
                      :class="[
                        'flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border-2 transition-all',
                        storyForm.priority === p.value
                          ? p.activeClass
                          : 'border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700/30',
                      ]"
                    >
                      <component :is="p.icon" class="w-3.5 h-3.5" :stroke-width="2" />
                      {{ p.label }}
                    </button>
                  </div>
                </div>

                <!-- Story Points — badges Fibonacci -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Story Points</label>
                  <div class="flex flex-wrap gap-1.5">
                    <button
                      v-for="sp in fibonacciPoints"
                      :key="sp"
                      type="button"
                      @click="storyForm.story_points = storyForm.story_points === sp ? null : sp"
                      :class="[
                        'w-9 h-9 rounded-lg text-xs font-bold border-2 transition-all',
                        storyForm.story_points === sp
                          ? 'border-blue-500 bg-blue-500 text-white shadow-sm shadow-blue-500/30'
                          : 'border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700/30 hover:border-gray-300 dark:hover:border-gray-500',
                      ]"
                    >
                      {{ sp }}
                    </button>
                  </div>
                </div>

                <!-- Statut -->
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Statut <span class="text-red-500">*</span></label>
                  <div class="flex gap-2">
                    <button
                      v-for="st in statusOptions"
                      :key="st.value"
                      type="button"
                      @click="storyForm.status = st.value"
                      :class="[
                        'flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border-2 transition-all',
                        storyForm.status === st.value
                          ? st.activeClass
                          : 'border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700/30',
                      ]"
                    >
                      <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: st.color }"></span>
                      {{ st.label }}
                    </button>
                  </div>
                </div>
              </div>

              <!-- ── Section 3 : Affectation ── -->
              <div class="space-y-4">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  <Users class="w-3.5 h-3.5" :stroke-width="2" />
                  Affectation
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <!-- Assigné à -->
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Assigné à</label>
                    <select
                      v-model="storyForm.assigned_to"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all appearance-none"
                    >
                      <option :value="null">Non assigné</option>
                      <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>
                  </div>

                  <!-- Persona -->
                  <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Persona</label>
                    <select
                      v-model="storyForm.persona_id"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all appearance-none"
                    >
                      <option :value="null">Aucun persona</option>
                      <option v-for="p in personas" :key="p.id" :value="p.id">{{ p.name }} — {{ p.role }}</option>
                    </select>
                  </div>

                  <!-- Sprint -->
                  <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sprint</label>
                    <select
                      v-model="storyForm.sprint_id"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-500 outline-none transition-all appearance-none"
                    >
                      <option :value="null">Non planifié</option>
                      <option v-for="sp in sprints" :key="sp.id" :value="sp.id">
                        S{{ sp.number }} — {{ sp.name }}
                        <template v-if="sp.status === 'active'"> (actif)</template>
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer fixe -->
            <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
              <p class="text-[11px] text-gray-400 dark:text-gray-500">
                <span class="text-red-500">*</span> Champs obligatoires
              </p>
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                >
                  Annuler
                </button>
                <button
                  type="submit"
                  :disabled="storyForm.processing"
                  class="inline-flex items-center gap-2 px-5 py-2 bg-[#1a1f36] dark:bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-[#252b45] dark:hover:bg-blue-700 transition-colors disabled:opacity-50 shadow-sm"
                >
                  <Loader2 v-if="storyForm.processing" class="w-4 h-4 animate-spin" :stroke-width="2" />
                  <CheckCircle2 v-else class="w-4 h-4" :stroke-width="2" />
                  {{ editingStory ? 'Enregistrer' : 'Créer la story' }}
                </button>
              </div>
            </div>
          </form>
        </div>
        </Transition>
      </div>
      </Transition>
    </Teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  Plus, Pencil, Trash2, Inbox, X, Filter,
  FileText, Tag, Users, Sparkles, Loader2, CheckCircle2,
  ArrowUp, Minus, ArrowDown,
} from 'lucide-vue-next';

const props = defineProps({
  columns: Array,
  streams: Array,
  users: Array,
  personas: Array,
  sprints: Array,
});

/* ── Options visuelles ── */
const fibonacciPoints = [1, 2, 3, 5, 8, 13, 21];

const priorityOptions = [
  { value: 'high',   label: 'Haute',   icon: ArrowUp,  activeClass: 'border-red-500 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400' },
  { value: 'medium', label: 'Moyenne', icon: Minus,     activeClass: 'border-amber-500 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' },
  { value: 'low',    label: 'Basse',   icon: ArrowDown, activeClass: 'border-gray-400 bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300' },
];

const statusOptions = [
  { value: 'todo',        label: 'À faire',  color: '#94a3b8', activeClass: 'border-slate-400 bg-slate-50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300' },
  { value: 'in_progress', label: 'En cours', color: '#3b82f6', activeClass: 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' },
  { value: 'done',        label: 'Terminé',  color: '#22c55e', activeClass: 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' },
];

const titleInput = ref(null);

/* ── Filter ── */
const activeStreamFilter = ref(null);

function toggleStreamFilter(id) {
  activeStreamFilter.value = activeStreamFilter.value === id ? null : id;
}

const filteredColumns = computed(() => {
  if (!activeStreamFilter.value) return props.columns;
  return props.columns.map(col => ({
    ...col,
    items: col.items.filter(s => s.stream_id === activeStreamFilter.value),
  }));
});

/* ── Modal state ── */
const showModal = ref(false);
const editingStory = ref(null);

const storyForm = useForm({
  title: '',
  description: '',
  acceptance_criteria: '',
  stream_id: '',
  status: 'todo',
  priority: 'medium',
  story_points: null,
  assigned_to: null,
  persona_id: null,
  sprint_id: null,
});

function openCreateModal() {
  editingStory.value = null;
  storyForm.reset();
  storyForm.clearErrors();
  if (props.streams.length) storyForm.stream_id = props.streams[0].id;
  showModal.value = true;
  nextTick(() => titleInput.value?.focus());
}

function openEditModal(story) {
  editingStory.value = story;
  storyForm.title = story.title;
  storyForm.description = story.description || '';
  storyForm.acceptance_criteria = story.acceptance_criteria || '';
  storyForm.stream_id = story.stream_id;
  storyForm.status = story.status;
  storyForm.priority = story.priority;
  storyForm.story_points = story.story_points;
  storyForm.assigned_to = story.assigned_to;
  storyForm.persona_id = story.persona_id || null;
  storyForm.sprint_id = story.sprint_id || null;
  storyForm.clearErrors();
  showModal.value = true;
  nextTick(() => titleInput.value?.focus());
}

function closeModal() {
  showModal.value = false;
  editingStory.value = null;
}

function submitForm() {
  if (editingStory.value) {
    storyForm.put(`/user-stories/${editingStory.value.id}`, { onSuccess: () => closeModal(), preserveScroll: true });
  } else {
    storyForm.post('/user-stories', { onSuccess: () => closeModal(), preserveScroll: true });
  }
}

function deleteStory(story) {
  if (confirm('Supprimer cette User Story ?')) {
    router.delete(`/user-stories/${story.id}`, { preserveScroll: true });
  }
}

/* ── Drag & Drop ── */
let draggedStory = null;

function onDragStart(event, story) {
  draggedStory = story;
  event.dataTransfer.effectAllowed = 'move';
  event.dataTransfer.setData('text/plain', story.id);
  event.target.classList.add('dragging');
}

function onDragEnd(event) {
  event.target.classList.remove('dragging');
}

function onDragOver(event, columnKey) {
  event.currentTarget.classList.add('drag-over');
}

function onDragLeave(event) {
  event.currentTarget.classList.remove('drag-over');
}

function onDrop(event, newStatus) {
  event.preventDefault();
  event.currentTarget.classList.remove('drag-over');
  if (!draggedStory || draggedStory.status === newStatus) { draggedStory = null; return; }
  router.patch(`/user-stories/${draggedStory.id}/move`, { status: newStatus, sort_order: 0 }, { preserveScroll: true });
  draggedStory = null;
}

/* ── Helpers ── */
function priorityClass(p) {
  return { high: 'bg-red-50 text-red-700 border border-red-200/60', medium: 'bg-amber-50 text-amber-700 border border-amber-200/60', low: 'bg-gray-50 text-gray-600 border border-gray-200/60' }[p] || '';
}
function priorityLabel(p) {
  return { high: 'Haute', medium: 'Moy', low: 'Basse' }[p] || p;
}
</script>
