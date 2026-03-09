<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl space-y-6">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Recherche globale</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Résultats pour “{{ q || '...' }}”</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Utilisateurs ({{ results.users.length }})</h2>
          <div v-if="results.users.length" class="space-y-2">
            <div v-for="u in results.users" :key="'u'+u.id" class="text-sm text-gray-700 dark:text-gray-300">
              <span class="font-medium">{{ u.name }}</span>
              <span class="text-gray-500 dark:text-gray-400"> · {{ u.email }}</span>
            </div>
          </div>
          <p v-else class="text-sm text-gray-500 dark:text-gray-400">Aucun résultat.</p>
        </section>

        <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Streams ({{ results.streams.length }})</h2>
          <div v-if="results.streams.length" class="space-y-2">
            <button
              v-for="s in results.streams"
              :key="'s'+s.id"
              @click="router.visit(`/streams/${s.slug}`)"
              class="w-full text-left text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
            >
              {{ s.name }}
            </button>
          </div>
          <p v-else class="text-sm text-gray-500 dark:text-gray-400">Aucun résultat.</p>
        </section>

        <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 lg:col-span-2">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">User Stories ({{ results.stories.length }})</h2>
          <div v-if="results.stories.length" class="space-y-2">
            <button
              v-for="story in results.stories"
              :key="'st'+story.id"
              @click="router.visit('/user-stories')"
              class="w-full text-left rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700/40 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
              <div class="text-sm font-medium text-gray-900 dark:text-white">{{ story.title }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ labelStatus(story.status) }} · {{ labelPriority(story.priority) }}</div>
            </button>
          </div>
          <p v-else class="text-sm text-gray-500 dark:text-gray-400">Aucun résultat.</p>
        </section>

        <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 lg:col-span-2">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Personas ({{ results.personas.length }})</h2>
          <div v-if="results.personas.length" class="space-y-2">
            <button
              v-for="p in results.personas"
              :key="'p'+p.id"
              @click="router.visit('/personas')"
              class="w-full text-left text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400"
            >
              {{ p.name }} <span class="text-gray-500 dark:text-gray-400">· {{ p.role }}</span>
            </button>
          </div>
          <p v-else class="text-sm text-gray-500 dark:text-gray-400">Aucun résultat.</p>
        </section>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  q: String,
  results: {
    type: Object,
    required: true,
  },
});

function labelStatus(value) {
  return ({ todo: 'À faire', in_progress: 'En cours', done: 'Terminé' }[value] || value);
}

function labelPriority(value) {
  return ({ low: 'Basse', medium: 'Moyenne', high: 'Haute' }[value] || value);
}
</script>
