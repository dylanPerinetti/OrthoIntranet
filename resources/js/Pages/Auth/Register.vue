<template>
  <GuestLayout>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Créer un compte</h2>
        <p class="text-sm text-slate-500 mt-1">Rejoignez l'équipe OrthoIntranet</p>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Nom -->
        <div>
          <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
            placeholder="Marie Dupont"
          />
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Adresse e-mail</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
            placeholder="marie@cabinet.fr"
          />
          <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <!-- Rôle -->
        <div>
          <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">Rôle</label>
          <select
            id="role"
            v-model="form.role"
            required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors bg-white"
          >
            <option value="" disabled>Sélectionner un rôle</option>
            <option v-for="role in roles" :key="role.value" :value="role.value">
              {{ role.label }}
            </option>
          </select>
          <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</p>
        </div>

        <!-- Mot de passe -->
        <div>
          <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
            placeholder="••••••••"
          />
          <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <!-- Confirmation -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirmer le mot de passe</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
            placeholder="••••••••"
          />
        </div>

        <!-- Submit -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-medium text-sm rounded-xl shadow-lg shadow-blue-200/50 hover:shadow-xl hover:shadow-blue-200/60 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="form.processing">Création en cours…</span>
          <span v-else>Créer mon compte</span>
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-slate-500">
        Déjà un compte ?
        <a href="/login" class="text-blue-600 font-medium hover:underline" @click.prevent="$inertia.visit('/login')">
          Se connecter
        </a>
      </p>
    </div>
  </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
  roles: Array,
});

const form = useForm({
  name: '',
  email: '',
  role: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
}
</script>
