<template>
  <div
    class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-lg hover:border-slate-300 transition-all duration-300 overflow-hidden"
  >
    <!-- Colored top accent -->
    <div :class="['h-1.5 w-full', accentClass]"></div>

    <div class="p-6">
      <!-- Header -->
      <div class="flex items-start gap-4 mb-4">
        <div :class="['flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center', iconBgClass]">
          <component :is="icon" :class="['w-6 h-6', iconColorClass]" :stroke-width="1.8" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-900">{{ title }}</h3>
          <p class="text-sm text-slate-500 mt-0.5">{{ subtitle }}</p>
        </div>
      </div>

      <!-- Description -->
      <p class="text-sm text-slate-600 leading-relaxed mb-5">{{ description }}</p>

      <!-- Features list -->
      <ul class="space-y-2.5">
        <li
          v-for="(feature, index) in features"
          :key="index"
          class="flex items-center gap-3 text-sm text-slate-700"
        >
          <div :class="['flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center', dotBgClass]">
            <component :is="featureIcon || CheckCircle2" :class="['w-3.5 h-3.5', dotColorClass]" :stroke-width="2" />
          </div>
          {{ feature }}
        </li>
      </ul>

      <!-- Status badge -->
      <div class="mt-5 flex items-center gap-2">
        <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium', badgeClass]">
          <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
          {{ status }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckCircle2 } from 'lucide-vue-next';

defineProps({
  title: String,
  subtitle: String,
  description: String,
  features: Array,
  status: String,
  icon: [Object, Function],
  featureIcon: [Object, Function],
  accentClass: { type: String, default: 'bg-gradient-to-r from-blue-500 to-cyan-500' },
  iconBgClass: { type: String, default: 'bg-blue-50' },
  iconColorClass: { type: String, default: 'text-blue-600' },
  dotBgClass: { type: String, default: 'bg-blue-50' },
  dotColorClass: { type: String, default: 'text-blue-500' },
  badgeClass: { type: String, default: 'bg-blue-50 text-blue-700' },
});
</script>
