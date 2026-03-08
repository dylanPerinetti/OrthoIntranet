<template>
  <div class="bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 rounded-2xl border border-slate-700/50 p-8 relative overflow-hidden">
    <!-- Decorative glow -->
    <div class="absolute -top-20 -right-20 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-cyan-500/10 rounded-full blur-3xl"></div>

    <div class="relative">
      <!-- Header -->
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
          <Kanban class="w-5 h-5 text-blue-400" :stroke-width="1.8" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-white">Backlog Interactif</h3>
          <p class="text-sm text-slate-400">Prochainement sur cet Intranet</p>
        </div>
      </div>

      <!-- Placeholder cards -->
      <div class="space-y-3 mb-6">
        <div
          v-for="i in 3"
          :key="i"
          class="flex items-center gap-3 bg-slate-800/60 backdrop-blur-sm rounded-xl p-4 border border-slate-700/40"
        >
          <div class="w-2 h-2 rounded-full bg-blue-400/60 animate-pulse"></div>
          <div class="flex-1 space-y-2">
            <div :class="['h-3 rounded-full bg-slate-700/60', i === 1 ? 'w-3/4' : i === 2 ? 'w-1/2' : 'w-2/3']"></div>
            <div :class="['h-2 rounded-full bg-slate-700/40', i === 1 ? 'w-1/2' : i === 2 ? 'w-1/3' : 'w-2/5']"></div>
          </div>
          <div class="flex-shrink-0 px-2 py-1 rounded-md bg-slate-700/40 text-xs text-slate-500">
            {{ ['Sprint 1', 'Sprint 2', 'Sprint 3'][i - 1] }}
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="flex items-center justify-between p-4 bg-blue-500/10 rounded-xl border border-blue-500/20">
        <div class="flex items-center gap-2">
          <PlusCircle class="w-5 h-5 text-blue-400" :stroke-width="1.8" />
          <span class="text-sm text-blue-300 font-medium">Le Dr André pourra ajouter ses propres User Stories ici</span>
        </div>
        <ArrowRight class="w-4 h-4 text-blue-400" :stroke-width="2" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Kanban, PlusCircle, ArrowRight } from 'lucide-vue-next';
</script>
