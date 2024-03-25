<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<style scoped>
.Del_text-title{
    color: #DA2F47;
    font-size: 26px;
    font-weight: 700;
}

.Del_text-desc{
    color: #156CB0;
    font-size: 15px;
}
</style>

<template>
    <section class="mt-10 space-y-6">
        <header>
            <h2 class="Del_text-title">Удалить профиль</h2>

            <p class="Del_text-desc">
                После удаления аккаунта, все данные будут полностью удалены. Перед удалением аккаунта, сохраните все данные, которые вам необходимы.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Удалить</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Вы уверены, что хотите удалить аккаунт?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    После удаления аккаунта, все данные будут полностью удалены. Перед удалением аккаунта, сохраните все данные, которые вам необходимы.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Выйти </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                    Удалить
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
