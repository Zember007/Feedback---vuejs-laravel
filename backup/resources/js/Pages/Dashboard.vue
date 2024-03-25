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
import { ref, computed } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
// import Survey_answer from "./Survey/Survey_answer.vue";
import Periods from "../Components/Periods.vue";

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


// PERIODS
let periodDateStart = ref<string>('');
let periodDateEnd = ref<string>('');

const answersList = computed(() => {
    const result = props.answers.filter(
        (el : { created_at: string }) => {
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
        (el : { created_at: string }) => {
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
const answersBad = computed( () => {
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
const answersPrevPeriodBad = computed( () => {
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
    set(value : any) {
        indexValue.value = value;
    },
    get() : number {
        return indexValue.value;
    }
});
// @ts-ignore
indexValueExp.value = indexes.value.find((item : Object) : Object => item.name === 'csat').couting;

const updateIndexValue = (item) => {
    indexValueExp.value = item.couting;
}


// ANSWERS
const filterPercentRound = (value) : string => {
    // @ts-ignore
    return parseFloat(value).toFixed(2);
}


// ANSWERS TABLE
const answersOptionsList = ref([]);
const answersOptions = computed(() => {
    if (answers.value) {}
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
        }).map((el : { answer_option_id: string; }) => {
            return JSON.parse(el.answer_option_id);
        })
    result.forEach((el) => {
        el.forEach((id : number) => {
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
    startDate = new Date(startDate.getTime() - (offset*60*1000))
    endDate = new Date(endDate.getTime() - (offset*60*1000))

    periodDateStart.value = startDate.toISOString().split('T')[0];
    periodDateEnd.value = endDate.toISOString().split('T')[0];
}

</script>


<template>
    <DashboardLayout>
        <div>
            <div class="component__header mb-4">
                <h3 class="text-3xl font-medium text-sky-600">
                    Панель управления
                </h3>
                <form class="component__form" action="#">
                    <input
                        type="date"
                        name="startDate"
                        id="start-date"
                        class="component__inp"
                        v-model="periodDateStart"
                    />
                    <input
                        type="date"
                        name="endDate"
                        id="end-date"
                        class="component__inp"
                        v-model="periodDateEnd"
                    />
                    <Periods @sendDate="setDate" title="Периоды"/>
                </form>
            </div>
            <div>
                <ul class="list-reset grid grid-cols-3 stat__list list-none table">
                    <!-- <li class="pt-2 stat__questions">
                        <h2 class="text-xl text-sky-600">Компания:</h2>
                        <h3 class="text-sm text-sky-500">{{ user.company }}</h3>
                        <h2 class="text-xl text-sky-600">Вопрос:</h2>
                        <p class="text-sm text-sky-500">answer</p>
                    </li> -->
                    <li class="pt-2 stat__formulas border border-sky-600">
                        <h2 class="mb-3 text-xl text-sky-800 text-center">
                            Индексы
                        </h2>
                        <span class="flex justify-between mb-4 px-2">
                            <button
                                v-for="indexItem in indexes"
                                class="btn-reset px-2.5 py-1 border-none rounded-2xl bg-sky-700 text-sky-100 index-btn"
                                @click="updateIndexValue(indexItem)"
                            >
                                {{ indexItem.name.toUpperCase() }}
                            </button>
                        </span>
                        <p class="stat__value"></p>
                        <p class="text-center text-base">{{ filterPercentRound(indexValueExp.value) }}</p>
                    </li>
                    <li class="pt-2 stat__responses border border-sky-600">
                        <h2 class="mb-3 text-xl text-sky-800 text-center">
                            Ответы
                        </h2>
                        <p class="text-center text-sky-400">
                            {{ answersList.length }}
                        </p>
                        <!--Это уже готово-->
                        <p class="text-center text-base text-red-300"></p>
                        <!--Сравнение с предыдущим периодом-->
                    </li>
                    <li class="grid grid-cols-3">
                        <!-- bad answers -->
                        <div
                            class="pt-2 stat__mark inline-block border border-sky-600"
                        >
                            <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                                <path
                                    fill="red"
                                    d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16c92.63 0 168 75.36 168 168s-75.37 168-168 168z"
                                ></path>
                                <path
                                    fill="red"
                                    d="M144 152c0-13.23-10.77-24-24-24s-24 10.77-24 24 10.77 24 24 24 24-10.77 24-24zm-32 0c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8-8-3.6-8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32a8 8 0 11.02-16.02A8 8 0 01248 160zM184 224a104.66 104.66 0 00-77.96 34.66 8 8 0 0011.92 10.68A88.63 88.63 0 01184 240a88.63 88.63 0 0166.04 29.34 7.96 7.96 0 0011.3.62 8 8 0 00.62-11.3A104.67 104.67 0 00184 224z"
                                ></path>
                            </svg>
                            <h3 class="text-center text-sky-700 text-lg">{{ answersList.length ? filterPercentRound(answersBad.length / answersList.length * 100) : 0}}%</h3>
                            <!--их отношение к общей сумме-->
                            <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersBad.length : 0 }}</p>
                            <!--Количество ответов данного типа-->
                            <p class="text-center text-sky-500 text-sm">{{ answersPrevPeriodList ? answersBad.length - answersPrevPeriodBad.length : 0 }}</p>
                            <!--Изменения в сравнении с предыдущим периодом с предыдущим этапом-->
                        </div>
                        <!-- neutral answers -->
                        <div
                            class="pt-2 stat__mark inline-block border border-sky-600"
                        >
                            <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                                <path
                                    fill="#717171"
                                    d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16s168 75.36 168 168-75.36 168-168 168z"
                                ></path>
                                <path
                                    fill="#717171"
                                    d="M120 176c13.23 0 24-10.77 24-24s-10.77-24-24-24-24 10.77-24 24 10.77 24 24 24zm0-32c4.4 0 8 3.6 8 8s-3.6 8-8 8-8-3.6-8-8 3.6-8 8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zM264 224H104a8 8 0 000 16h160a8 8 0 000-16z"
                                ></path>
                            </svg>
                            <h3 class="text-center text-sky-700 text-lg">{{ answersList.length ? filterPercentRound(answersNeutral.length / answersList.length * 100) : 0}}%</h3>
                            <!--их отношение к общей сумме-->
                            <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersNeutral.length : 0 }}</p>
                            <!--Количество ответов данного типа-->
                            <p class="text-center text-sky-500 text-sm">{{ answersPrevPeriodList ? answersNeutral.length - answersPrevPeriodNeutral.length : 0 }}</p>
                            <!--Изменения в сравнении с предыдущим периодом с предыдущим этапом-->
                        </div>
                        <!-- good answers -->
                        <div
                            class="pt-2 stat__mark inline-block border border-sky-600"
                        >
                            <svg viewBox="0 0 368 368" class="w-12 mx-auto">
                                <path
                                    fill="green"
                                    d="M184 0C82.54 0 0 82.54 0 184s82.54 184 184 184 184-82.54 184-184S285.46 0 184 0zm0 352c-92.64 0-168-75.36-168-168S91.36 16 184 16s168 75.36 168 168-75.36 168-168 168z"
                                ></path>
                                <path
                                    fill="green"
                                    d="M144 152c0-13.23-10.77-24-24-24s-24 10.77-24 24 10.77 24 24 24 24-10.77 24-24zm-32 0c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8-8-3.6-8-8zM248 128c-13.23 0-24 10.77-24 24s10.77 24 24 24 24-10.77 24-24-10.77-24-24-24zm0 32c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zM261.34 226.04a8 8 0 00-11.3.62A88.62 88.62 0 01184 256a88.63 88.63 0 01-66.04-29.34 8 8 0 10-11.92 10.68A104.66 104.66 0 00184 272c29.83 0 58.25-12.64 77.96-34.66a8 8 0 00-.62-11.3z"
                                ></path>
                            </svg>
                            <h3 class="text-center text-sky-700 text-lg">{{ answersList.length ? filterPercentRound(answersGood.length / answersList.length * 100) : 0 }}%</h3>
                            <!--их отношение к общей сумме-->
                            <p class="text-center text-sky-600 text-base">{{ answersList.length ? answersGood.length : 0 }}</p>
                            <!--Количество ответов данного типа-->
                            <p class="text-center text-sky-500 text-sm">{{ answersPrevPeriodList ? answersGood.length - answersPrevPeriodGood.length : 0 }}</p>
                            <!--Изменения в сравнении с предыдущим периодом с предыдущим этапом-->
                        </div>
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
                        <span class="answers__name answers__name-count">К-во:</span>
                        <span class="answers__name answers__name-percent">Проценты:</span>
                    </li>
                    <li class="answers__item" v-for="option in answersOptions">
                        <p class="answers__answer">{{ option.text }}</p>
                        <p class="answers__count">{{ option.count }}</p>
                        <div class="answers__percent">
                            <div class="answers__percent-bar">
                                <div :style="{ transform: `scaleX(${answersWithOption.length ? option.count / answersWithOption.reduce((sum,a) => sum + a.length, 0) : 0})` }" class="answers__percent-progress"></div>
                            </div>
                            <p class="answers__percent-count">{{ answersWithOption.length ? Math.round(option.count / answersWithOption.reduce((sum,a) => sum + a.length, 0) * 100) : 0}}%</p>
                        </div>
                    </li>
                    <li class="answers__item">
                        <p class="answers__answer answers__answer-count">Всего проголосовало</p>
                        <p class="answers__count">{{ answersWithOption.reduce((sum,a) => sum + a.length, 0) }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </DashboardLayout>
</template>
<style scoped>
.component__form {
    display: flex;
    align-items: flex-start;
    gap: 5px;
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
    border-bottom: 1px solid rgba(0,0,0,.1);
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

.answers__item:not(:first-child) {
    border-bottom: 1px solid rgba(0,0,0,.1);
}

.answers__name {
    padding-bottom: 5px;

    font-weight: 500;
    font-size: 17px;
    color: rgba(0,0,0, .3);
}

.answers__answer {
    font-weight: 500;
    font-size: 20px;
    color: rgba(0,0,0, .6);
}

.answers__answer-count {
    font-weight: 700;
    color: rgba(0,0,0, .7);
}

.answers__count {
    display: flex;
    justify-content: center;

    font-weight: 700;
    font-size: 18px;
    color: rgba(0,0,0,.8);
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
    color: rgba(0,0,0,.8);
}

@media (max-width: 768px) {
    .table {
        grid-template-columns: 1fr;
        grid-template-rows: repeat(3,minmax(0,1fr));
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
</style>
