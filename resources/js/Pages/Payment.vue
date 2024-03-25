<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import SurveyButton from '@/Components/SurveyButton.vue';

onMounted(() => {
    
})
const user = usePage().props.auth.user;

const tariff_name = user.subscription_name

const tariff_duration = () => {
    var fD = new Date(user.subscription)
    var bD = new Date(fD.getFullYear(),fD.getMonth() +  user.subscription_duration,fD.getDate())
    var text =`до ${getDate(bD)} осталось ${getRemainsDays(bD, fD)}`
    console.log(getRemainsDays(bD, fD));
    
    return text
}

const getRemainsDays = (bD, fD) => {
    let days = (bD - fD) / 1000 / 60 / 60 / 24
    var text = ''
    if(days < 5 && days > 1){
        text = 'дня'
    } else if(days == 1) {
        text = 'день'
    } else {
        text = 'дней'
    }
    return `${days} ${text}`
}

const getDate = (D) => {
    return `${String(D.getDate()).length == 1? '0'+String(D.getDate()): D.getDate()}.${String(D.getMonth() + 1).length == 1? '0'+String(D.getMonth() + 1) : (D.getMonth() + 1)}.${D.getFullYear()}`
}

</script>

<style scoped lang="scss">
.payment__top {
    width: 100%;
    padding: 6px 0;
    background: linear-gradient(#E7F1FF, #E7F1FF) padding-box,
        radial-gradient(circle at 0% 0%, rgb(46, 48, 139) 0%, rgb(25, 106, 189) 33.333%, rgb(51, 186, 194) 66.667%, rgb(72, 233, 241) 100%) border-box;
    border-bottom: 5px solid transparent;
}

.payment__heading {
    font-weight: 700;
    font-size: 32px;
    color: #156CB0;
}

.payment__description{
    color: #156cb0;
    font-weight: 300;
    font-size: 22px;
    & strong{
        font-size: 23px; 
        font-weight: 500;
    }
}

</style>

<template>
    <DashboardLayout>
        <div class="payment__top">
            <h2 class="payment__heading">Оплата</h2>
        </div>
        <div class="flex flex-col gap-4 py-4">
            <div class="flex flex-col gap-2">
                <div class="payment__description">Ваш тариф: <strong>{{ tariff_name }}</strong></div>
                <div class="payment__description">Срок: <strong>{{ tariff_duration() }}</strong></div>
            </div>

            <SurveyButton>
                <Link :href="route('tariffs')">Продлить тариф</Link>
            </SurveyButton>
        </div>

    </DashboardLayout>
</template>
