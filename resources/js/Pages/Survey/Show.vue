<script setup lang="ts">
import { ref, reactive } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";
import SurveyButton from '@/Components/SurveyButton.vue';
import DangerButtonSurvey from '@/Components/DangerButton.vue';

const toast = useToast();

const closeModal = () => {
    isAddQuestions.value = false;
    form.reset();
};

const count_answer_options = ref(1)

const props = defineProps({
    survey: {
        type: Object
    },
    questions: {
        type: Object
    },
});

const SurveyDelete = () => {
    useForm().delete(route('survey.delete'));
}

const copylink = (link) => {
    navigator.clipboard.writeText(link)
        .then(() => toast.success("Скопировано!"))
}

</script>

<template>
    <DashboardLayout>
        <div class="flex flex-col justify-between">
            
            <div class="flex flex-col gap-3">
                <h1 class="text-3xl font-medium text-sky-700">
                Опрос
                </h1>
                <div class="survey_description">Заголовок: {{ survey.name }}</div>
                <div class="survey_description">Вопрос: {{ survey.description }}</div>
                <div class="survey_description">Ссылка на карту: <a :href="survey.link" style="text-decoration: underline;">Перейти</a></div>
                <SurveyButton>
                    <Link :href="route('survey.survey_answers', { id: survey.id })">
                        Посмотреть опрос
                    </Link>
                </SurveyButton>
                <SurveyButton @click.prevent="copylink(route('survey.survey_answers', { id: survey.id }))">
                        Скопировать ссылку
                </SurveyButton>
            </div>

            <DangerButtonSurvey @click="SurveyDelete()">Удалить опрос</DangerButtonSurvey>
        </div>
    </DashboardLayout>
</template>

<style lang="scss" scoped>

.survey_description{
    color: #156CB0;
    font-weight: 500;
}

</style>
