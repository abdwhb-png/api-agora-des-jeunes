<script setup lang="ts">
import { useToast } from '@/components/ui/toast/use-toast';
import { cn } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from '@/components/ui/combobox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';

const props = defineProps({
    data: Object,
    models: Object,
});

const { toast } = useToast();
const form = useForm({
    groq_model: props.data?.groq_model || null,
    groq_api_key: props.data?.groq_api_key || '',
});

const submit = () => {
    form.put(route('ai.update'), {
        preserveScroll: true,
        onSuccess: (page) => {
            toast({
                title: 'Success',
                description: page.props.flash.status,
                class: 'bg-success',
                variant: 'success',
            });
        },
    });
};
</script>

<template>
    <Card class="mx-auto min-w-[450px]">
        <CardHeader>
            <CardTitle>Groq API</CardTitle>
            <CardDescription>Manage qroq api param here.</CardDescription>
        </CardHeader>
        <CardContent>
            <form>
                <div class="grid w-full items-center gap-4">
                    <div class="flex flex-col space-y-1.5">
                        <Label for="api_key">API Key</Label>
                        <Input id="api_key" placeholder="Enter your api key" v-model="form.groq_api_key" />
                        <InputError :message="form.errors.groq_api_key" />
                    </div>

                    <div class="flex flex-col space-y-1.5">
                        <Label for="model">Used model</Label>
                        <Combobox v-model="form.groq_model" by="id" class="w-full">
                            <ComboboxAnchor as-child>
                                <ComboboxTrigger as-child>
                                    <Button variant="outline" class="w-full justify-between">
                                        {{ form.groq_model ?? 'Select model' }}
                                        <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                    </Button>
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                            <ComboboxList>
                                <div class="relative w-full items-center">
                                    <ComboboxInput
                                        class="h-10 rounded-none border-0 border-b pl-9 focus-visible:ring-0"
                                        placeholder="Select model..."
                                    />
                                    <span class="absolute inset-y-0 start-0 flex items-center justify-center px-3">
                                        <Search class="size-4 text-muted-foreground" />
                                    </span>
                                </div>

                                <ComboboxEmpty> No model found. </ComboboxEmpty>

                                <ComboboxGroup class="max-h-60 overflow-y-auto">
                                    <ComboboxItem v-for="model in models?.data" :key="model.id" :value="model.id" :disabled="!model.active">
                                        {{ model.id }}
                                        <span class="text-xs text-muted-foreground">{{ `(${model.owned_by})` }}</span>
                                        <ComboboxItemIndicator>
                                            <Check v-if="model.id === form.groq_model" :class="cn('ml-auto h-4 w-4')" />
                                        </ComboboxItemIndicator>
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxList>
                        </Combobox>

                        <InputError :message="form.errors.groq_model" />
                    </div>
                </div>
            </form>
        </CardContent>
        <CardFooter class="flex justify-between px-6 pb-6">
            <Button variant="outline"> Cancel </Button>
            <Button type="submit" @click="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : 'Save' }}
            </Button>
        </CardFooter>
    </Card>
</template>
