<script setup lang="ts">
import GroqManager from '@/components/partials/GroqManager.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ai_setting: Object,
    groq_models: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'AI Manager',
        href: route('ai.index'),
    },
];
</script>

<template>
    <Head title="AI Manager" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <GroqManager :data="ai_setting" :models="groq_models" />
        </div>
    </AppLayout>
</template>
