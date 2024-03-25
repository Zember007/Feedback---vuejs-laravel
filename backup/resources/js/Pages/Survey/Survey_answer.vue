<script setup lang="ts">
    import { ref, reactive } from 'vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import axios from 'axios';

    const props = defineProps({
      survey: {
          type: Object
      },
      questions: {
          type: Object
      },
    });
    const questions = ref(props.questions);
    const questions_length = ref(0);
    const next_question_1_add = ref(false);
    const next_question_2_add = ref(false);
    const next_question_3_add = ref(false);
    const question_status = ref(null);
    const link_open = ref(true);
    const form = useForm({
        question_id: '',
        answers: ref([]),
        comment: '',
        phone: '',
        name: ''
    });


    const next_question = () => {
            form.post(route('survey.answers'), {
                onFinish: () => form.reset(),
            });
        };

    const next_question_1 = () => {
        next_question_1_add.value = true;
        link_open.value = false;
        question_status.value = 1;
    }
    const next_question_2 = () => {
        next_question_2_add.value = true;
        link_open.value = false;
        question_status.value = 2;
    }

    const saved_end = () => {
        axios({
          method: 'post',
          url: '/api/answers_1',
              data: {
                question_id: props.questions[questions_length.value].id,
                comment: form.comment,
                answers: form.answers,
                phone: form.phone,
                name: form.name,
                status: question_status.value,
              }
            })
            .then((response) => {

              })
        /*Отправить по ссылке*/
        window.location.href = 'https://feedback.kz/';
    }
    const next_question_3 = () => {
        form.question_id = props.questions[0].id;
        axios({
          method: 'post',
          url: '/api/answers_3',
          data: {
            question_id: form.question_id,
          }
        })
        window.location.href = props.survey.link;
    };

</script>

<template>
    <div>
        <div>
        <h1 class="pt-2 mb-8 text-center text-6xl text-sky-800">
            {{props.survey.name}}  <!-- Имя опроса-->
        </h1>

        <div v-if='next_question_1_add || next_question_2_add'>

                <form @submit.prevent="next_question">
                    <h2 class="mb-7 text-center text-3xl text-sky-700">{{props.questions[questions_length].text}}</h2>
                    <div v-if='next_question_1_add || next_question_2_add'>

                        <div id="answers"  v-for='answer in props.questions[questions_length].answer_options'>
                          <input type="checkbox" :id="answer.id" :value="answer.id" v-model="form.answers" />
                          <label :for="answer.id">{{answer.text}}</label>
                          <br />

                        </div>
                        <div>
                            <textarea type="text" class="input" placeholder="Оставьте комментарий" v-model="form.comment"></textarea>
                        </div>
                        <div>
                            <InputLabel for="name" value="ФИО" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required

                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="phone" value="Ссылка на сайт с отзывами" />
                            <TextInput
                                id="phone"
                                type="tel"
                                class="mt-1 block w-full"
                                v-model="form.phone"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                        <div class="flex items-center mt-4" > <!-- эта кнопка выводится на последнем ответе если он не положительный - при нажатии отправляет данные в БД и все -->
                            <div class="ms-4" @click="saved_end">
                                Отправить
                            </div>
                        </div>
                    </div>

                </form>
        </div>
        <div v-else>
            <h2 class="mb-7 text-4xl text-center text-sky-700">{{props.survey.description}}<!-- Описание опроса--></h2>
            <div class="flex gap-x-11 justify-center mb-8">
                <div class="text-center text-5xl cursor-pointer" @click="next_question_1"><!--Первый вариант ответа - совсем влохой-->😡</div>
                <div class="text-center text-5xl cursor-pointer" @click="next_question_2"><!--Второй вариант ответа - средний-->🫥</div>
                <div class="text-center text-5xl cursor-pointer" @click="next_question_3"><!--Третий вариант ответа - хороший-->🙂</div>
            </div>
            <p class="text-lg text-center text-sky-600">Разработано Feedback.kz</p>
        </div>

    </div>
  </div>
</template>
