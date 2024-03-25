<script setup lang="ts">
    import { ref, reactive } from 'vue';
    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { useCreateSurvey } from '@/Composables/useCreateSurvey';
    import vClickOutside from "click-outside-vue3";
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import Modal from '@/Components/Modal.vue';



    const closeModal = () => {
        isCreateSurvey.value = false;

        form.reset();
    };

    const props = defineProps({
      surveys: {
          type: Object
      },
    });
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

</script>

<template>
    <DashboardLayout>
        <div>
        <h3 class="text-3xl font-medium text-sky-700">
          Опрос
        </h3>
        <div v-if='props.surveys.length > 0'>

            <div сlass="" v-for='survey in props.surveys'>
                <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Заголовок:</h2> {{survey.name}}</div>
                <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Вопрос:</h2> {{survey.description}}</div>
                <div class="text-sky-400 text-sm"><h2 class="inline-block text-base text-sky-600">Ссылка на карту:</h2><a :href=survey.link> Перейти</a></div>
                <!-- <div>{{survey.created_at}}</div> -->
                <div class="text-sky-400 text-sm">
                    <Link class="flex items-center py-2 mt-4 duration-200 border-l-cyan-400" :href="route('survey.survey_answers', { id: survey.id })">
                        <span class="py-2 px-3 text-sky-100 bg-sky-700 rounded-xl">Cсылка</span>
                    </Link>
                </div>
                <div><!--Подробнее - ссылка на страницу с опросом - -->
                    <Link class="flex items-center py-2 mt-4 duration-200 border-l-cyan-400" :href="route('survey.show', { id: survey.id })">
                        <span class="text-sky-500">Подробнее</span>
                    </Link>
                </div>
            </div>
        </div>
        <div v-else>
            <div>
                <button class="text-lg text-sky-700" v-if='!isCreateSurvey' @click="isCreateSurvey = true">Создать опрос</button>
                <Modal :show="isCreateSurvey" @close="closeModal">
                    <div class="p-6">
                        <h3 class="text-lg text-sky-800">Создать опрос</h3>
                        <form @submit.prevent="submit">
                            <div class="mb-2">
                                <InputLabel for="survey_name" value="Название" />

                                <TextInput
                                    id="survey_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.survey_name"
                                    placeholder="Название компании и т.д."
                                    required

                                />

                                <InputError class="mt-2" :message="form.errors.survey_name" />
                            </div>
                            <div class="mb-2">
                                <InputLabel for="description" value="Описание" />

                                <TextInput
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.survey_description"
                                    placeholder="Как вам обслуживание?"
                                    required

                                />

                                <InputError class="mt-2" :message="form.errors.survey_description" />
                            </div>
                            <div class="mb-2">
                                <InputLabel for="link" value="Ссылка на сайт с отзывами" />

                                <TextInput
                                    id="link"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.survey_link"
                                    placeholder="https://2.gis/astana/7777/tab/reviews/addreview"
                                    required

                                />

                                <InputError class="mt-2" :message="form.errors.survey_link" />
                            </div>
                            <div class="mb-2">
                                <InputLabel for="questions_text" value="Вопрос" />
                                <TextInput
                                    id="questions_text"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.question"
                                    placeholder="Что вам не понравилось?"
                                    required

                                />
                                <div class="mb-3" v-for='count_answer_option in count_answer_options'>
                                    <InputLabel for="questions_text" value="Ответ" />
                                    <TextInput
                                        id="questions_text"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.answer_options[count_answer_option-1]"
                                        placeholder="Работа персонала"
                                        required

                                    />
                                </div>
                                <template v-if='form.answer_options.length - count_answer_options < 1'>
                                    <button  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click='count_answer_options += 1'>
                                        Добавить ответ
                                    </button >
                                </template>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ms-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Создать
                                </PrimaryButton>
                            </div>
                        </form>
                        <button class="" @click="isCreateSurvey = false">Отмена</button>
                    </div>
                </Modal>
            </div>
        </div>
    </div>
  </DashboardLayout>
</template>
