<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

</script>

<style scoped>
.textinput{
    border: 2px solid #156CB0;
    padding: 5px;
    width: 300px;
    height: 40px;
}
</style>

<template>
    <input
        class="textinput"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
