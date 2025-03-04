<script setup>
import { nextTick, onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    nextTick(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
    <!-- <Input ref="input" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" /> -->
</template>
