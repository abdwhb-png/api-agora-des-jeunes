<script setup lang="ts">
import ApiTokenManager from '@/components/ApiTokenManager.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Token Manager',
        href: route('api-tokens.index'),
    },
];
</script>

<template>
    <Head title="Token Manager" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="px-5 md:px-20">
                <ApiTokenManager :tokens="tokens" :available-permissions="availablePermissions" :default-permissions="defaultPermissions" />
            </div>
        </div>
    </AppLayout>
</template>
