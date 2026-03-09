<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Audit Logs</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Journal des actions métier (qui, quoi, quand)</p>
      </div>

      <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/40 text-gray-600 dark:text-gray-300">
              <tr>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3 text-left font-semibold">Utilisateur</th>
                <th class="px-4 py-3 text-left font-semibold">Action</th>
                <th class="px-4 py-3 text-left font-semibold">Cible</th>
                <th class="px-4 py-3 text-left font-semibold">IP</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr v-for="log in logs" :key="log.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 whitespace-nowrap">{{ log.created_at }}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                  <div v-if="log.user">
                    <div class="font-medium">{{ log.user.name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ log.user.email }}</div>
                  </div>
                  <span v-else class="text-gray-400">—</span>
                </td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-medium">
                    {{ log.action }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                  {{ shortType(log.auditable_type) }}#{{ log.auditable_id ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ log.ip_address || '—' }}</td>
              </tr>
              <tr v-if="!logs.length">
                <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">Aucun log disponible.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  logs: {
    type: Array,
    default: () => [],
  },
});

function shortType(type) {
  if (!type) return '—';
  const parts = type.split('\\\\');
  return parts[parts.length - 1];
}
</script>
