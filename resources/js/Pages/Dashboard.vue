<!-- answers - array
обращаться к свойствам типа status, id и т.д. через v-for . типа:
v-for (answer in answers)
answer.status
answer.id и т.д
if answer.status = 1
negatuveAns++
Вывести negativeAns через {{ negativeAns }}

answers  - содержит поля (таблица ответов пользователей)
    id  - индитификатор
    question_id - индетификатор вороса
    answer_option_id   - поле содержит в себе массив с id ответов (пример [3,4,5])
    comment  - комментарий резидента
    status   - статус ответа - может быть 1 (плохо) - 2 (средне) - 3 (хорошо) - при третьем ответе данные человека не записываются
    created_at  - дата ответа - по ней сортировать
    name    - имя резидента {{ answers.answer.name }} или типа того
    phone - телефон резидента
answer_options   - содержит поля (таблица ответов на вопросы)
    id  - индитификатор
    question_id - индетификатор вороса
    text  - ответ -->

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import CommentsLayout from "@/Layouts/CommentsLayout.vue"
// import Survey_answer from "./Survey/Survey_answer.vue";
import Periods from "../Components/Periods.vue";
import { usePage, useForm } from '@inertiajs/vue3';
// PROPS
const props = defineProps({
    answers: {
        type: Object,
    },
    answer_options: {
        type: Object,
    },
});

const answers = ref(props.answers ?? []);
const answersPrevPeriod = ref(props.answers ?? []);
const user = usePage().props.auth.user;
const comments_data_Bad = ref([]);
const comments_data_Neutral = ref([]);


onMounted(() => {
    if (user.subscription_paymed) {
        const pay_inf = useForm({
            pg_payment_id: Number(user.subscription_paymed),
            pg_order_id: String(user.id),
            user_id: String(user.id)
        });
        pay_inf.post(route('subscription.result'));
    }
})

// PERIODS
let periodDateStart = ref<string>('');
let periodDateEnd = ref<string>('');

const answersList = computed(() => {
    const result = props.answers.filter(
        (el: { created_at: string }) => {
            const answerDate = new Date(el.created_at);
            const dateStart = periodDateStart.value ? new Date(periodDateStart.value) : new Date(0);
            const dateEnd = periodDateEnd.value ? new Date(periodDateEnd.value) : new Date();
            return answerDate.getTime() >= dateStart.getTime() && answerDate.getTime() <= dateEnd.getTime();
        });
    answers.value = result;
    return result;
});
const answersPrevPeriodList = computed(() => {
    const result = props.answers.filter(
        (el: { created_at: string }) => {
            const answerDate = new Date(el.created_at);
            const dateStart = periodDateStart.value ? new Date(periodDateStart.value) : new Date(0);
            const dateEnd = periodDateEnd.value ? new Date(periodDateEnd.value) : new Date();
            const prevPeriod = dateEnd.getTime() - dateStart.getTime();

            return answerDate.getTime() >= dateStart.getTime() - prevPeriod && answerDate.getTime() <= dateEnd.getTime() - prevPeriod;
        });
    answersPrevPeriod.value = result;

    return result;
});

// ANSWERS FILTER
const answersGood = computed(() => {
    return answers.value.filter(
        (el: { status: string; }) => {
            return el.status === "3";
        });
});
const answersNeutral = computed(() => {
    return answers.value.filter(
        (el: { status: string; }) => {
            return el.status === "2";
        });
});
const answersBad = computed(() => {
    return answers.value.filter(
        (el: { status: string; }) => {
            return el.status === "1";
        });
});

const answersPrevPeriodGood = computed(() => {
    return answersPrevPeriod.value.filter(
        (el: { status: string; }) => {
            return el.status === "3";
        });
});
const answersPrevPeriodNeutral = computed(() => {
    return answersPrevPeriod.value.filter(
        (el: { status: string; }) => {
            return el.status === "2";
        });
});
const answersPrevPeriodBad = computed(() => {
    return answersPrevPeriod.value.filter(
        (el: { status: string; }) => {
            return el.status === "1";
        });
});

// INDEXES
type IndexName = "nps" | "csat";

interface Index {
    name: IndexName,
    couting: () => number,
}

const indexes = computed(() => {
    return [
        {
            name: 'csat',
            couting: computed(() => {
                return answers.value.length ? answersGood.value.length / answers.value.length * 100 : 0;
            }),
        },
        {
            name: 'nps',
            couting: computed(() => {
                return answers.value.length ? answersGood.value.length / answers.value.length - answersBad.value.length / answers.value.length : 0;
            }),
        },
    ];
});

const indexValue = ref(0);
const indexValueExp = computed({
    set(value: any) {
        indexValue.value = value;
    },
    get(): number {
        return indexValue.value;
    }
});
// @ts-ignore
indexValueExp.value = indexes.value.find((item: Object): Object => item.name === 'csat').couting;

const updateIndexValue = (item) => {
    indexValueExp.value = item.couting;
}


// ANSWERS
const filterPercentRound = (value): string => {
    // @ts-ignore
    return parseFloat(value).toFixed(2);
}


// ANSWERS TABLE
const answersOptionsList = ref([]);
const answersOptions = computed(() => {
    if (answers.value) { }
    const arr = [];
    props.answer_options.forEach(el => {
        arr.push({
            id: el.id,
            text: el.text,
            count: 0,
        });
    });
    answersOptionsList.value = arr;

    return arr;
});
const answersWithOption = computed(() => {
    const result = answers.value.filter(
        (el: { answer_option_id: string; }) => {
            return el.answer_option_id !== null;
        }).map((el: { answer_option_id: string; }) => {
            return JSON.parse(el.answer_option_id);
        })
    result.forEach((el) => {
        el.forEach((id: number) => {
            // @ts-ignore
            const index = answersOptions.value.findIndex(obj => obj.id === id);
            answersOptionsList.value[index].count += 1;
        });
    });

    return result;
});

function setDate(period) {
    let startDate = new Date(period.start);
    let endDate = new Date(period.end);
    const offset = startDate.getTimezoneOffset()
    startDate = new Date(startDate.getTime() - (offset * 60 * 1000))
    endDate = new Date(endDate.getTime() - (offset * 60 * 1000))

    periodDateStart.value = startDate.toISOString().split('T')[0];
    periodDateEnd.value = endDate.toISOString().split('T')[0];
}


comments_data_Neutral.value = answersNeutral.value;
comments_data_Bad.value = answersBad.value;

</script>

<template>
    <DashboardLayout>
        <div v-if='(user.subscription && user.subscription_duration > 0) || user.role <= 1'>
            <div class="answers__top component__form mb-4">
                <h3 class="text-3xl font-medium text-sky-600">
                    Статистика
                </h3>
                <div class="component__form_box">
                    <form class="component__form" action="#">
                        <input type="date" name="startDate" id="start-date" class="component__inp"
                            v-model="periodDateStart" />
                        <input type="date" name="endDate" id="end-date" class="component__inp"
                            v-model="periodDateEnd" />
                        <Periods @sendDate="setDate" title="Периоды" />
                    </form>
                </div>
            </div>
            <div>

                <ul class="list-reset grid grid-cols-5 stat__list list-none table">
                    <li class="pt-2 stat__responses">
                        <h2 class="mb-3 title-grid text-center">
                            Индексы
                        </h2>
                        <span class="flex justify-between mb-6 px-6">
                            <button v-for="indexItem in indexes" class="indexItem_default"
                                :class="[(indexItem.couting._value == indexValueExp.value) ? 'indexItem_active' : '']"
                                @click="updateIndexValue(indexItem)">
                                {{ indexItem.name.toUpperCase() }}
                            </button>
                        </span>
                        <p class="indexItem_text">{{ filterPercentRound(indexValueExp.value) }}</p>
                    </li>
                    <li class="pt-2 stat__responses">
                        <h2 class="mb-8 title-grid text-center">
                            Ответы
                        </h2>
                        <p class="indexItem_text">
                            {{ answersList.length }}
                        </p>
                        <!--Это уже готово-->
                        <p class="text-center text-base text-red-300"></p>
                        <!--Сравнение с предыдущим периодом-->
                    </li>

                    <li class="pt-2 stat__responses">
                        <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                            <path fill="red"
                                d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16c92.63 0 168 75.36 168 168s-75.37 168-168 168z">
                            </path>
                            <path fill="red"
                                d="M144 152c0-13.23-10.77-24-24-24s-24 10.77-24 24 10.77 24 24 24 24-10.77 24-24zm-32 0c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8-8-3.6-8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32a8 8 0 11.02-16.02A8 8 0 01248 160zM184 224a104.66 104.66 0 00-77.96 34.66 8 8 0 0011.92 10.68A88.63 88.63 0 01184 240a88.63 88.63 0 0166.04 29.34 7.96 7.96 0 0011.3.62 8 8 0 00.62-11.3A104.67 104.67 0 00184 224z">
                            </path>
                        </svg>
                        <h3 class="text-center indexItem_text">{{ answersList.length ?
            filterPercentRound(answersBad.length / answersList.length * 100) : 0 }}%</h3>
                        <!--их отношение к общей сумме-->
                        <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersBad.length : 0
                            }}</p>
                        <!--Количество ответов данного типа-->
                        <p class="flex justify-center"
                        style="cursor: pointer;"
                        @click="$refs.comments_Bad.openModal()"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                data-name="Layer 2" preserveAspectRatio="xMidYMid meet" version="1.0"
                                viewBox="0 0 100 100" zoomAndPan="magnify" style="fill: rgb(21, 108, 176);width: 50px;height: auto;transform: translateX(12px);"
                                original_string_length="1463">
                                <g id="__id124_sis7ax1lg">
                                    <path d="M12.21,18.88h0L41,18.68a1,1,0,0,0,0-1.93h0L12.2,17a1,1,0,1,0,0,1.93Z"
                                        style="fill: inherit;" />
                                </g>
                                <g id="__id125_sis7ax1lg">
                                    <path d="M12.21,28h0L41,27.78a1,1,0,0,0,0-1.93h0l-28.82.2a1,1,0,0,0,0,1.93Z"
                                        style="fill: inherit;" />
                                </g>
                                <g id="__id126_sis7ax1lg">
                                    <path
                                        d="M56.18,16.3h-6V10.94a4.82,4.82,0,0,0-4.81-4.81H7.81A4.82,4.82,0,0,0,3,10.94V33.45a4.82,4.82,0,0,0,3.75,4.69L5.9,46.63a1,1,0,0,0,.49.94.91.91,0,0,0,.48.13,1,1,0,0,0,.57-.19l6.33-4.69v.82a4.8,4.8,0,0,0,4.8,4.79h25.5l12.48,9.25a.94.94,0,0,0,.58.19.88.88,0,0,0,.47-.13.94.94,0,0,0,.49-.93l-.85-8.49A4.81,4.81,0,0,0,61,43.64V21.12A4.82,4.82,0,0,0,56.18,16.3ZM8.53,36.65a1,1,0,0,0-.72-.32,2.88,2.88,0,0,1-2.88-2.88V10.94A2.88,2.88,0,0,1,7.81,8.06H45.42a2.88,2.88,0,0,1,2.88,2.88v6.3s0,0,0,0,0,0,0,0V33.45a2.88,2.88,0,0,1-2.88,2.88H19.6a.93.93,0,0,0-.57.19L8,44.65l.73-7.26A1,1,0,0,0,8.53,36.65Zm50.54,7a2.89,2.89,0,0,1-2.89,2.86,1,1,0,0,0-.71.32.92.92,0,0,0-.25.74L56,54.83,45,46.69a1,1,0,0,0-.57-.19H18.57a2.87,2.87,0,0,1-2.87-2.86V41.39l4.22-3.13h25.5a4.82,4.82,0,0,0,4.81-4.81V18.24h6a2.89,2.89,0,0,1,2.89,2.88Z"
                                        style="fill: inherit;" />
                                </g>
                            </svg>
                        </p>
                        <!--Изменения в сравнении с предыдущим периодом с предыдущим этапом-->
                    </li>

                    <li class="pt-2 stat__responses">
                        <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                            <path fill="#717171"
                                d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16s168 75.36 168 168-75.36 168-168 168z">
                            </path>
                            <path fill="#717171"
                                d="M120 176c13.23 0 24-10.77 24-24s-10.77-24-24-24-24 10.77-24 24 10.77 24 24 24zm0-32c4.4 0 8 3.6 8 8s-3.6 8-8 8-8-3.6-8-8 3.6-8 8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zM264 224H104a8 8 0 000 16h160a8 8 0 000-16z">
                            </path>
                        </svg>
                        <h3 class="text-center indexItem_text">{{ answersList.length ?
                        filterPercentRound(answersNeutral.length / answersList.length * 100) : 0 }}%</h3>
                        <!--их отношение к общей сумме-->
                        <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersNeutral.length
                        : 0 }}</p>
                        
                        <!--Количество ответов данного типа-->
                        <p class="flex justify-center" 
                        style="cursor: pointer;"
                        @click="$refs.comments_Neutral.openModal()"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                data-name="Layer 2" preserveAspectRatio="xMidYMid meet" version="1.0"
                                viewBox="0 0 100 100" zoomAndPan="magnify" style="fill: rgb(21, 108, 176);width: 50px;height: auto;transform: translateX(12px);"
                                original_string_length="1463">
                                <g id="__id124_sis7ax1lg">
                                    <path d="M12.21,18.88h0L41,18.68a1,1,0,0,0,0-1.93h0L12.2,17a1,1,0,1,0,0,1.93Z"
                                        style="fill: inherit;" />
                                </g>
                                <g id="__id125_sis7ax1lg">
                                    <path d="M12.21,28h0L41,27.78a1,1,0,0,0,0-1.93h0l-28.82.2a1,1,0,0,0,0,1.93Z"
                                        style="fill: inherit;" />
                                </g>
                                <g id="__id126_sis7ax1lg">
                                    <path
                                        d="M56.18,16.3h-6V10.94a4.82,4.82,0,0,0-4.81-4.81H7.81A4.82,4.82,0,0,0,3,10.94V33.45a4.82,4.82,0,0,0,3.75,4.69L5.9,46.63a1,1,0,0,0,.49.94.91.91,0,0,0,.48.13,1,1,0,0,0,.57-.19l6.33-4.69v.82a4.8,4.8,0,0,0,4.8,4.79h25.5l12.48,9.25a.94.94,0,0,0,.58.19.88.88,0,0,0,.47-.13.94.94,0,0,0,.49-.93l-.85-8.49A4.81,4.81,0,0,0,61,43.64V21.12A4.82,4.82,0,0,0,56.18,16.3ZM8.53,36.65a1,1,0,0,0-.72-.32,2.88,2.88,0,0,1-2.88-2.88V10.94A2.88,2.88,0,0,1,7.81,8.06H45.42a2.88,2.88,0,0,1,2.88,2.88v6.3s0,0,0,0,0,0,0,0V33.45a2.88,2.88,0,0,1-2.88,2.88H19.6a.93.93,0,0,0-.57.19L8,44.65l.73-7.26A1,1,0,0,0,8.53,36.65Zm50.54,7a2.89,2.89,0,0,1-2.89,2.86,1,1,0,0,0-.71.32.92.92,0,0,0-.25.74L56,54.83,45,46.69a1,1,0,0,0-.57-.19H18.57a2.87,2.87,0,0,1-2.87-2.86V41.39l4.22-3.13h25.5a4.82,4.82,0,0,0,4.81-4.81V18.24h6a2.89,2.89,0,0,1,2.89,2.88Z"
                                        style="fill: inherit;" />
                                </g>
                            </svg>
                        </p>
                        <!--Изменения в сравнении с предыдущим периодом с предыдущим этапом-->
                    </li>

                    <li class="pt-2 stat__responses">
                        <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                            <path fill="green"
                                d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16s168 75.36 168 168-75.36 168-168 168z">
                            </path>
                            <path fill="green"
                                d="M144 152c0-13.23-10.77-24-24-24s-24 10.77-24 24 10.77 24 24 24 24-10.77 24-24zm-32 0c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8-8-3.6-8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zM261.34 226.04a8 8 0 00-11.3.62A88.62 88.62 0 01184 256a88.63 88.63 0 01-66.04-29.34 8 8 0 10-11.92 10.68A104.66 104.66 0 00184 272c29.83 0 58.25-12.64 77.96-34.66a8 8 0 00-.62-11.3z">
                            </path>
                        </svg>
                        <h3 class="text-center indexItem_text">{{ answersList.length ?
            filterPercentRound(answersGood.length / answersList.length * 100) : 0 }}%</h3>
                        <!--их отношение к общей сумме-->
                        <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersGood.length : 0
                            }}</p>
                        <!--Количество ответов данного типа-->
                    </li>
                </ul>
            </div>
            <div class="answers">
                <div class="answers__top">
                    <h2 class="answers__heading">Что следует улучшить</h2>
                </div>
                <ul class="answers__table">
                    <li class="answers__item">
                        <span class="answers__name">Опрос:</span>
                        <span class="answers__name answers__name-count">Кол-во:</span>
                        <span class="answers__name answers__name-count">Проценты:</span>
                    </li>
                    <li class="answers__item" v-for="option in answersOptions">
                        <p class="answers__answer">{{ option.text }}</p>
                        <p class="answers__count">{{ option.count }}</p>
                        <div class="answers__count">
                            <p class="answers__percent-count">{{ answersWithOption.length ? Math.round(option.count /
                                answersWithOption.reduce((sum,a) => sum + a.length, 0) * 100) : 0}}%</p>
                        </div>
                    </li>
                    <!-- <li class="answers__item">
                        <p class="answers__answer answers__answer-count">Всего проголосовало</p>
                        <p class="answers__count">{{ answersWithOption.reduce((sum,a) => sum + a.length, 0) }}</p>
                    </li> -->
                </ul>
            </div>
        </div>
        <div v-else class="Error_subscription">
            Ваша подписка неактивна
        </div>
    </DashboardLayout>
    <CommentsLayout ref="comments_Bad" :comments="answersBad">
    </CommentsLayout>
    <CommentsLayout ref="comments_Neutral" :comments="comments_data_Neutral">      
    </CommentsLayout>
</template>

<style scoped>
.title-grid {
    color: #156cb0;
    font-size: 20px;
    font-weight: 100;
}

.stat__responses {
    background: linear-gradient(#E7F1FF, #E7F1FF) padding-box,
        radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%) border-box;
    border-right: 1px solid transparent;
    border-left: 1px solid transparent;
}

.stat__responses:first-child {
    border-left: 0;
}

.stat__responses:last-child {
    border-right: 0;
}

.indexItem_text {
    font-size: 26px;
    color: #156CB0;
    font-weight: 700;
    text-align: center;
}

.indexItem_default {
    color: #156cb0;
    font-size: 18px;
    font-weight: 100;
    opacity: 0.7;
    border: 1px solid gray;
    border-radius: 8px;
    padding: 2px 5px;
}

.indexItem_default.indexItem_active {
    opacity: 1;
    font-weight: 700;
    border: 1px solid #156cb0;

}

.Error_subscription {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    font-weight: 700;
}

.component__form {
    display: flex;
    align-items: center;
    gap: 15px;
}

.component__inp {
    padding: 5px 10px;
    border-radius: 10px;
    background: #FFF;
    width: 150px;
    color: #156CB0;
    font-size: 18px;
    box-shadow: 0px 0px 12px -7px #000;
    height: 40px;
}

.component__form_box {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.subscription__box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
    font-size: 18px;
    font-weight: 500;
}

.subscription__box strong {
    font-size: 20px;
    font-weight: 700;
}

.overview {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    width: 100%;
}

.answers {
    padding-top: 30px;
}

.answers__top {
    width: 100%;
    padding: 10px 0 20px 0;
    background: linear-gradient(#E7F1FF, #E7F1FF) padding-box,
        radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%) border-box;
    border-bottom: 5px solid transparent;
}

.answers__heading {
    font-weight: 500;
    font-size: 30px;
    line-height: 100%;
    color: #0284c7;
}

.answers__table {
    display: flex;
    flex-direction: column;

    padding-top: 20px;
    padding-bottom: 20px;
}

.answers__item {
    display: grid;
    grid-template-columns: 6fr 1fr 2fr;
    grid-gap: 30px;

    padding-top: 11px;
    padding-bottom: 11px;
}

.answers__name {
    padding-bottom: 5px;
    font-weight: 700;
    color: #0284c7;
    font-size: 20px;
}

.answers__answer {
    font-weight: 500;
    font-size: 20px;
    color: rgba(0, 0, 0, .6);
}

.answers__answer-count {
    font-weight: 700;
    color: rgba(0, 0, 0, .7);
}

.answers__count {
    display: flex;
    justify-content: center;

    font-weight: 700;
    font-size: 18px;
    color: rgba(0, 0, 0, .8);
}

.answers__name-count {
    display: flex;
    justify-content: center;
}

.answers__percent {
    display: flex;
    align-items: center;
    gap: 20px;
}

.answers__percent-bar {
    position: relative;

    width: 100%;
    height: 10px;
    border-radius: 10px;
    overflow: hidden;

    background: white;
}

.answers__percent-progress {
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;

    background: #0284c7;
    border-radius: 10px;

    transform-origin: left;
}

.answers__percent-count {
    font-weight: 700;
    font-size: 18px;
    color: rgba(0, 0, 0, .8);
}

@media (max-width: 768px) {
    .table {
        grid-template-columns: 1fr;
        grid-template-rows: repeat(3, minmax(0, 1fr));
    }

    .answers__item {
        grid-template-columns: 3.8fr 1fr 2fr;
    }

    .answers__percent-count {
        font-size: 15px;
    }

    .answers__percent {
        gap: 10px;
    }
}

@media (max-width: 425px) {

    .answers__heading {
        font-size: calc(30 / 425 * 100vw);
    }

    .answers__table {
        padding-top: calc(20 / 425 * 100vw);
        padding-bottom: calc(20 / 425 * 100vw);
    }

    .answers__item {
        grid-gap: calc(10 / 425 * 100vw);

        padding-top: calc(11 / 425 * 100vw);
        padding-bottom: calc(11 / 425 * 100vw);
    }

    .answers__name {
        padding-bottom: calc(5 / 425 * 100vw);
        font-size: calc(15 / 425 * 100vw);
    }

    .answers__answer {
        font-size: calc(17 / 425 * 100vw);
    }

    .answers__count {
        font-size: calc(18 / 425 * 100vw);
    }

    .answers__percent-count {
        font-size: calc(15 / 425 * 100vw);
    }

    .answers__item {
        grid-template-columns: 3.8fr 1fr 2fr;
    }

    .answers__percent-bar {
        display: none;
    }

    .answers__percent {
        justify-content: center;
    }

    .answers__name-percent {
        display: flex;
        justify-content: center;
    }
}

input[type="date"] {
    position: relative;
    border: none;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    color: transparent;
    background: none;
    z-index: 1;
}

input[type="date"]:before {
    color: transparent;
    background: none;
    display: block;
    content: url(../../img/icon_calendar.svg);
    width: 15px;
    height: 20px;
    position: absolute;
    top: 50%;
    transform: translateY(-70%);
    right: 25px;
    color: #999;
}
</style>
