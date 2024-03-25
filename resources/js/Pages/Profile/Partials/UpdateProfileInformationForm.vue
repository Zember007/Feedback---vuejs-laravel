<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone,
    company: user.company,
    bin: user.bin,
    inn: user.inn,
});
</script>

<style scoped>
.Title-form{
    color: #156CB0;
    font-size: 40px;
    font-weight: 600;
}

</style>

<template>
    <section>
        <header>
            <h2 class="Title-form">Личные данные</h2>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-10 space-y-6">
            <div class="flex flex-col gap-2">
                <InputLabel for="name" value="Имя" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!--Здесь желательно отображение телефона в поле для ввода-->
            <div class="flex flex-col gap-2">
                <InputLabel for="phone" value="Телефон" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="flex flex-col gap-2">
                <InputLabel for="company" value="Компания" />

                <TextInput
                    id="company"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.company"
                    required
                    autocomplete="company"
                />

                <InputError class="mt-2" :message="form.errors.company" />
            </div>

            <div class="flex flex-col gap-2">
                <InputLabel for="bin" value="БИН" />

                <TextInput
                    id="bin"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.bin"
                    autocomplete="bin"
                />

                <InputError class="mt-2" :message="form.errors.bin" />
            </div>

            <div class="flex flex-col gap-2">
                <InputLabel for="inn" value="ИНН" />

                <TextInput
                    id="inn"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.inn"
                    autocomplete="inn"
                />

                <InputError class="mt-2" :message="form.errors.inn" />
            </div>

            <div class="flex flex-col gap-2">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-sky-500">
                    Ваш email не подтвержден
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-sky-600 hover:text-sky-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Получить вериикационное письмо повторно.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Новое письмо было отправленно на ваш email.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Сохранено.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
