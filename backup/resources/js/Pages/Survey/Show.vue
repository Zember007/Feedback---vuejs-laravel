<script setup lang="ts">
    import { ref, reactive } from 'vue';
    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';




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
</script>

<template>
    <DashboardLayout>
        <div>
        <h3 class="text-3xl font-medium text-sky-700">
          Опрос
        </h3>
        <div>
            <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Заголовок:</h2> {{survey.name}}</div>
            <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Вопрос:</h2> {{survey.description}}</div>
            <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Ссылка на карту:</h2> {{survey.link}}</div>
            <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Дата создания опроса:</h2> {{survey.created_at}}</div>
            <div class="text-sky-600 text-base">Ссылка на опрос для прохождения:
                <Link class="flex items-center py-2 mt-4 duration-200 border-l-cyan-400" :href="route('survey.survey_answers', { id: survey.id })">
                    <span class="py-2 px-3 text-sky-100 bg-sky-700 rounded-xl">Ссылка</span>
                </Link>
            </div>

            <div v-for='question in props.questions'>
                <div class="text-base text-sky-600">Вопрос - {{question.text}}</div>
                <div v-for='answer_option in question.answer_options'>
                    <div class="text-sky-400">Ответ - {{answer_option.text}}</div>
                </div>
            </div>
        </div>
    </div>
  </DashboardLayout>
</template>
