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
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4 pt-16 sm:pt-4">
        <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
          <!-- Header -->
          <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-5 py-4 flex items-center justify-between rounded-t-xl z-10">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">
              {{ editingStory ? 'Modifier la User Story' : 'Nouvelle User Story' }}
            </h3>
            <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
              <X class="w-5 h-5" :stroke-width="2" />
            </button>
          </div>

          <form @submit.prevent="submitForm" class="p-5 space-y-4">
            <!-- Titre -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Titre *</label>
              <input
                v-model="storyForm.title"
                type="text"
                required
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                placeholder="En tant que [persona], je veux [action]…"
              />
              <p v-if="storyForm.errors.title" class="mt-1 text-xs text-red-600">{{ storyForm.errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Description</label>
              <textarea
                v-model="storyForm.description"
                rows="3"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="Décrivez le besoin en détail…"
              ></textarea>
            </div>

            <!-- Critères -->
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Critères d'acceptation</label>
              <textarea
                v-model="storyForm.acceptance_criteria"
                rows="2"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="- [ ] Critère 1&#10;- [ ] Critère 2"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Stream *</label>
                <select
                  v-model="storyForm.stream_id"
                  required
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
                >
                  <option value="" disabled>Choisir…</option>
                  <option v-for="s in streams" :key="s.id" :value="s.id">{{ s.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Statut *</label>
                <select
                  v-model="storyForm.status"
                  required
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
                >
                  <option value="todo">À faire</option>
                  <option value="in_progress">En cours</option>
                  <option value="done">Terminé</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Priorité *</label>
                <select
                  v-model="storyForm.priority"
                  required
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
                >
                  <option value="low">Basse</option>
                  <option value="medium">Moyenne</option>
                  <option value="high">Haute</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Story Points</label>
                <input
                  v-model.number="storyForm.story_points"
                  type="number"
                  min="1"
                  max="21"
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                  placeholder="1-21"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Assigné à</label>
              <select
                v-model="storyForm.assigned_to"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
              >
                <option :value="null">Non assigné</option>
                <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
              </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
              >
                Annuler
              </button>
              <button
                type="submit"
                :disabled="storyForm.processing"
                class="px-5 py-2 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors disabled:opacity-50"
              >
                {{ editingStory ? 'Enregistrer' : 'Créer' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Plus, Pencil, Trash2, Inbox, X, Filter } from 'lucide-vue-next';

const props = defineProps({
  columns: Array,
  streams: Array,
  users: Array,
});

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
});

function openCreateModal() {
  editingStory.value = null;
  storyForm.reset();
  storyForm.clearErrors();
  if (props.streams.length) storyForm.stream_id = props.streams[0].id;
  showModal.value = true;
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
  storyForm.clearErrors();
  showModal.value = true;
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
