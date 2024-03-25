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
</script>

<style scoped>
.stat__responses {
    background: linear-gradient(#E7F1FF, #E7F1FF) padding-box,
        radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%) border-box;
    border-right: 1px solid transparent;
    border-left: 1px solid transparent;
    padding: 5px;
    color: #156CB0;
    font-weight: 300;
}

.users__top {
    width: 100%;
    padding: 6px 0;
    background: linear-gradient(#E7F1FF, #E7F1FF) padding-box,
        radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%) border-box;
    border-bottom: 5px solid transparent;
}

.users__heading {
    font-weight: 700;
    font-size: 32px;
    color: #156CB0;
}
</style>

<template>
    <DashboardLayout>
        <div>
            <div class="users__top">
                <h2 class="users__heading">Оплата</h2>
            </div>
            <table v-if='props.users.length > 0' style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col" class="stat__responses">№</th>
                        <th scope="col" class="stat__responses">Компания</th>
                        <th scope="col" class="stat__responses">БИН/ИИН</th>
                        <th scope="col" class="stat__responses">Имя</th>
                        <th scope="col" class="stat__responses">Email</th>
                        <th scope="col" class="stat__responses">Телефон</th>
                        <th scope="col" class="stat__responses">Подписка</th>
                        <th scope="col" class="stat__responses">Период</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="" v-for='(user, index) in props.users'>
                        <th scope="row" class="stat__responses">{{ index }}</th>
                        <th  class="stat__responses">{{ user.company }}</th>
                        <th  class="stat__responses"><p>{{ user.bin }} <span v-if="user.bin || user.inn">/</span> {{ user.inn }}</p></th>
                        <th  class="stat__responses">{{ user.name }}</th>
                        <th  class="stat__responses">{{ user.email }}</th>
                        <th  class="stat__responses">{{ user.phone }}</th>
                        <th  class="stat__responses">{{ user.subscription_duration? 'Да' : 'Нет' }} </th>
                        <th  class="stat__responses">{{ user.subscription_duration }} </th>
                    </tr>
                </tbody>

            </table>

            <div v-else>
                Нет пользователей
            </div>

        </div>
    </DashboardLayout>
</template>
