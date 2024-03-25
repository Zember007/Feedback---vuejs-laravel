<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputAnswer from '@/Components/InputAnswer.vue';
import SurveyButton from '@/Components/SurveyButton.vue';
import axios from 'axios';

const props = defineProps({
    survey: {
        type: Object
    },
    questions: {
        type: Object
    },
});

const finish = ref(false)
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
    var object = { finish: "true", timestamp: new Date().getTime() }
    localStorage.setItem("finish", JSON.stringify(object));
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

onMounted(() => {

})

const getFinishValue = () => {
    if (JSON.parse(localStorage.getItem("finish")).finish == 'true') {
        if (Number(JSON.parse(localStorage.getItem("finish")).timestamp) <= new Date().getTime() - 1209600000) {
            var object = { finish: "false", timestamp: new Date().getTime() }
            localStorage.setItem("finish", JSON.stringify(object));
        } else {
            return true
        }
    }

    return false
}


</script>

<template>
    <div>
        <div class="survey__main">
            <h1 class="title_survey">
                {{ props.survey.name }} <!-- Имя опроса-->
            </h1>

            <div class="flex items-center flex-col" style="margin: -50px;"
                v-if='next_question_1_add || next_question_2_add'>

                <form class="flex items-center flex-col gap-4" @submit.prevent="saved_end">
                    <div class="description__box">
                        <div class="description__block">
                            <h2>{{ props.questions[questions_length].text }}</h2>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2" v-if='next_question_1_add || next_question_2_add'>

                        <div>
                            <div class="flex items-center gap-2" id="answers"
                                v-for='answer in props.questions[questions_length].answer_options'>
                                <input type="checkbox" :id="answer.id" :value="answer.id" v-model="form.answers" />
                                <label :for="answer.id">{{ answer.text }}</label>
                                <br />

                            </div>
                        </div>
                        <div>
                            <textarea type="text" class="textarea_answer" placeholder="С чем у Вас возникли трудности?"
                                v-model="form.comment" required></textarea>
                        </div>
                        <div>

                            <InputAnswer placeholder="ФИО" id="name" type="text" class="mt-1 block w-full"
                                v-model="form.name" required />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputAnswer placeholder="Номер телефона" id="phone" type="tel" class="mt-1 block w-full"
                                v-model="form.phone" required />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                        <div class="flex items-center mt-4 justify-center">
                            <!-- эта кнопка выводится на последнем ответе если он не положительный - при нажатии отправляет данные в БД и все -->
                            <SurveyButton>
                                Отправить
                            </SurveyButton>
                        </div>
                    </div>

                </form>
            </div>
            <div v-else-if="getFinishValue()">
                <div class="description__box flex-col">
                    <img src="../../../img/ThankYou.jpg" alt="Feedback.kz">
                    <div class="description__block">
                        <h2>
                            Спасибо!<br/>
                            Ваш отзыв будет<br/>
                            передан руководству!<br/>
                            Хорошего дня!<br/>
                        </h2>
                    </div>
                </div>
            </div>
            <div v-else class="survey__preview">
                <div class="description__box">
                    <div class="description__block">
                        <h2>{{ props.survey.description }}</h2>
                    </div>
                </div>
                <div class="flex gap-x-11 justify-center">
                    <div class="text-center text-5xl cursor-pointer" @click="next_question_1">
                        <!--Первый вариант ответа - совсем влохой-->😡
                    </div>
                    <div class="text-center text-5xl cursor-pointer" @click="next_question_2">
                        <!--Второй вариант ответа - средний-->🙂
                    </div>
                    <div class="text-center text-5xl cursor-pointer" @click="next_question_3">
                        <!--Третий вариант ответа - хороший-->🙂
                    </div>
                </div>
            </div>

            <p class="developer_inf">Разработано Feedback.kz</p>

        </div>
    </div>
</template>

<style scoped>
.survey__main {
    display: flex;
    flex-direction: column;
    gap: 60px;
    padding: 60px 10px;
    margin-bottom: 50px;
}

.survey__preview {
    display: flex;
    flex-direction: column;
    gap: 60px;
}

.developer_inf {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    color: #156CB0;
    font-size: 18px;
    white-space: nowrap;
}

.title_survey {
    font-size: 34px;
    font-weight: 700;
    color: #156CB0;
    text-align: center;
}

.description__box {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.description__block {
    padding: 20px 30px;
    border-radius: 20px;
    background: #C4DDFF;
    text-align: center;
    color: #156CB0;
    font-size: 24px;
    font-weight: 700;
}

.textarea_answer {
    width: 300px;
    height: 160px;
    resize: none;
    border: 2px solid #156CB0;
    padding: 5px;
}
</style>
