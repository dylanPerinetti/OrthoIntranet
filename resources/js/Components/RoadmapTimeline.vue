<template>
  <div class="relative">
    <!-- Vertical line -->
    <div class="absolute left-6 top-0 bottom-0 w-px bg-gradient-to-b from-cyan-300 via-blue-400 to-indigo-500"></div>

    <div class="space-y-8">
      <div
        v-for="(step, index) in steps"
        :key="index"
        class="relative flex items-start gap-5 pl-2"
      >
        <!-- Circle with number -->
        <div
          :class="[
            'relative z-10 flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2',
            step.active
              ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200'
              : 'bg-white border-slate-300 text-slate-500',
          ]"
        >
          {{ index + 1 }}
        </div>

        <!-- Content -->
        <div :class="['flex-1 pb-2', step.active ? '' : 'opacity-80']">
          <div class="flex items-center gap-2 mb-1">
            <component :is="step.icon" class="w-4 h-4 text-slate-400" :stroke-width="2" />
            <h4 :class="['text-sm font-semibold', step.active ? 'text-slate-900' : 'text-slate-600']">
              {{ step.title }}
            </h4>
          </div>
          <p class="text-sm text-slate-500 leading-relaxed">{{ step.description }}</p>
          <span
            v-if="step.tag"
            class="inline-block mt-2 px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600"
          >
            {{ step.tag }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  steps: Array,
});
</script>
