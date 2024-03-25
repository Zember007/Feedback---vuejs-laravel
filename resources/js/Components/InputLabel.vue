<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<style scoped>
.Label-form{
    color: #156CB0;
    font-size: 22px;
}
</style>

<template>
    <label class="Label-form">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
