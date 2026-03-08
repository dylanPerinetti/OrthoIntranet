<template>
  <div class="flex flex-col h-full bg-[#1a1f36] text-white">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 h-16 border-b border-white/[0.06]">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
        <Stethoscope class="w-4 h-4 text-white" :stroke-width="2" />
      </div>
      <div class="min-w-0">
        <h1 class="text-sm font-bold text-white truncate">OrthoIntranet</h1>
        <p class="text-[10px] text-gray-500 -mt-0.5">Pilotage IA</p>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
      <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500">Navigation</p>

      <button
        v-for="link in navLinks"
        :key="link.href"
        @click="$emit('navigate', link.href)"
        :class="[
          'w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group',
          isActive(link.href)
            ? 'bg-white/[0.08] text-white'
            : 'text-gray-400 hover:bg-white/[0.04] hover:text-gray-200',
        ]"
      >
        <component
          :is="iconMap[link.icon]"
          :class="[
            'w-[18px] h-[18px] flex-shrink-0',
            isActive(link.href) ? 'text-blue-400' : 'text-gray-500 group-hover:text-gray-400',
          ]"
          :stroke-width="1.8"
        />
        {{ link.label }}
        <div
          v-if="isActive(link.href)"
          class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"
        ></div>
      </button>

      <div class="!mt-6">
        <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500">Streams</p>
        <div class="space-y-0.5">
          <button
            @click="$emit('navigate', '/streams/operationnel')"
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:bg-white/[0.04] hover:text-gray-300 transition-colors"
          >
            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
            <span class="truncate">Opérationnel</span>
          </button>
          <button
            @click="$emit('navigate', '/streams/marketing')"
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:bg-white/[0.04] hover:text-gray-300 transition-colors"
          >
            <span class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0"></span>
            <span class="truncate">Marketing</span>
          </button>
          <button
            @click="$emit('navigate', '/streams/expertise-dr-z')"
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:bg-white/[0.04] hover:text-gray-300 transition-colors"
          >
            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
            <span class="truncate">Expertise Dr Z</span>
          </button>
        </div>
      </div>

      <div class="!mt-6">
        <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-gray-500">Gestion</p>
        <div class="space-y-0.5">
          <button
            @click="$emit('navigate', '/personas')"
            :class="[
              'w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-all',
              isActive('/personas')
                ? 'bg-white/[0.08] text-white'
                : 'text-gray-400 hover:bg-white/[0.04] hover:text-gray-200',
            ]"
          >
            <UserCircle2 :class="['w-[18px] h-[18px] flex-shrink-0', isActive('/personas') ? 'text-blue-400' : 'text-gray-500']" :stroke-width="1.8" />
            <span class="truncate">Persona Types</span>
          </button>
          <button
            @click="$emit('navigate', '/user-stories')"
            :class="[
              'w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-all',
              isActive('/user-stories')
                ? 'bg-white/[0.08] text-white'
                : 'text-gray-400 hover:bg-white/[0.04] hover:text-gray-200',
            ]"
          >
            <BookOpen :class="['w-[18px] h-[18px] flex-shrink-0', isActive('/user-stories') ? 'text-blue-400' : 'text-gray-500']" :stroke-width="1.8" />
            <span class="truncate">User Stories</span>
          </button>
        </div>
      </div>
    </nav>

    <!-- User section -->
    <div class="px-3 py-3 border-t border-white/[0.06]">
      <!-- Settings link -->
      <button
        @click="$emit('navigate', '/settings')"
        :class="[
          'w-full flex items-center gap-3 px-3 py-2 mb-2 rounded-lg text-[13px] font-medium transition-all',
          isActive('/settings')
            ? 'bg-white/[0.08] text-white'
            : 'text-gray-400 hover:bg-white/[0.04] hover:text-gray-200',
        ]"
      >
        <SettingsIcon :class="['w-[18px] h-[18px] flex-shrink-0', isActive('/settings') ? 'text-blue-400' : 'text-gray-500']" :stroke-width="1.8" />
        <span class="truncate">Réglages</span>
      </button>

      <div class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-white/[0.04] transition-colors group">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
          {{ auth.user.name.charAt(0).toUpperCase() }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] font-medium text-gray-200 truncate">{{ auth.user.name }}</p>
          <p class="text-[11px] text-gray-500 truncate">{{ auth.user.role_name }}</p>
        </div>
        <button
          @click="$emit('logout')"
          class="p-1.5 rounded-md text-gray-500 hover:text-red-400 hover:bg-white/[0.06] transition-colors opacity-0 group-hover:opacity-100"
          title="Déconnexion"
        >
          <LogOut class="w-3.5 h-3.5" :stroke-width="2" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Stethoscope, LogOut, LayoutDashboard, Kanban, ShieldCheck, Milestone, UserCircle2, BookOpen, Settings as SettingsIcon } from 'lucide-vue-next';

defineProps({
  auth: Object,
  navLinks: Array,
  isActive: Function,
});

defineEmits(['navigate', 'logout']);

const iconMap = {
  LayoutDashboard,
  Kanban,
  ShieldCheck,
  Milestone,
};
</script>
