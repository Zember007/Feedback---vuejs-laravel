<script setup lang="ts">
    import { ref } from 'vue';
    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import vClickOutside from "click-outside-vue3"
    const props = defineProps({
      users: {
          type: Object
      },
    });

    console.log(props.users);
</script>

<template>
    <DashboardLayout>
        <div>
        <h3 class="text-3xl font-medium text-sky-700">
          Пользователи
        </h3>
        <div class="text-sky-600 text-lg" v-if='props.users.length > 0'>
            Вывод пользователей
            <div class="grid grid-cols-6">
                <h4 class="text-sky-500 text-xl outline outline-sky-400">Компания</h4>
                <h4 class="text-sky-500 text-xl outline outline-sky-400">БИН/ИИН</h4>
                <h4 class="text-sky-500 text-xl text-center outline outline-sky-400">Имя</h4>
                <h4 class="text-sky-500 text-xl text-center outline outline-sky-400">Email</h4>
                <h4 class="text-sky-500 text-xl text-center outline outline-sky-400">Телефон</h4>
                <h4 class="text-sky-500 text-xl text-center outline outline-sky-400">Подписка</h4>
            </div>

            <div class="grid grid-cols-6" v-for='user in props.users'>
                <div class="text-base text-center outline outline-sky-400">{{ user.company }}</div>
                <div class="text-base text-center outline outline-sky-400">
                    <p>{{ user.bin }}</p>
                    <p>{{ user.inn }}</p>
                </div>
                <div class="text-base text-center outline outline-sky-400">{{user.name}}</div>
                <div class="text-base text-center outline outline-sky-400">{{user.email}}</div>
                <div class="text-base text-center outline outline-sky-400">{{user.phone}}</div>
                <!-- <div>{{user.name}}</div> -->
                <div class="text-base text-center outline outline-sky-400"><!--Дата по ссылке--></div>
            </div>
        </div>
        <div v-else>
            Нет пользователей
        </div>
    </div>
  </DashboardLayout>
</template>
