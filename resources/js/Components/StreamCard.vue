<template>
  <div class="group bg-white rounded-xl border border-gray-200/80 overflow-hidden hover:shadow-md hover:border-gray-300/80 transition-all duration-200">
    <!-- Top accent bar -->
    <div class="h-1 w-full" :style="{ backgroundColor: accentColor }"></div>

    <div class="p-5">
      <!-- Header -->
      <div class="flex items-start gap-3.5 mb-3">
        <div
          class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
          :style="{ backgroundColor: accentColor + '14' }"
        >
          <component
            :is="icon"
            class="w-5 h-5"
            :style="{ color: accentColor }"
            :stroke-width="1.8"
          />
        </div>
        <div class="min-w-0">
          <h3 class="text-[15px] font-semibold text-gray-900">{{ title }}</h3>
          <p class="text-xs text-gray-500 mt-0.5">{{ subtitle }}</p>
        </div>
      </div>

      <!-- Description -->
      <p class="text-[13px] text-gray-600 leading-relaxed mb-4">{{ description }}</p>

      <!-- Features -->
      <ul class="space-y-2">
        <li
          v-for="(feature, index) in features"
          :key="index"
          class="flex items-start gap-2.5 text-[13px] text-gray-700"
        >
          <div
            class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center mt-px"
            :style="{ backgroundColor: accentColor + '14' }"
          >
            <CheckCircle2
              class="w-3 h-3"
              :style="{ color: accentColor }"
              :stroke-width="2"
            />
          </div>
          {{ feature }}
        </li>
      </ul>

      <!-- Status -->
      <div class="mt-4 pt-3 border-t border-gray-100">
        <span
          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium"
          :style="{ backgroundColor: accentColor + '10', color: accentColor }"
        >
          <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: accentColor }"></span>
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
  slug: String,
  accentColor: { type: String, default: '#3b82f6' },
});
</script>
