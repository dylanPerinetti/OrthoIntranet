<template>
  <div class="min-h-screen bg-[#f8f9fb] flex">
    <!-- Left decorative panel (hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] bg-[#1a1f36] relative overflow-hidden items-center justify-center">
      <!-- Decorative grid -->
      <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm-2 2l-2 2h4l-2-2z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

      <!-- Content -->
      <div class="relative z-10 max-w-md px-12 text-center">
        <div class="w-16 h-16 mx-auto mb-8 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-xl">
          <Stethoscope class="w-8 h-8 text-white" :stroke-width="1.5" />
        </div>
        <h1 class="text-3xl font-bold text-white mb-3 tracking-tight">OrthoIntranet</h1>
        <p class="text-gray-400 text-base leading-relaxed mb-8">
          Plateforme de pilotage IA pour le cabinet d'orthodontie du Dr André.
        </p>
        <div class="flex items-center justify-center gap-6 text-sm text-gray-500">
          <div class="flex items-center gap-2">
            <Shield class="w-4 h-4 text-indigo-400" :stroke-width="2" />
            <span>Sécurisé</span>
          </div>
          <div class="flex items-center gap-2">
            <Zap class="w-4 h-4 text-indigo-400" :stroke-width="2" />
            <span>Temps réel</span>
          </div>
          <div class="flex items-center gap-2">
            <BrainCircuit class="w-4 h-4 text-indigo-400" :stroke-width="2" />
            <span>IA intégrée</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right form panel -->
    <div class="flex-1 flex flex-col items-center justify-center px-4 sm:px-8 py-12">
      <!-- Mobile logo -->
      <div class="lg:hidden mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
          <Stethoscope class="w-5 h-5 text-white" :stroke-width="2" />
        </div>
        <div>
          <h1 class="text-lg font-bold text-gray-900 tracking-tight">OrthoIntranet</h1>
          <p class="text-xs text-gray-400 -mt-0.5">Plateforme de Pilotage IA</p>
        </div>
      </div>

      <div class="w-full max-w-sm">
        <slot />
      </div>

      <p class="mt-10 text-xs text-gray-400">
        © {{ new Date().getFullYear() }} OrthoIntranet — Dr André
      </p>
    </div>
  </div>
</template>

<script setup>
import { Stethoscope, Shield, Zap, BrainCircuit } from 'lucide-vue-next';
</script>
