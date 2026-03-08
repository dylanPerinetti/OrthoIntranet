<template>
  <AuthenticatedLayout>
    <div class="space-y-8 max-w-3xl">

      <!-- Page header -->
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Réglages</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Personnalisez votre expérience OrthoIntranet</p>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- APPARENCE                                -->
      <!-- ═══════════════════════════════════════ -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-5">
          <Palette class="w-4 h-4 text-gray-400 dark:text-gray-500" :stroke-width="2" />
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Apparence</h3>
        </div>

        <div class="space-y-4">
          <p class="text-sm text-gray-600 dark:text-gray-400">Choisissez le thème de l'interface.</p>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <!-- Light -->
            <button
              @click="setTheme('light')"
              :class="[
                'relative flex flex-col items-center gap-3 p-4 rounded-xl border-2 transition-all',
                theme === 'light'
                  ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-900/20'
                  : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500',
              ]"
            >
              <div class="w-full h-20 rounded-lg bg-white border border-gray-200 overflow-hidden">
                <div class="h-2 bg-[#1a1f36]"></div>
                <div class="flex h-[calc(100%-0.5rem)]">
                  <div class="w-6 bg-[#1a1f36]"></div>
                  <div class="flex-1 p-1.5 space-y-1">
                    <div class="h-1.5 w-10 bg-gray-200 rounded"></div>
                    <div class="h-1.5 w-8 bg-gray-100 rounded"></div>
                    <div class="h-1.5 w-12 bg-gray-100 rounded"></div>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <Sun class="w-4 h-4 text-amber-500" :stroke-width="2" />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Clair</span>
              </div>
              <div v-if="theme === 'light'" class="absolute top-2 right-2">
                <CheckCircle2 class="w-5 h-5 text-blue-500" :stroke-width="2" />
              </div>
            </button>

            <!-- Dark -->
            <button
              @click="setTheme('dark')"
              :class="[
                'relative flex flex-col items-center gap-3 p-4 rounded-xl border-2 transition-all',
                theme === 'dark'
                  ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-900/20'
                  : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500',
              ]"
            >
              <div class="w-full h-20 rounded-lg bg-gray-900 border border-gray-700 overflow-hidden">
                <div class="h-2 bg-gray-950"></div>
                <div class="flex h-[calc(100%-0.5rem)]">
                  <div class="w-6 bg-gray-950"></div>
                  <div class="flex-1 p-1.5 space-y-1">
                    <div class="h-1.5 w-10 bg-gray-700 rounded"></div>
                    <div class="h-1.5 w-8 bg-gray-800 rounded"></div>
                    <div class="h-1.5 w-12 bg-gray-800 rounded"></div>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <Moon class="w-4 h-4 text-indigo-400" :stroke-width="2" />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sombre</span>
              </div>
              <div v-if="theme === 'dark'" class="absolute top-2 right-2">
                <CheckCircle2 class="w-5 h-5 text-blue-500" :stroke-width="2" />
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- PROFIL                                   -->
      <!-- ═══════════════════════════════════════ -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-5">
          <UserCircle class="w-4 h-4 text-gray-400 dark:text-gray-500" :stroke-width="2" />
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Profil</h3>
        </div>

        <div class="flex items-center gap-4 mb-6">
          <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-lg font-bold">
            {{ user.name.charAt(0).toUpperCase() }}
          </div>
          <div>
            <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ user.name }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
            <span :class="[
              'inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-[11px] font-medium',
              user.role === 'admin' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' :
              user.role === 'secretaire' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' :
              'bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
            ]">
              {{ { admin: 'Administrateur', secretaire: 'Secrétaire', assistante: 'Assistante' }[user.role] }}
            </span>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════ -->
      <!-- INFORMATIONS SYSTÈME                     -->
      <!-- ═══════════════════════════════════════ -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200/80 dark:border-gray-700 p-5 sm:p-6">
        <div class="flex items-center gap-2 mb-5">
          <Info class="w-4 h-4 text-gray-400 dark:text-gray-500" :stroke-width="2" />
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Informations système</h3>
        </div>

        <div class="space-y-3">
          <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm text-gray-500 dark:text-gray-400">Version</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">v1.0.0</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm text-gray-500 dark:text-gray-400">Framework</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Laravel 12 + Vue 3</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm text-gray-500 dark:text-gray-400">Base de données</span>
            <span class="text-sm font-medium text-gray-900 dark:text-white">SQLite</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">Environnement</span>
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded text-xs font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              Local
            </span>
          </div>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {
  Palette, Sun, Moon, CheckCircle2,
  UserCircle, Info,
} from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useTheme } from '@/composables/useTheme.js';

const page = usePage();
const user = computed(() => page.props.auth.user);

const { theme, set: setTheme } = useTheme();
</script>
