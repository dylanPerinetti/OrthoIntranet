<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-red-50 text-red-700 rounded-md text-[10px] font-bold uppercase tracking-wider border border-red-200/60">
              <ShieldCheck class="w-3 h-3" :stroke-width="2" />
              Admin
            </span>
          </div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Gestion des Utilisateurs</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">RBAC — Contrôle d'accès basé sur les rôles</p>
        </div>
        <button
          @click="openCreateModal()"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors shadow-sm"
        >
          <UserPlus class="w-4 h-4" :stroke-width="2" />
          Ajouter un utilisateur
        </button>
      </div>

      <!-- Stats cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4">
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ users.length }}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Total utilisateurs</div>
        </div>
        <div
          v-for="(label, role) in roles"
          :key="role"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 p-4"
        >
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: roleColor(role) }"></span>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ users.filter(u => u.role === role).length }}</div>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">{{ label }}</div>
        </div>
      </div>

      <!-- Users table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden">
        <!-- Desktop table -->
        <div class="hidden sm:block overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-700">
                <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3.5">Utilisateur</th>
                <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3.5">Email</th>
                <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3.5">Rôle</th>
                <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3.5">Créé le</th>
                <th class="text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-5 py-3.5">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-5 py-3.5">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                      :style="{ background: `linear-gradient(135deg, ${roleColor(user.role)}, ${roleColor(user.role)}cc)` }"
                    >
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</span>
                  </div>
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</td>
                <td class="px-5 py-3.5">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold"
                    :style="{ backgroundColor: roleColor(user.role) + '14', color: roleColor(user.role) }"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: roleColor(user.role) }"></span>
                    {{ user.role_name }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-500 tabular-nums">{{ user.created_at }}</td>
                <td class="px-5 py-3.5 text-right">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button
                      @click="openEditModal(user)"
                      class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition-colors"
                      title="Modifier"
                    >
                      <Pencil class="w-3.5 h-3.5" :stroke-width="2" />
                    </button>
                    <button
                      @click="deleteUser(user)"
                      class="p-1.5 rounded-md hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors"
                      title="Supprimer"
                    >
                      <Trash2 class="w-3.5 h-3.5" :stroke-width="2" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile cards -->
        <div class="sm:hidden divide-y divide-gray-100">
          <div v-for="user in users" :key="user.id" class="p-4">
            <div class="flex items-start justify-between">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                  :style="{ background: `linear-gradient(135deg, ${roleColor(user.role)}, ${roleColor(user.role)}cc)` }"
                >
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ user.name }}</p>
                  <p class="text-xs text-gray-500">{{ user.email }}</p>
                </div>
              </div>
              <div class="flex items-center gap-1">
                <button @click="openEditModal(user)" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400">
                  <Pencil class="w-3.5 h-3.5" />
                </button>
                <button @click="deleteUser(user)" class="p-1.5 rounded-md hover:bg-red-50 text-gray-400">
                  <Trash2 class="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
            <div class="flex items-center gap-2 mt-2.5">
              <span
                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold"
                :style="{ backgroundColor: roleColor(user.role) + '14', color: roleColor(user.role) }"
              >
                {{ user.role_name }}
              </span>
              <span class="text-[11px] text-gray-400">{{ user.created_at }}</span>
            </div>
          </div>
        </div>

        <div v-if="!users.length" class="flex flex-col items-center py-16 text-center">
          <Users class="w-10 h-10 text-gray-300 mb-3" :stroke-width="1.5" />
          <p class="text-sm text-gray-500">Aucun utilisateur</p>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════ -->
    <!-- MODAL CREATE / EDIT                     -->
    <!-- ═══════════════════════════════════════ -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4 pt-16 sm:pt-4">
        <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md max-h-[85vh] overflow-y-auto">
          <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-5 py-4 flex items-center justify-between rounded-t-xl z-10">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">
              {{ editingUser ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}
            </h3>
            <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
              <X class="w-5 h-5" :stroke-width="2" />
            </button>
          </div>

          <form @submit.prevent="submitForm" class="p-5 space-y-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Nom complet *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                placeholder="Dr André Ziani"
              />
              <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                placeholder="email@orthodz.fr"
              />
              <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Rôle *</label>
              <select
                v-model="form.role"
                required
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
              >
                <option v-for="(label, key) in roles" :key="key" :value="key">{{ label }}</option>
              </select>

              <!-- Role description -->
              <div class="mt-2 p-2.5 rounded-lg bg-gray-50 border border-gray-100">
                <div class="flex items-start gap-2">
                  <ShieldCheck class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" :stroke-width="2" />
                  <div class="text-[11px] text-gray-500 leading-relaxed">
                    <template v-if="form.role === 'admin'">
                      <strong class="text-gray-700">Accès complet</strong> — Panel admin, gestion utilisateurs, toutes les fonctionnalités.
                    </template>
                    <template v-else-if="form.role === 'secretaire'">
                      <strong class="text-gray-700">Accès standard</strong> — Dashboard, backlog, streams. Pas d'accès admin.
                    </template>
                    <template v-else>
                      <strong class="text-gray-700">Accès limité</strong> — Dashboard et consultation. Pas d'accès admin.
                    </template>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                Mot de passe {{ editingUser ? '(laisser vide pour ne pas changer)' : '*' }}
              </label>
              <input
                v-model="form.password"
                type="password"
                :required="!editingUser"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                placeholder="••••••••"
              />
              <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
            </div>

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
                :disabled="form.processing"
                class="px-5 py-2 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors disabled:opacity-50"
              >
                {{ editingUser ? 'Enregistrer' : 'Créer' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { UserPlus, Pencil, Trash2, X, ShieldCheck, Users } from 'lucide-vue-next';

const props = defineProps({
  users: Array,
  roles: Object,
});

const showModal = ref(false);
const editingUser = ref(null);

const form = useForm({
  name: '',
  email: '',
  role: 'secretaire',
  password: '',
});

function roleColor(role) {
  return { admin: '#ef4444', secretaire: '#3b82f6', assistante: '#8b5cf6' }[role] || '#6b7280';
}

function openCreateModal() {
  editingUser.value = null;
  form.reset();
  form.clearErrors();
  showModal.value = true;
}

function openEditModal(user) {
  editingUser.value = user;
  form.name = user.name;
  form.email = user.email;
  form.role = user.role;
  form.password = '';
  form.clearErrors();
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  editingUser.value = null;
}

function submitForm() {
  if (editingUser.value) {
    form.put(`/admin/users/${editingUser.value.id}`, { onSuccess: () => closeModal(), preserveScroll: true });
  } else {
    form.post('/admin/users', { onSuccess: () => closeModal(), preserveScroll: true });
  }
}

function deleteUser(user) {
  if (confirm(`Supprimer l'utilisateur "${user.name}" ?`)) {
    router.delete(`/admin/users/${user.id}`, { preserveScroll: true });
  }
}
</script>
