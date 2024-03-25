<template>
  <header class="header-tariffs">
    <div class="header-tariffs__container">
      <a href="#" class="header-tariffs__logo">
        <div>
          <img src="../../img/logo.png" alt="logo" />
        </div>
        <div>
          <img src="../../img/logo.png" alt="logo" />
        </div>
      </a>
      <div class="header-tariffs__btnbox">
        <button class="header-tariffs__button">
            <a href="https://wa.me/77089152629" class="supp text-inherit">Поддержка</a>
        </button>
        <button class="header-tariffs__button">
            <a href="https://feedback.kz/register">Регистрация</a>
        </button>
      </div>
    </div>
  </header>
  <main>
    <section class="tariffs">
      <h2 class="tariffs__heading">Тарифные планы и цены</h2>
      <div class="tariffs__wrapper">
        <ul class="tariffs__list">
          <li class="tariffs__item">
            <h3 class="tariffs__heading-item">Старт</h3>
            <p class="tariffs__desc-item">на 1 месяц 10&nbsp;000 тенге</p>
            <button class="tariffs__button-item">
                <a @click.prevent="status_pay('10000','Cтарт на 1 месяц','1')" href="#" class="buy-link">
                Купить
                </a>
            </button>
          </li>
          <li class="tariffs__item">
            <h3 class="tariffs__heading-item">Профи</h3>
            <p class="tariffs__desc-item">на 3 месяца 30&nbsp;000 тенге</p>
            <button class="tariffs__button-item">
                <a @click.prevent="status_pay('30000','Профи на 3 месяцев','3')" href="#" class="buy-link">
                    Купить
                </a>
            </button>
          </li>
          <li class="tariffs__item">
            <h3 class="tariffs__heading-item">Бизнес</h3>
            <p class="tariffs__desc-item">на 6 месяцев 60&nbsp;000 тенге</p>
            <button class="tariffs__button-item">
                <a @click.prevent="status_pay('60000','Бизнес на 6 месяцев','6')" href="#" class="buy-link">
                    Купить
                </a>
            </button>
          </li>
          <li class="tariffs__item">
            <h3 class="tariffs__heading-item">Ультра</h3>
            <p class="tariffs__desc-item">на 1 год 120&nbsp;000 тенге</p>
            <button class="tariffs__button-item">
                <a @click.prevent="status_pay('120000','Ультра на 12 месяцев','12')" href="#" class="buy-link">
                    Купить
                </a>
            </button>
          </li>
        </ul>
        <p class="tariffs__desc">
          Если у Вас остались вопросы Вы можете написать или позвонить в
          поддержку
        </p>
      </div>
      <a href="https://wa.me/77089152629">
        <img src="../../img/messanger.png" class="tariffs__messenger" />
    </a>
    </section>
  </main>
  <footer class="footer-tariffs">
    <div class="footer-tariffs__container">
      <ul class="footer-tariffs__list">
        <li class="footer-tariffs__item">
          <a href="#">Главная</a>
        </li>
        <li class="footer-tariffs__item">
          <a href="https://clck.ru/37cEGV" target="_blank">Политика конфиденциальности</a>
        </li>
        <li class="footer-tariffs__item">
          <a href="https://clck.ru/37cEHc" target="_blank">Публичная оферта</a>
        </li>
        <li class="footer-tariffs__item">
          <a href="https://clck.ru/37chPb" target="_blank">Порядок оплаты</a>
        </li>
      </ul>
      <ul class="footer-tariffs__list">
        <li class="footer-tariffs__item">Контакты</li>
        <li class="footer-tariffs__item">
          <a href="tel:+77779901919">+7 777 990 1919</a>
        </li>
        <li class="footer-tariffs__item">
          <address>г. Астана, ул. Акмешит 11</address>
        </li>
      </ul>
      <div class="footer-tariffs__payment">
        <img src="../../img/payment.png" alt="visa" />
        <img src="../../img/payment.png" alt="mastercard" />
      </div>
    </div>
  </footer>
</template>

<script lang="ts">
import { defineComponent,onMounted } from "vue";
import { usePage, useForm } from '@inertiajs/vue3';
export default defineComponent({
  props: {
    auth: {
        type: Array,
        default: []
    },
  },
  data() {
    return {
      
    };
  },
  methods: {
    status_pay(amount,description,pg_duration){
      const user = usePage().props.auth.user;
      const pay_inf = useForm({
        pg_amount: amount,
        pg_description: description,
        duration: pg_duration,
        user_id: String(user.id)
      });

      pay_inf.post(route('subscription.init'),{
        onFinish: () => {
          if(this.auth.user.subscription_link){ 
            window.open(this.auth.user.subscription_link, '_self')
          }
        },
      });
      
    }
  }
  
});

</script>

<style scoped>
@font-face {
  font-family: "ALS Schlange sans";
  src: url("../../fonts/ALSSchlangesans-Bold.eot");
  src: local("ALS Schlange sans Bold"),
    local("../../fonts/ALSSchlangesans-Bold"),
    url("../../fonts/ALSSchlangesans-Bold.eot?#iefix")
      format("embedded-opentype"),
    url("../../fonts/ALSSchlangesans-Bold.woff2") format("woff2"),
    url("../../fonts/ALSSchlangesans-Bold.woff") format("woff"),
    url("../../fonts/ALSSchlangesans-Bold.ttf") format("truetype");
  font-weight: bold;
  font-style: normal;
}

@font-face {
  font-family: "ALS Schlange sans";
  src: url("../../fonts/ALSSchlangesans-Black.eot");
  src: local("ALS Schlange sans Black"),
    local("../../fonts/ALSSchlangesans-Black"),
    url("../../fonts/ALSSchlangesans-Black.eot?#iefix")
      format("embedded-opentype"),
    url("../../fonts/ALSSchlangesans-Black.woff2") format("woff2"),
    url("../../fonts/ALSSchlangesans-Black.woff") format("woff"),
    url("../../fonts/ALSSchlangesans-Black.ttf") format("truetype");
  font-weight: 900;
  font-style: normal;
}

@font-face {
  font-family: "ALS Schlange sans";
  src: url("../../fonts/ALSSchlangesans-Light.eot");
  src: local("ALS Schlange sans Light"),
    local("../../fonts/ALSSchlangesans-Light"),
    url("../../fonts/ALSSchlangesans-Light.eot?#iefix")
      format("embedded-opentype"),
    url("../../fonts/ALSSchlangesans-Light.woff2") format("woff2"),
    url("../../fonts/ALSSchlangesans-Light.woff") format("woff"),
    url("../../fonts/ALSSchlangesans-Light.ttf") format("truetype");
  font-weight: 300;
  font-style: normal;
}

@font-face {
  font-family: "ALS Schlange sans";
  src: url("../../fonts/ALSSchlangesans-Thin.eot");
  src: local("ALS Schlange sans Thin"),
    local("../../fonts/ALSSchlangesans-Thin"),
    url("../../fonts/ALSSchlangesans-Thin.eot?#iefix")
      format("embedded-opentype"),
    url("../../fonts/ALSSchlangesans-Thin.woff2") format("woff2"),
    url("../../fonts/ALSSchlangesans-Thin.woff") format("woff"),
    url("../../fonts/ALSSchlangesans-Thin.ttf") format("truetype");
  font-weight: 100;
  font-style: normal;
}

@font-face {
  font-family: "ALS Schlange sans";
  src: url("../../fonts/ALSSchlangesans.eot");
  src: local("ALS Schlange sans"), local("../../fonts/ALSSchlangesans"),
    url("../../fonts/ALSSchlangesans.eot?#iefix")
      format("embedded-opentype"),
    url("../../fonts/ALSSchlangesans.woff2") format("woff2"),
    url("../../fonts/ALSSchlangesans.woff") format("woff"),
    url("../../fonts/ALSSchlangesans.ttf") format("truetype");
  font-weight: normal;
  font-style: normal;
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  list-style: none;
  text-decoration: none;
  font-family: "ALS Schlange sans", sans-serif;
  color: white;
}

button {
  background: none;
  border: none;
  outline: none;
}

.header-tariffs {
  padding: 42px 0;
  background: linear-gradient(90deg, #cdffd8, #94b9ff);
}

.header-tariffs__container {
  display: flex;
  align-items: center;
  justify-content: space-between;

  max-width: 1696px;
  margin: 0 auto;
  padding: 0 15px;
}

.header-tariffs__logo {
  display: flex;
  align-items: center;
  gap: 15px;
}

.header-tariffs__logo div:nth-child(1) {
  display: block;
  width: 50px;
  height: 50px;
  overflow: hidden;
}

.header-tariffs__logo div:nth-child(1) img {
  width: 100%;
  height: 100%;

  transform: scale(1.35) translateY(5px);
}

.header-tariffs__logo div:nth-child(2) {
  display: block;
  width: 294px;
  height: 45px;
  padding-right: 10px;
  overflow: hidden;
}

.header-tariffs__logo div:nth-child(2) img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: left 5px bottom -16px;
  transform: scale(1.5);
}

.header-tariffs__btnbox {
  display: flex;
  align-items: center;
  gap: 35px;
}

.header-tariffs__button {
  padding: 8px 15px;
  background: #015bcf;
  border-radius: 10px;

  font-weight: 700;
  font-size: 26px;
  line-height: 100%;
}

.tariffs {
  position: relative;

  padding-top: 75px;

  background: url("../../img/bg.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center top;
}

.tariffs__heading {
  font-weight: 700;
  font-size: 59px;
  line-height: 120%;

  text-align: center;
  padding-bottom: 75px;
}

.tariffs__wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;

  padding: 81px 75px;
  background: white;
  border-top-right-radius: 50px;
  border-top-left-radius: 50px;
}

.tariffs__list {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
}

.tariffs__item {
  display: flex;
  flex-direction: column;
  align-items: center;

  width: 260px;
  min-height: 324px;
  padding: 24px 32px;
  background: #004aad;
}

.tariffs__heading-item {
  font-weight: 700;
  font-size: 40px;
  line-height: 160%;
  margin-bottom: 30px;
  text-align: center;
}

.tariffs__desc-item {
  font-weight: 700;
  font-size: 26px;
  line-height: 160%;
  margin-bottom: 40px;
  text-align: center;
}

.tariffs__button-item {
  padding: 8px 15px;
  border-radius: 10px;
  background: white;
}

.buy-link {
  font-weight: 700;
  font-size: 24px;
  line-height: 120%;
  color: #156cb0;
}

.tariffs__desc {
  max-width: 1260px;

  font-weight: 300;
  font-size: 50px;
  line-height: 150%;
  color: #156cb0;
  text-align: center;
}

.footer-tariffs {
  padding: 50px 0 285px;
  background: #015bcf;
}

.footer-tariffs__container {
  display: flex;
  align-items: center;

  max-width: 1323px;
  margin: 0 auto;
  padding: 0 15px;
}

.footer-tariffs__list {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.footer-tariffs__list:first-of-type {
  margin-right: auto;
}

.footer-tariffs__list:nth-of-type(2) {
  margin-right: 126px;
}

.footer-tariffs__item {
  font-weight: 300;
  font-size: 20px;
  line-height: 120%;
}

address {
  font-style: normal;
}

.footer-tariffs__payment {
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 8px 12px;
  background: white;
}

.footer-tariffs__payment img:nth-child(1) {
  width: 114px;
  height: 35px;
  object-fit: cover;
  object-position: top;
}

.footer-tariffs__payment img:nth-child(2) {
  width: 94px;
  height: 55px;
  object-fit: cover;
  object-position: bottom;
}

.tariffs__messenger {
  position: fixed;
  right: 58px;
  bottom: 31px;

  width: 90px;
  height: 90px;

  transform: scaleX(-1);

  cursor: pointer;
}

@media (max-width: 768px) {
  .header-tariffs {
    padding: 15px;
  }

  .header-tariffs__logo div:nth-child(2) {
    display: none;
  }

  .header-tariffs__btnbox {
    gap: 10px;
  }

  .header-tariffs__button {
    font-size: 20px;
  }

  .tariffs__messenger {
    right: 20px;
    bottom: 20px;
    width: 50px;
    height: 50px;
  }

  .footer-tariffs__container {
    flex-direction: column;
    align-items: center;
    gap: 50px;
  }

  .footer-tariffs__container * {
    margin: 0 !important;
    text-align: center;
  }
}

@media (max-width: 425px) {
  .header-tariffs__container {
    flex-wrap: wrap;
    gap: 20px;
  }

  .header-tariffs__logo {
    width: 100%;
  }

  .header-tariffs__logo div:nth-child(1) {
    min-width: 50px;
  }

  .header-tariffs__logo div:nth-child(2) {
    display: block;

    width: 100%;
  }

  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom -16px;
  }

  .header-tariffs__btnbox {
    width: 100%;
  }

  .header-tariffs__button {
    width: 100%;
  }

  .tariffs__desc {
    max-width: auto;
    font-size: 35px;
    line-height: 130%;
  }
}

@media (max-width: 375px) {
  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom -10px;
  }
}

@media (max-width: 330px) {
  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom -4px;
  }

  .header-tariffs__button {
    font-size: 15px;
  }
}

@media (max-width: 300px) {
  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom 0px;
  }
}
@media (max-width: 288px) {
  .header-tariffs__logo {
    gap: 5px;
  }

  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom 0px;
    transform: scale(1.53);
  }
}

@media (max-width: 270px) {
  .header-tariffs__logo div:nth-child(2) img {
    object-position: left 5px bottom 4px;
    transform: scale(1.53);
  }
}
</style>
