<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { useCreateSurvey } from '@/Composables/useCreateSurvey';
import vClickOutside from "click-outside-vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { useToast } from "vue-toastification";
import SurveyButton from '@/Components/SurveyButton.vue';
import DangerButtonSurvey from '@/Components/DangerButtonSurvey.vue';

const toast = useToast();

const closeModal = () => {
    isCreateSurvey.value = false;

    form.reset();
};

const props = defineProps({
    surveys: {
        type: Object
    },
});

const user = usePage().props.auth.user;

onMounted(() => {
    if (user.subscription_paymed) {
        const pay_inf = useForm({
            pg_payment_id: Number(user.subscription_paymed),
            pg_order_id: String(user.id),
            user_id: String(user.id)
        });
        pay_inf.post(route('subscription.result'));
        console.log(props.surveys);

    }


})


let { isCreateSurvey } = useCreateSurvey()
const count_answer_options = ref(1)
const form = useForm({
    survey_name: '',
    survey_description: '',
    survey_link: '',
    question: '',
    answer_options: ref([]),
});

const submit = () => {
    form.post(route('survey.create'), {
        onFinish: () => form.reset(),
    });
    isCreateSurvey = false;
};

const SurveyDelete = () => {
    useForm([]).delete(route('survey.delete'));
}


const copylink = (link) => {
    navigator.clipboard.writeText(link)
        .then(() => toast.success("Скопировано!"))
}

</script>

<style scoped lang="scss">
.Error_subscription {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    font-weight: 700;
}

.SurveyForm {
    max-width: 600px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.Link_hint {
    color: #156CB0;
    font-size: 25px;
    position: absolute;
    bottom: 0;
    right: 0;
    transform: translateX(120%);

    &:hover {
        text-decoration: underline;
    }
}

.survey_description {
    color: #156CB0;
    font-weight: 300;
    font-size: 19px;
}
</style>

<template>
    <DashboardLayout>
        <div style="height: 100%;" class="flex flex-col gap-4"
            v-if='(user.subscription && user.subscription_duration > 0) || user.role <= 1'>
            <h3 v-if='!isCreateSurvey' class="text-3xl font-medium text-sky-700">
                Опрос
            </h3>
            <h3 v-else class="text-3xl font-medium text-sky-700">Создание опроса</h3>
            <div v-if='props.surveys.length > 0' class="flex-1 flex flex-col justify-between"
                v-for='survey in props.surveys'>

                <div class="flex flex-col gap-3">
                    <div class="survey_description">Заголовок: {{ survey.name }}</div>
                    <div class="survey_description">Вопрос: {{ survey.description }}</div>
                    <div class="survey_description">Ссылка на карту: <a :href="survey.link"
                            style="text-decoration: underline;">Перейти</a></div>
                    <SurveyButton>
                        <Link :href="route('survey.survey_answers', { id: survey.id })">
                        Посмотреть опрос
                        </Link>
                    </SurveyButton>
                    <div class="flex gap-4 items-center">
                        <SurveyButton @click.prevent="copylink(route('survey.survey_answers', { id: survey.id }))">
                            Скопировать ссылку
                        </SurveyButton>
                        <div class="survey_description">Начните делать рассылку этой ссылки вашим клиентам.</div>
                    </div>
                </div>

                <div class="flex gap-4 items-center">
                    <DangerButtonSurvey @click="SurveyDelete()">Удалить опрос</DangerButtonSurvey>
                    <div class="survey_description">Внимание: при удалении опроса, так же удаляется вся собранная статистика</div>
                </div>
            </div>

            <div v-else>
                <div>
                    <SurveyButton v-if='!isCreateSurvey' @click="isCreateSurvey = true">Создать опрос</SurveyButton>
                    <div v-if="isCreateSurvey" @close="closeModal">
                        <form class="SurveyForm">
                            <div class="mb-2">
                                <InputLabel for="survey_name" value="Название компании" />

                                <TextInput id="survey_name" type="text" class="mt-1 block w-full"
                                    v-model="form.survey_name" required />

                                <InputError class="mt-2" :message="form.errors.survey_name" />
                            </div>
                            <div class="mb-2">
                                <InputLabel for="description" value="Вопрос для оценки" />

                                <TextInput id="description" type="text" class="mt-1 block w-full"
                                    v-model="form.survey_description" placeholder="Оцените качество обслуживания"
                                    required />

                                <InputError class="mt-2" :message="form.errors.survey_description" />
                            </div>
                            <div class="mb-2" style="position:relative">
                                <a href="#" class="Link_hint">Где взять ссылку?</a>

                                <InputLabel for="link" value="Ссылка на профиль для положительных отзывов" />

                                <TextInput id="link" type="text" class="mt-1 block w-full" v-model="form.survey_link"
                                    required />

                                <InputError class="mt-2" :message="form.errors.survey_link" />
                            </div>
                            <div class="mb-2">
                                <InputLabel for="questions_text" value="Вопрос при негативном/нейтральном голосе" />
                                <TextInput id="questions_text" type="text" class="mt-1 block w-full"
                                    v-model="form.question" placeholder="Что вам не понравилось?" required />
                                <div class="mb-3" v-for='(count_answer_option, index) in count_answer_options'>
                                    <InputLabel for="questions_text" :value="`Ответ ${index + 1}`" />
                                    <TextInput id="questions_text" type="text" class="mt-1 block w-full"
                                        v-model="form.answer_options[count_answer_option - 1]"
                                        placeholder="Работа персонала" required />
                                </div>
                                <template v-if='form.answer_options.length - count_answer_options < 1'>
                                    <SurveyButton @click='count_answer_options += 1'>
                                        Добавить ответ
                                    </SurveyButton>
                                </template>
                            </div>
                            <div class="flex gap-4 justify-between mt-4">
                                <SurveyButton @click="isCreateSurvey = false">Отмена</SurveyButton>
                                <SurveyButton @click.prevent="submit" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">Создать
                                </SurveyButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="Error_subscription">
            Ваша подписка неактивна
        </div>
    </DashboardLayout>
</template>
