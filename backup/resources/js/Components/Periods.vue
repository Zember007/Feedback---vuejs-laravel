<template>
    <div class="stat__container">
        <div class="stat__select" @click="isOpen = !isOpen" v-click-outside="onClickOutside">
            <div class="stat__heading">
                <a href="#" class="stat__periods-title">{{ title }}</a>
                <svg
                    class="w-3"
                    xmlns="http://www.w3.org/2000/svg"
                    fill-rule="evenodd"
                    viewBox="0 0 100 100"
                >
                    <path
                        fill="black"
                        d="M20.961 55.588h12.802V42.787H20.961v12.801zm3-9.801h6.802v6.802h-6.802v-6.802zm19.861 9.801h12.802V42.787H43.822v12.801zm3-9.801h6.802v6.802h-6.802v-6.802zm19.127 9.831H78.81V42.757H65.949v12.861zm3.059-9.802h6.742v6.742h-6.742v-6.742zM20.961 77.915h12.802V65.113H20.961v12.802zm3-9.802h6.802v6.802h-6.802v-6.802zm19.861 9.802h12.802V65.113H43.822v12.802zm3-9.802h6.802v6.802h-6.802v-6.802zm19.127 9.832H78.81V65.083H65.949v12.862zm3.059-9.802h6.742v6.742h-6.742v-6.742z"
                    />
                    <path
                        fill="black"
                        d="M79.248 13.621v-2.008c0-3.294-2.68-5.974-5.974-5.974S67.3 8.319 67.3 11.613v1.922H56.108v-1.922a5.95 5.95 0 0 0-5.944-5.944 5.95 5.95 0 0 0-5.944 5.944v1.952H33v-1.952c0-3.278-2.667-5.944-5.944-5.944s-5.944 2.667-5.944 5.944v2.008H9.645v81.064h81.064V13.621H79.248zm-8.889-2.008c0-1.607 1.308-2.915 2.915-2.915s2.915 1.308 2.915 2.915v8.31c0 1.607-1.308 2.915-2.915 2.915s-2.915-1.308-2.915-2.915v-8.31zm-23.139 0c0-1.624 1.321-2.944 2.944-2.944s2.944 1.321 2.944 2.944v8.31a2.947 2.947 0 0 1-2.944 2.944 2.947 2.947 0 0 1-2.944-2.944v-8.31zm-23.109 0c0-1.624 1.321-2.944 2.944-2.944S30 9.989 30 11.613v8.31c0 1.624-1.321 2.944-2.944 2.944s-2.944-1.321-2.944-2.944l-.001-8.31zm63.598 80.072H12.645V16.621h8.466v3.302c0 3.278 2.667 5.944 5.944 5.944S33 23.201 33 19.923v-3.358h11.22v3.358c0 3.278 2.667 5.944 5.944 5.944s5.944-2.667 5.944-5.944v-3.328h11.191v3.328c0 3.294 2.68 5.974 5.974 5.974s5.974-2.68 5.974-5.974v-3.302h8.461l.001 75.064z"
                    />
                </svg>
            </div>
            <transition name="fade" appear>
                <div class="stat__periods" v-if="isOpen">
                    <div
                        v-for="(item, i) in periodsCalendar"
                        :key="i"
                        class="stat__dropdown flex gap-2"
                    >
                        <button
                            @click.prevent="sendDateFunc(item.period)"
                            class="stat__opt"
                        >
                            {{ item.title }}
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
import vClickOutside from 'click-outside-vue3'

export default {
    name: "periods",
    props: ["title"],
    emits: ["sendDate"],
    directives: {
      clickOutside: vClickOutside.directive
    },
    data() {
        return {
            isOpen: false,
            nowDate: new Date(),
            // календарный
            periodsCalendar: [
                {
                    title: "Сегодня",
                    period: {
                        start: new Date(
                            `${
                                new Date().getMonth() + 1
                            }-${new Date().getDate()}-${new Date().getFullYear()}`
                        ).getTime(),
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "Вчера",
                    period: {
                        start: new Date(
                            `${new Date().getMonth() + 1}-${
                                new Date().getDate() - 1
                            }-${new Date().getFullYear()}`
                        ).getTime(),
                        end: new Date(
                            `${
                                new Date().getMonth() + 1
                            }-${new Date().getDate()}-${new Date().getFullYear()}`
                        ).getTime(),
                    },
                },
                {
                    title: "Неделя",
                    period: {
                        start:
                            new Date().getTime() -
                            ([7, 1, 2, 3, 4, 5, 6][new Date().getDay()] - 1) *
                                24 *
                                60 *
                                60 *
                                1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "2 недели",
                    period: {
                        start:
                            new Date().getTime() -
                            ([7, 1, 2, 3, 4, 5, 6][new Date().getDay()] + 6) *
                                24 *
                                60 *
                                60 *
                                1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "Месяц",
                    period: {
                        start: new Date(
                            `${
                                new Date().getMonth() + 1
                            }-01-${new Date().getFullYear()}`
                        ).getTime(),
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
            ],
            // относительно сегодня
            periods: [
                {
                    title: "24 часа",
                    period: {
                        start: new Date().getTime() - 24 * 60 * 60 * 1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "Прошлые 24 часа",
                    period: {
                        start: new Date().getTime() - 2 * 24 * 60 * 60 * 1000,
                        end: new Date().getTime(),
                    },
                },
                {
                    title: "7 дней",
                    period: {
                        start: new Date().getTime() - 7 * 24 * 60 * 60 * 1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "14 дней",
                    period: {
                        start: new Date().getTime() - 14 * 24 * 60 * 60 * 1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
                {
                    title: "30 дней",
                    period: {
                        start: new Date().getTime() - 30 * 24 * 60 * 60 * 1000,
                        end: new Date().getTime() + 24 * 60 * 60 * 1000,
                    },
                },
            ],
        };
    },
    methods: {
        sendDateFunc(period) {
            this.$emit("sendDate", period);
        },
        onClickOutside(event) {
            this.isOpen = false;
        }
    },
};
</script>
<style>

.stat__container {
    position: relative;
}

.stat__select {
    position: absolute;
    padding: 5px 10px;
    border-radius: 20px;
    border: 1px solid black;
    background: #F0F9FF;
}

.stat__heading {
    display: flex;
    align-items: center;
    gap: 5px;
}
</style>
