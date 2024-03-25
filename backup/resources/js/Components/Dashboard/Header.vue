<script setup lang="ts">
import { ref } from 'vue'
import { useSidebar } from '@/Composables/useSidebar'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const dropdownOpen = ref(false)
const { isOpen } = useSidebar()
</script>

<template>
  <header
    class="flex items-center justify-between px-6 py-4 bg-sky-400 border-b-4 border-b-cyan-200"
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
          class="relative z-10 block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none"
          @click="dropdownOpen = !dropdownOpen"
        >
          <!-- <img
            class="object-cover w-full h-full"
            src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=296&q=80"
            alt="Your avatar"
          > -->
            <svg class="object-cover" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" viewBox="0 0 100 100">
                <path d="M51.775 54.264C31.718 54.264 15.4 71.193 15.4 92a1.5 1.5 0 0 0 1.5 1.5h69.75a1.5 1.5 0 0 0 1.5-1.5c0-20.807-16.317-37.736-36.375-37.736zM18.431 90.5c.757-18.46 15.424-33.236 33.345-33.236 17.92 0 32.587 14.776 33.345 33.236h-66.69zm32.715-40.358c11.568 0 20.98-9.74 20.98-21.713S62.715 6.716 51.146 6.716s-20.979 9.74-20.979 21.713 9.411 21.713 20.979 21.713zm0-40.426c9.914 0 17.98 8.395 17.98 18.713s-8.066 18.713-17.98 18.713-17.979-8.395-17.979-18.713S41.232 9.716 51.146 9.716z"/>
            </svg>
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
