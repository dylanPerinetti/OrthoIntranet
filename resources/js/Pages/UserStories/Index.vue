<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">User Stories</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Toutes les User Stories par activité et persona</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
          <span class="tabular-nums font-semibold text-gray-900 dark:text-white">{{ stories.length }}</span>
          stories au total
        </div>
      </div>

      <!-- Filters row -->
      <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-1.5 text-xs text-gray-500">
          <Filter class="w-3.5 h-3.5" :stroke-width="2" />
          <span>Filtrer :</span>
        </div>

        <!-- Stream filters -->
        <button
          v-for="stream in streams"
          :key="'s-' + stream.id"
          @click="toggleFilter('stream', stream.id)"
          :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-xs font-medium border transition-colors',
            activeStreamId === stream.id
              ? 'border-gray-900 bg-gray-900 text-white'
              : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300',
          ]"
        >
          <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: stream.color }"></span>
          {{ stream.name }}
        </button>

        <span class="text-gray-300">|</span>

        <!-- Persona filters -->
        <button
          v-for="persona in personas"
          :key="'p-' + persona.id"
          @click="toggleFilter('persona', persona.id)"
          :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-xs font-medium border transition-colors',
            activePersonaId === persona.id
              ? 'border-gray-900 bg-gray-900 text-white'
              : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300',
          ]"
        >
          <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: persona.avatar_color }"></span>
          {{ persona.name }}
        </button>

        <span class="text-gray-300">|</span>

        <!-- Status filters -->
        <button
          v-for="st in statuses"
          :key="'st-' + st.key"
          @click="toggleFilter('status', st.key)"
          :class="[
            'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-xs font-medium border transition-colors',
            activeStatus === st.key
              ? 'border-gray-900 bg-gray-900 text-white'
              : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300',
          ]"
        >
          <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: st.color }"></span>
          {{ st.label }}
        </button>

        <button
          v-if="activeStreamId || activePersonaId || activeStatus"
          @click="clearFilters"
          class="text-xs text-gray-400 hover:text-gray-600 transition-colors ml-1"
        >
          Effacer tout
        </button>
      </div>

      <!-- Stories grouped by stream -->
      <div v-if="filteredStories.length" class="space-y-4">
        <div
          v-for="story in filteredStories"
          :key="story.id"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4 sm:p-5 hover:shadow-sm transition-shadow"
        >
          <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
            <!-- Left: persona avatar -->
            <div
              v-if="story.persona"
              class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
              :style="{ background: `linear-gradient(135deg, ${story.persona.avatar_color}, ${story.persona.avatar_color}cc)` }"
              :title="story.persona.name + ' — ' + story.persona.role"
            >
              {{ story.persona.name.charAt(0) }}
            </div>
            <div v-else class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <UserCircle class="w-5 h-5 text-gray-400" :stroke-width="1.5" />
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-1.5">
                <!-- Priority -->
                <span :class="['inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide', priorityClass(story.priority)]">
                  {{ priorityLabel(story.priority) }}
                </span>
                <!-- Status -->
                <span
                  class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium"
                  :style="{ backgroundColor: statusColor(story.status) + '14', color: statusColor(story.status) }"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: statusColor(story.status) }"></span>
                  {{ statusLabel(story.status) }}
                </span>
                <!-- Stream -->
                <span
                  v-if="story.stream"
                  class="text-[10px] px-1.5 py-0.5 rounded font-medium"
                  :style="{ backgroundColor: story.stream.color + '12', color: story.stream.color }"
                >
                  {{ story.stream.name }}
                </span>
                <!-- Persona -->
                <span
                  v-if="story.persona"
                  class="text-[10px] px-1.5 py-0.5 rounded font-medium bg-gray-100 text-gray-600"
                >
                  {{ story.persona.name }}
                </span>
              </div>

              <h3 class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">{{ story.title }}</h3>
              <p v-if="story.description" class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed line-clamp-2">{{ story.description }}</p>

              <!-- Acceptance criteria preview -->
              <div v-if="story.acceptance_criteria" class="mt-2 p-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600">
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Critères d'acceptation</p>
                <p class="text-[11px] text-gray-600 leading-relaxed whitespace-pre-line line-clamp-3">{{ story.acceptance_criteria }}</p>
              </div>
            </div>

            <!-- Right: metadata -->
            <div class="flex sm:flex-col items-center sm:items-end gap-2 flex-shrink-0">
              <div
                v-if="story.assignee"
                class="w-6 h-6 rounded-md bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white text-[10px] font-bold"
                :title="'Assigné à ' + story.assignee.name"
              >
                {{ story.assignee.name.charAt(0) }}
              </div>
              <span v-if="story.story_points" class="text-[11px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded font-medium tabular-nums">
                {{ story.story_points }}pt
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else class="bg-white rounded-xl border border-gray-200/80 flex flex-col items-center py-20 text-center">
        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mb-3">
          <BookOpen class="w-6 h-6 text-gray-400" :stroke-width="1.5" />
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Aucune User Story trouvée</h3>
        <p class="text-xs text-gray-500">Essayez d'ajuster vos filtres ou créez-en depuis le Backlog.</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Filter, UserCircle, BookOpen } from 'lucide-vue-next';

const props = defineProps({
  stories: Array,
  streams: Array,
  personas: Array,
  users: Array,
});

const activeStreamId = ref(null);
const activePersonaId = ref(null);
const activeStatus = ref(null);

const statuses = [
  { key: 'todo', label: 'À faire', color: '#64748b' },
  { key: 'in_progress', label: 'En cours', color: '#3b82f6' },
  { key: 'done', label: 'Terminé', color: '#10b981' },
];

function toggleFilter(type, val) {
  if (type === 'stream') activeStreamId.value = activeStreamId.value === val ? null : val;
  else if (type === 'persona') activePersonaId.value = activePersonaId.value === val ? null : val;
  else if (type === 'status') activeStatus.value = activeStatus.value === val ? null : val;
}

function clearFilters() {
  activeStreamId.value = null;
  activePersonaId.value = null;
  activeStatus.value = null;
}

const filteredStories = computed(() => {
  return props.stories.filter(s => {
    if (activeStreamId.value && s.stream_id !== activeStreamId.value) return false;
    if (activePersonaId.value && s.persona_id !== activePersonaId.value) return false;
    if (activeStatus.value && s.status !== activeStatus.value) return false;
    return true;
  });
});

function priorityClass(p) {
  return {
    high: 'bg-red-50 text-red-700 border border-red-200/60',
    medium: 'bg-amber-50 text-amber-700 border border-amber-200/60',
    low: 'bg-gray-50 text-gray-600 border border-gray-200/60',
  }[p] || '';
}
function priorityLabel(p) {
  return { high: 'Haute', medium: 'Moy', low: 'Basse' }[p] || p;
}
function statusColor(s) {
  return { todo: '#64748b', in_progress: '#3b82f6', done: '#10b981' }[s] || '#6b7280';
}
function statusLabel(s) {
  return { todo: 'À faire', in_progress: 'En cours', done: 'Terminé' }[s] || s;
}
</script>
