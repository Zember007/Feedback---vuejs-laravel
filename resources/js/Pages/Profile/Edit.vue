<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<style scoped>
.edit-form{
    position: relative;
}
.edit-form::before{
    content: "";
    display: block;
    position: absolute;
    height: 5px;
    left: 0;
    right: 0;
    top: 70px;
    background: radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%);
}
</style>

<template>

    <Head title="Profile" />

    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-cyan-800 leading-tight">Профиль</h2>
        </template>

        <div class="edit-form flex justify-between">
            <div class="w-full">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
            </div>

            <div class="w-full flex flex-col gap-6">
                <UpdatePasswordForm class="max-w-xl" />
                <DeleteUserForm class="max-w-xl" />
            </div>

        </div>
    </DashboardLayout>
</template>
