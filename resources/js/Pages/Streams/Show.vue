<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <button
              @click="$inertia.visit('/')"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <ArrowLeft class="w-4 h-4" :stroke-width="2" />
            </button>
            <span
              class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
              :style="{ backgroundColor: stream.color + '14', color: stream.color }"
            >
              <component :is="iconMap[stream.icon] || Settings" class="w-3 h-3" :stroke-width="2" />
              Stream
            </span>
          </div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ stream.name }}</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ stream.description }}</p>
        </div>
      </div>

      <!-- Stats row -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4">
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stories.length }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">User Stories</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ statusCounts.todo }}</span>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">À faire</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ statusCounts.in_progress }}</span>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">En cours</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ statusCounts.done }}</span>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Terminé</div>
        </div>
      </div>

      <!-- Progress bar -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-5">
        <div class="flex items-center justify-between text-sm mb-3">
          <span class="font-medium text-gray-700 dark:text-gray-300">Progression</span>
          <span class="font-bold text-gray-900 dark:text-white">{{ progressPercent }}%</span>
        </div>
        <div class="w-full h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
          <div
            class="h-full rounded-full transition-all duration-700"
            :style="{ width: progressPercent + '%', backgroundColor: stream.color }"
          ></div>
        </div>
      </div>

      <!-- Stories list -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">User Stories</h3>
          <div class="flex items-center gap-2">
            <button
              v-for="f in filters"
              :key="f.key"
              @click="activeFilter = activeFilter === f.key ? null : f.key"
              :class="[
                'px-2.5 py-1 rounded-md text-[11px] font-medium border transition-colors',
                activeFilter === f.key
                  ? 'border-gray-900 bg-gray-900 text-white'
                  : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300',
              ]"
            >
              {{ f.label }}
            </button>
          </div>
        </div>

        <div class="divide-y divide-gray-100">
          <div
            v-for="story in filteredStories"
            :key="story.id"
            class="px-5 py-4 hover:bg-gray-50/50 transition-colors"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span
                    :class="['inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide', priorityClass(story.priority)]"
                  >
                    {{ priorityLabel(story.priority) }}
                  </span>
                  <span
                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium"
                    :style="{ backgroundColor: statusColor(story.status) + '14', color: statusColor(story.status) }"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: statusColor(story.status) }"></span>
                    {{ statusLabel(story.status) }}
                  </span>
                </div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ story.title }}</h4>
                <p v-if="story.description" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ story.description }}</p>
              </div>

              <div class="flex items-center gap-3 flex-shrink-0">
                <div
                  v-if="story.assignee"
                  class="w-6 h-6 rounded-md bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white text-[10px] font-bold"
                  :title="story.assignee.name"
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

        <div v-if="!filteredStories.length" class="flex flex-col items-center py-16 text-center">
          <Inbox class="w-10 h-10 text-gray-300 mb-3" :stroke-width="1.5" />
          <p class="text-sm text-gray-500">Aucune story dans ce filtre</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  ArrowLeft, Settings, Megaphone, GraduationCap, Inbox,
} from 'lucide-vue-next';

const props = defineProps({
  stream: Object,
  stories: Array,
  statusCounts: Object,
});

const iconMap = { Settings, Megaphone, GraduationCap };

const activeFilter = ref(null);

const filters = [
  { key: 'todo', label: 'À faire' },
  { key: 'in_progress', label: 'En cours' },
  { key: 'done', label: 'Terminé' },
];

const filteredStories = computed(() => {
  if (!activeFilter.value) return props.stories;
  return props.stories.filter(s => s.status === activeFilter.value);
});

const totalStories = computed(() => props.stories.length);
const progressPercent = computed(() => {
  if (!totalStories.value) return 0;
  return Math.round(((props.statusCounts?.done || 0) / totalStories.value) * 100);
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
