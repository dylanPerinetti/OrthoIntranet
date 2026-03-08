<template>
  <GuestLayout>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Connexion</h2>
        <p class="text-sm text-slate-500 mt-1">Accédez à votre espace OrthoIntranet</p>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Adresse e-mail</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
            placeholder="dr.andre@cabinet.fr"
          />
          <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <!-- Password -->
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

        <!-- Remember -->
        <div class="flex items-center gap-2">
          <input
            id="remember"
            v-model="form.remember"
            type="checkbox"
            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
          />
          <label for="remember" class="text-sm text-slate-600">Se souvenir de moi</label>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-medium text-sm rounded-xl shadow-lg shadow-blue-200/50 hover:shadow-xl hover:shadow-blue-200/60 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="form.processing">Connexion en cours…</span>
          <span v-else>Se connecter</span>
        </button>
      </form>

      <p class="mt-6 text-center text-xs text-slate-400">
        Intranet réservé au personnel du cabinet.<br />
        Contactez votre administrateur pour obtenir un accès.
      </p>
    </div>
  </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
}
</script>
