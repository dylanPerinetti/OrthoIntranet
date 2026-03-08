<template>
  <AuthenticatedLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Persona Types</h1>
          <p class="text-sm text-gray-500 mt-0.5">Les profils utilisateurs du projet — "En tant que…"</p>
        </div>
        <button
          @click="openCreateModal()"
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors shadow-sm"
        >
          <UserPlus class="w-4 h-4" :stroke-width="2" />
          Nouveau Persona
        </button>
      </div>

      <!-- Persona Cards Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <div
          v-for="persona in personas"
          :key="persona.id"
          class="bg-white rounded-xl border border-gray-200/80 overflow-hidden hover:shadow-md hover:border-gray-300/80 transition-all duration-200 group"
        >
          <!-- Top accent bar -->
          <div class="h-1 w-full" :style="{ backgroundColor: persona.avatar_color }"></div>

          <div class="p-5">
            <!-- Header -->
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div
                  class="w-11 h-11 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                  :style="{ background: `linear-gradient(135deg, ${persona.avatar_color}, ${persona.avatar_color}cc)` }"
                >
                  {{ persona.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <h3 class="text-[15px] font-semibold text-gray-900">{{ persona.name }}</h3>
                  <p class="text-xs text-gray-500">{{ persona.role }}</p>
                </div>
              </div>
              <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                <button @click="openEditModal(persona)" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition-colors">
                  <Pencil class="w-3.5 h-3.5" :stroke-width="2" />
                </button>
                <button @click="deletePersona(persona)" class="p-1.5 rounded-md hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors">
                  <Trash2 class="w-3.5 h-3.5" :stroke-width="2" />
                </button>
              </div>
            </div>

            <!-- Description -->
            <p v-if="persona.description" class="text-[13px] text-gray-600 leading-relaxed mb-4 line-clamp-2">
              {{ persona.description }}
            </p>

            <!-- Details sections -->
            <div class="space-y-2.5">
              <div v-if="persona.goals" class="p-2.5 rounded-lg bg-emerald-50/60 border border-emerald-100/60">
                <div class="flex items-center gap-1.5 mb-1">
                  <Target class="w-3 h-3 text-emerald-600" :stroke-width="2" />
                  <span class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider">Objectifs</span>
                </div>
                <p class="text-[11px] text-emerald-800 leading-relaxed line-clamp-2">{{ persona.goals }}</p>
              </div>

              <div v-if="persona.pain_points" class="p-2.5 rounded-lg bg-red-50/60 border border-red-100/60">
                <div class="flex items-center gap-1.5 mb-1">
                  <AlertTriangle class="w-3 h-3 text-red-500" :stroke-width="2" />
                  <span class="text-[10px] font-semibold text-red-700 uppercase tracking-wider">Frustrations</span>
                </div>
                <p class="text-[11px] text-red-800 leading-relaxed line-clamp-2">{{ persona.pain_points }}</p>
              </div>

              <div v-if="persona.needs" class="p-2.5 rounded-lg bg-blue-50/60 border border-blue-100/60">
                <div class="flex items-center gap-1.5 mb-1">
                  <Lightbulb class="w-3 h-3 text-blue-600" :stroke-width="2" />
                  <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider">Besoins</span>
                </div>
                <p class="text-[11px] text-blue-800 leading-relaxed line-clamp-2">{{ persona.needs }}</p>
              </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
              <span
                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold"
                :style="{ backgroundColor: persona.avatar_color + '14', color: persona.avatar_color }"
              >
                <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: persona.avatar_color }"></span>
                {{ persona.role }}
              </span>
              <span class="text-[11px] text-gray-400 tabular-nums">
                {{ persona.user_stories_count }} stor{{ persona.user_stories_count === 1 ? 'y' : 'ies' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-if="!personas.length" class="bg-white rounded-xl border border-gray-200/80 flex flex-col items-center py-20 text-center">
        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mb-3">
          <UserCircle class="w-6 h-6 text-gray-400" :stroke-width="1.5" />
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Aucun persona défini</h3>
        <p class="text-xs text-gray-500 mb-4 max-w-xs">Les personas représentent les profils d'utilisateurs pour les User Stories.</p>
        <button @click="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors">
          <UserPlus class="w-4 h-4" :stroke-width="2" />
          Créer le premier persona
        </button>
      </div>
    </div>

    <!-- ═══════════════════════════════════════ -->
    <!-- MODAL CREATE / EDIT                     -->
    <!-- ═══════════════════════════════════════ -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4 pt-16 sm:pt-4">
        <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
          <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between rounded-t-xl z-10">
            <h3 class="text-base font-bold text-gray-900">
              {{ editingPersona ? 'Modifier le Persona' : 'Nouveau Persona' }}
            </h3>
            <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors">
              <X class="w-5 h-5" :stroke-width="2" />
            </button>
          </div>

          <form @submit.prevent="submitForm" class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Nom *</label>
                <input v-model="form.name" type="text" required
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                  placeholder="Dr André" />
                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Rôle *</label>
                <input v-model="form.role" type="text" required
                  class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
                  placeholder="Orthodontiste" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Description</label>
              <textarea v-model="form.description" rows="2"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="Décrivez ce persona…"></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                <span class="inline-flex items-center gap-1"><Target class="w-3 h-3 text-emerald-600" :stroke-width="2" /> Objectifs</span>
              </label>
              <textarea v-model="form.goals" rows="2"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="Que cherche ce persona à accomplir ?"></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                <span class="inline-flex items-center gap-1"><AlertTriangle class="w-3 h-3 text-red-500" :stroke-width="2" /> Frustrations</span>
              </label>
              <textarea v-model="form.pain_points" rows="2"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="Qu'est-ce qui frustre ce persona ?"></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                <span class="inline-flex items-center gap-1"><Lightbulb class="w-3 h-3 text-blue-600" :stroke-width="2" /> Besoins</span>
              </label>
              <textarea v-model="form.needs" rows="2"
                class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"
                placeholder="De quoi a besoin ce persona ?"></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Couleur</label>
              <div class="flex items-center gap-2">
                <input v-model="form.avatar_color" type="color"
                  class="w-9 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5" />
                <span class="text-xs text-gray-500 font-mono">{{ form.avatar_color }}</span>
              </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <button type="button" @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                Annuler
              </button>
              <button type="submit" :disabled="form.processing"
                class="px-5 py-2 bg-[#1a1f36] text-white text-sm font-medium rounded-lg hover:bg-[#252b45] transition-colors disabled:opacity-50">
                {{ editingPersona ? 'Enregistrer' : 'Créer' }}
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
import {
  UserPlus, Pencil, Trash2, X, UserCircle,
  Target, AlertTriangle, Lightbulb,
} from 'lucide-vue-next';

const props = defineProps({
  personas: Array,
});

const showModal = ref(false);
const editingPersona = ref(null);

const form = useForm({
  name: '',
  role: '',
  description: '',
  goals: '',
  pain_points: '',
  needs: '',
  avatar_color: '#3b82f6',
});

function openCreateModal() {
  editingPersona.value = null;
  form.reset();
  form.clearErrors();
  showModal.value = true;
}

function openEditModal(persona) {
  editingPersona.value = persona;
  form.name = persona.name;
  form.role = persona.role;
  form.description = persona.description || '';
  form.goals = persona.goals || '';
  form.pain_points = persona.pain_points || '';
  form.needs = persona.needs || '';
  form.avatar_color = persona.avatar_color || '#3b82f6';
  form.clearErrors();
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  editingPersona.value = null;
}

function submitForm() {
  if (editingPersona.value) {
    form.put(`/personas/${editingPersona.value.id}`, { onSuccess: () => closeModal(), preserveScroll: true });
  } else {
    form.post('/personas', { onSuccess: () => closeModal(), preserveScroll: true });
  }
}

function deletePersona(persona) {
  if (confirm(`Supprimer le persona "${persona.name}" ?`)) {
    router.delete(`/personas/${persona.id}`, { preserveScroll: true });
  }
}
</script>
