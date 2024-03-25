<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Главная" />

    <div
        class="body relative sm:flex sm:justify-center sm:items-center min-h-screen selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <Link
                    :href="route('tariffs')"
                    class="login font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Тарифы</Link
                >
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Административная панель</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="login ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Вход</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="register ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Регистрация</Link
                >
            </template>
        </div>

        <div class="max-w-6xl md:relative container p-6"><!--Work here!!!-->
            <section class="top">
                <h1 class="top__title">Станьте ближе к своим клиентам</h1>
                <p class="top__descr">
                    Повышайте рейтинг компании на картах и оперативно устраняйте проблемы в обслуживании.
                </p>
                <button class="top__btn">Начать</button>
                <div class="top__bg"></div>
                <div class="absolute lap-bg">
                    <img src="../../img/lap.jpg" alt="Feedback.kz">
                </div>
            </section>
            <footer class="footer">
                <!--Icons with social medias-->
                <div class="container flex md:justify-end sm:justify-center">
                    <div class="footer__bg flex gap-3 justify-center">
                        <a href="#" class="w-5 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
                                <g>
                                    <g>
                                        <g>
                                            <path style="fill:#FFFFFF;" d="M4.221,29.298l-0.104-0.181c-1.608-2.786-2.459-5.969-2.458-9.205     C1.663,9.76,9.926,1.5,20.078,1.5c4.926,0.002,9.553,1.919,13.03,5.399c3.477,3.48,5.392,8.107,5.392,13.028     c-0.005,10.153-8.268,18.414-18.42,18.414c-3.082-0.002-6.126-0.776-8.811-2.24l-0.174-0.096l-9.385,2.46L4.221,29.298z"/>
                                            <path style="fill:#4bccf0;" d="M20.078,2L20.078,2c4.791,0.001,9.293,1.867,12.676,5.253C36.137,10.639,38,15.14,38,19.927     c-0.005,9.878-8.043,17.914-17.927,17.914c-2.991-0.001-5.952-0.755-8.564-2.18l-0.349-0.19l-0.384,0.101l-8.354,2.19     l2.226-8.131l0.11-0.403L4.55,28.867c-1.566-2.711-2.393-5.808-2.391-8.955C2.163,10.036,10.202,2,20.078,2 M20.078,1     C9.651,1,1.163,9.485,1.158,19.912c-0.002,3.333,0.869,6.588,2.525,9.455L1,39.169l10.03-2.63c2.763,1.507,5.875,2.3,9.042,2.302     h0.008c10.427,0,18.915-8.485,18.92-18.914c0-5.054-1.966-9.807-5.538-13.382C29.89,2.971,25.14,1.002,20.078,1L20.078,1z"/>
                                        </g>
                                    </g>
                                </g>
                                <path style="fill:#4bccf0;" d="M19.995,35c-2.504-0.001-4.982-0.632-7.166-1.823l-1.433-0.782l-1.579,0.414l-3.241,0.85l0.83-3.03  l0.453-1.656L7,27.485c-1.309-2.267-2.001-4.858-2-7.492C5.004,11.726,11.732,5.001,19.998,5c4.011,0.001,7.779,1.563,10.61,4.397  C33.441,12.231,35,15.999,35,20.005C34.996,28.273,28.268,35,19.995,35z"/>
                                <path style="fill:#FFFFFF;" d="M28.085,24.186c-0.443-0.221-2.621-1.293-3.026-1.44c-0.406-0.148-0.702-0.221-0.997,0.221  c-0.295,0.443-1.144,1.44-1.402,1.735c-0.258,0.295-0.516,0.332-0.96,0.111s-1.87-0.69-3.561-2.198  c-1.317-1.173-2.206-2.624-2.464-3.067s-0.028-0.682,0.194-0.903c0.199-0.199,0.443-0.518,0.664-0.776  c0.221-0.258,0.295-0.443,0.443-0.739c0.148-0.295,0.074-0.555-0.037-0.776c-0.111-0.221-0.997-2.401-1.366-3.287  c-0.359-0.863-0.725-0.746-0.997-0.759c-0.258-0.013-0.553-0.015-0.848-0.015c-0.295,0-0.776,0.111-1.181,0.555S11,14.363,11,16.542  s1.587,4.285,1.808,4.58c0.221,0.295,3.122,4.767,7.566,6.685c1.056,0.455,1.882,0.728,2.524,0.933  c1.061,0.337,2.026,0.289,2.79,0.175c0.851-0.128,2.621-1.071,2.989-2.105c0.369-1.034,0.369-1.921,0.258-2.105  C28.824,24.519,28.529,24.407,28.085,24.186z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-5 inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 98.624 98.624" style="enable-background:new 0 0 98.624 98.624;" xml:space="preserve">
                                <path fill="#4bccf0" d="M49.312,0C22.078,0,0,22.078,0,49.312s22.078,49.312,49.312,49.312s49.312-22.078,49.312-49.312  C98.624,22.078,76.546,0,49.312,0z M78.811,28.881c0.022,0,0.041,0.012,0.063,0.013L51.357,56.411  c-0.917,0.917-2.541,0.919-3.458-0.002L20.371,28.881H78.811z M18.452,30.722c0-0.015,0.008-0.027,0.008-0.041l18.705,18.705  L18.452,68.099L18.452,30.722L18.452,30.722z M20.293,70.257c-0.085,0-0.158-0.037-0.24-0.048L39.02,51.242l7.023,7.023  c0.955,0.955,2.228,1.48,3.585,1.48c1.358,0,2.629-0.527,3.584-1.48l6.796-6.796l18.788,18.788L20.293,70.257L20.293,70.257z   M80.652,68.402L61.864,49.614l18.788-18.787V68.402z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<style>

.top__title {
    max-width: 300px;
    margin-bottom: 20px;
    padding: 8px 12px;
    font-size: 2rem;
    line-height: 36px;
    color: #fff;
    background: #2183bc;
    border-radius: 23px 23px 23px 0px ;
}

.top__descr {
    max-width: 300px;
    padding: 5px 20px;
    margin-bottom: 10px;
    color: #b9daec;
    background: #2183bc;
    border-radius: 23px 23px 23px 0px ;
}

.lap-bg {
    top: 10%;
    right: 10%;
    max-width: 390px;
    width: 100%;
    -webkit-border-radius: 125px;
    -webkit-border-bottom-left-radius: 10px;
    -webkit-border-bottom-left-radius: 10px;
    -moz-border-radius: 125px;
    -moz-border-radius-topright: 10px;
    -moz-border-radius-bottomleft: 10px;
    border-radius: 125px;
    border-top-right-radius: 10px;
    border-bottom-left-radius: 10px;
    background: linear-gradient(#1063a7, #2183bc);
    overflow: hidden;
}

.top__btn {
    padding: 10px 16px;
    margin-bottom: 70px;
    border: none;
    border-radius: 32px;
    background-color: #f1f8fb;
    color: #1063a7;
}

.top__bg {
    position: fixed;
    bottom: calc(159/948*100%);
    left: calc(-300/1423*100%);
    z-index: -1;
    width: calc(1110/1920*100%);
    height: calc(356/948*100%);
    min-height: 330px;
    background: linear-gradient(#1063a7, #2183bc);
    transform: rotate(145deg);
    border-radius: 175px;
}

.footer__bg {
    max-width: 200px;
    padding: 4px 12px;
    border-radius: 100px;
    background: linear-gradient(#1063a7, #2183bc);
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

@media (max-width: 1024px) {

    .login, .register {
        color: #fff;
    }

    .top__title {
        margin-bottom: 5px;
        font-size: 1.5rem;
        line-height: 22px;
    }

    .top__btn {
        padding: 5px 10px;
        margin-bottom: 140px;
        background: #2183bc;
        color: #fff;
    }

    .top__bg {
        display: none;
        /* bottom: calc(109/948*100%); */
    }

    .lap-bg {
        top: -17%;
        right: 0;
        z-index: -5;
        max-width: 100%;
        border-radius: 0;
        -webkit-border-radius: 0;
    }
}

@media (max-width: 820px) {

    .login, .register {
        color: #2183bc;
    }

    .top__btn {
        margin-bottom: 10px;
    }

    .lap-bg {
        top: -39%;
        display: none;
    }

}
</style>
