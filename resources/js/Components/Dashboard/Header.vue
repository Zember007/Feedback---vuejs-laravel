<script setup lang="ts">
import { ref } from 'vue'
import { useSidebar } from '@/Composables/useSidebar'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { usePage } from '@inertiajs/vue3';

const dropdownOpen = ref(false)
const { isOpen } = useSidebar()

const user = usePage().props.auth.user;
</script>

<style scoped>

</style>

<template>
  <header
    class="flex items-center justify-between px-6 py-12 border-b-4 border-b-cyan-200"
  >
    <div class="flex items-center">
      <button
        class="text-gray-500 focus:outline-none lg:hidden"
        @click="isOpen = true"
      >
        <svg
          class="w-6 h-6"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M4 6H20M4 12H20M4 18H11"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button>


    </div>

    <div class="flex items-center">
      <!-- <button class="flex mx-4 text-gray-600 focus:outline-none">
        <svg
          class="w-6 h-6"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button> -->

      <div class="relative">
        <button
          class="flex relative z-10 block gap-4 text-white text-2xl items-center rounded-full focus:outline-none"
          @click="dropdownOpen = !dropdownOpen"
        >
        <span>{{ user.email }}</span>
        <img src="../../../img/user_icon.webp" alt="user_icon" width="60" height="60">
        </button>

        <div
          v-show="dropdownOpen"
          class="fixed inset-0 z-10 w-full h-full"
          @click="dropdownOpen = false"
        />

        <transition
          enter-active-class="transition duration-150 ease-out transform"
          enter-from-class="scale-95 opacity-0"
          enter-to-class="scale-100 opacity-100"
          leave-active-class="transition duration-150 ease-in transform"
          leave-from-class="scale-100 opacity-100"
          leave-to-class="scale-95 opacity-0"
        >
          <div
            v-show="dropdownOpen"
            class="absolute right-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl"
          >
            <ResponsiveNavLink :href="route('profile.edit')" :class="[route().current() === 'profile.edit' ? activeClass : inactiveClass]"> Профиль </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Выход</ResponsiveNavLink>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>
