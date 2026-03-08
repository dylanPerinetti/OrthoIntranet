<template>
  <div class="min-h-screen flex bg-[#f8f9fb]">
    <!-- ══════════════════════════════════════════ -->
    <!-- SIDEBAR (desktop: fixed, mobile: overlay) -->
    <!-- ══════════════════════════════════════════ -->

    <!-- Mobile overlay -->
    <Transition name="sidebar">
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-50 lg:hidden"
      >
        <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false"></div>
        <aside class="relative w-72 h-full">
          <SidebarContent :auth="auth" :navLinks="navLinks" :isActive="isActive" @navigate="navigate" @logout="logout" />
        </aside>
      </div>
    </Transition>

    <!-- Desktop sidebar -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 xl:w-72 lg:fixed lg:inset-y-0 lg:z-30">
      <SidebarContent :auth="auth" :navLinks="navLinks" :isActive="isActive" @navigate="navigate" @logout="logout" />
    </aside>

    <!-- ══════════════════════════════════════════ -->
    <!-- MAIN CONTENT                               -->
    <!-- ══════════════════════════════════════════ -->
    <div class="flex-1 lg:pl-64 xl:pl-72 flex flex-col min-h-screen">
      <!-- Top bar (mobile + breadcrumb) -->
      <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200/60">
        <div class="flex items-center gap-4 px-4 sm:px-6 lg:px-8 h-16">
          <!-- Mobile menu button -->
          <button
            @click="sidebarOpen = true"
            class="lg:hidden -ml-1 p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
          >
            <Menu class="w-5 h-5" :stroke-width="2" />
          </button>

          <!-- Breadcrumb -->
          <div class="flex-1 flex items-center gap-2 text-sm">
            <span class="hidden sm:inline text-gray-400">OrthoIntranet</span>
            <ChevronRight class="hidden sm:block w-3.5 h-3.5 text-gray-300" :stroke-width="2" />
            <span class="font-medium text-gray-700">{{ currentPageTitle }}</span>
          </div>

          <!-- Top-right actions -->
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-medium border border-emerald-200/60">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              En ligne
            </span>

            <!-- Mobile avatar -->
            <div class="lg:hidden relative" ref="mobileMenuRef">
              <button
                @click="showMobileMenu = !showMobileMenu"
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-bold"
              >
                {{ auth.user.name.charAt(0).toUpperCase() }}
              </button>
              <div
                v-show="showMobileMenu"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 shadow-lg py-1 z-50"
              >
                <div class="px-3 py-2 border-b border-gray-100">
                  <p class="text-sm font-medium text-gray-900">{{ auth.user.name }}</p>
                  <p class="text-xs text-gray-500">{{ auth.user.role_name }}</p>
                </div>
                <button
                  @click="logout"
                  class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2"
                >
                  <LogOut class="w-3.5 h-3.5" :stroke-width="2" />
                  Déconnexion
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page content -->
      <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <slot />
      </main>

      <!-- Footer -->
      <footer class="px-4 sm:px-6 lg:px-8 py-4 border-t border-gray-200/60">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span>Système en ligne</span>
            <span class="text-gray-300">·</span>
            <span>v1.0 — Sprint 1</span>
          </div>
          <span>© {{ new Date().getFullYear() }} OrthoIntranet — Dr André</span>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Menu, ChevronRight, LogOut } from 'lucide-vue-next';
import SidebarContent from '@/Components/SidebarContent.vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const sidebarOpen = ref(false);
const showMobileMenu = ref(false);
const mobileMenuRef = ref(null);

const navLinks = computed(() => {
  const links = [
    { href: '/', label: 'Vue d\'ensemble', icon: 'LayoutDashboard' },
    { href: '/backlog', label: 'Backlog', icon: 'Kanban' },
  ];
  if (auth.value?.user?.role === 'admin') {
    links.push({ href: '/admin/users', label: 'Utilisateurs', icon: 'ShieldCheck' });
  }
  return links;
});

const currentPageTitle = computed(() => {
  const match = navLinks.value.find(l => isActive(l.href));
  if (match) return match.label;
  if (page.url.startsWith('/streams/')) {
    const slug = page.url.split('/streams/')[1]?.split('?')[0];
    const names = { operationnel: 'Opérationnel', marketing: 'Marketing', 'expertise-dr-z': 'Expertise Dr Z' };
    return names[slug] || 'Stream';
  }
  if (page.url.startsWith('/personas')) return 'Persona Types';
  if (page.url.startsWith('/user-stories')) return 'User Stories';
  return 'Page';
});

function isActive(href) {
  return page.url === href || (href !== '/' && page.url.startsWith(href));
}

function navigate(href) {
  sidebarOpen.value = false;
  router.visit(href);
}

function logout() {
  router.post('/logout');
}

function handleClickOutside(e) {
  if (mobileMenuRef.value && !mobileMenuRef.value.contains(e.target)) {
    showMobileMenu.value = false;
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside));
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside));
</script>
