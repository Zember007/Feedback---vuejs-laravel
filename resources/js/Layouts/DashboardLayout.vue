<script setup lang="ts">
    import Sidebar from '@/Components/Dashboard/Sidebar.vue';
    import Header from '@/Components/Dashboard/Header.vue';

</script>

<style scoped>
.main-bg{
    background: radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%);
  }
</style>

<template>
  <div class="flex h-screen main-bg font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header/>

      <main class="flex-1 overflow-x-hidden overflow-y-auto" style="background: #E7F1FF;">
        <div class="container mx-auto px-6 py-8" style="height: 100%;">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>
