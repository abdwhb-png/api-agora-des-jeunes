<script setup lang="ts">
import ActionMessage from '@/components/jetstream/ActionMessage.vue';
import ActionSection from '@/components/jetstream/ActionSection.vue';
import Checkbox from '@/components/jetstream/Checkbox.vue';
import ConfirmationModal from '@/components/jetstream/ConfirmationModal.vue';
import DangerButton from '@/components/jetstream/DangerButton.vue';
import DialogModal from '@/components/jetstream/DialogModal.vue';
import FormSection from '@/components/jetstream/FormSection.vue';
import InputError from '@/components/jetstream/InputError.vue';
import InputLabel from '@/components/jetstream/InputLabel.vue';
import PrimaryButton from '@/components/jetstream/PrimaryButton.vue';
import SecondaryButton from '@/components/jetstream/SecondaryButton.vue';
import SectionBorder from '@/components/jetstream/SectionBorder.vue';
import TextInput from '@/components/jetstream/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from './ui/button';

const props = defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const createApiTokenForm = useForm({
    name: '',
    permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
    permissions: [],
});

const deleteApiTokenForm = useForm({});

const currentToken = ref(null);
const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);

const createApiToken = () => {
    createApiTokenForm.post(route('api-tokens.store'), {
        preserveScroll: true,
        onSuccess: (page) => {
            currentToken.value = page.props.flash.token;
            displayingToken.value = true;
            createApiTokenForm.reset();
        },
    });
};

const manageApiTokenPermissions = (token) => {
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;
};

const updateApiToken = () => {
    updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor.value.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (managingPermissionsFor.value = null),
    });
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted.value.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (apiTokenBeingDeleted.value = null),
    });
};

const copyToken = () => {
    if (currentToken.value) {
        navigator.clipboard.writeText(currentToken.value);
        alert('Copied!');
    }
};
</script>

<template>
    <div>
        <!-- Generate API Token -->
        <FormSection @submitted="createApiToken">
            <template #title> Create API Token </template>

            <template #description> API tokens allow third-party services to authenticate with our application on your behalf. </template>

            <template #form>
                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" v-model="createApiTokenForm.name" type="text" class="mt-1 block w-full" autofocus />
                    <InputError :message="createApiTokenForm.errors.name" class="mt-2" />
                </div>

                <!-- Token Permissions -->
                <div v-if="availablePermissions.length > 0" class="col-span-6">
                    <InputLabel for="permissions" value="Permissions" />

                    <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div v-for="permission in availablePermissions" :key="permission">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="createApiTokenForm.permissions" :value="permission" />
                                <span class="ms-2 text-sm text-gray-600">{{ permission }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="createApiTokenForm.recentlySuccessful" class="me-3"> Created. </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing">
                    Create
                </PrimaryButton>
            </template>
        </FormSection>

        <div v-if="tokens.length > 0">
            <SectionBorder />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title> Manage API Tokens </template>

                    <template #description> You may delete any of your existing tokens if they are no longer needed. </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div v-for="token in tokens" :key="token.id" class="flex items-center justify-between">
                                <Button
                                    variant="link"
                                    class="break-all"
                                    @click="
                                        currentToken = token.token;
                                        displayingToken = true;
                                    "
                                >
                                    {{ token.name }}
                                </Button>

                                <div class="ms-2 flex items-center">
                                    <div v-if="token.last_used_ago" class="text-sm text-gray-400">Last used {{ token.last_used_ago }}</div>

                                    <button
                                        v-if="availablePermissions.length > 0"
                                        class="ms-6 cursor-pointer text-sm text-gray-400 underline"
                                        @click="manageApiTokenPermissions(token)"
                                    >
                                        Permissions
                                    </button>

                                    <button class="ms-6 cursor-pointer text-sm text-red-500" @click="confirmApiTokenDeletion(token)">Delete</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Token Value Modal -->
        <DialogModal
            :show="displayingToken"
            @close="
                displayingToken = false;
                currentToken = null;
            "
        >
            <template #title> API Token </template>

            <template #content>
                <div>Please copy your new API token. For your security, it won't be shown again.</div>

                <div v-if="currentToken" class="mt-4 break-all rounded bg-gray-100 px-4 py-2 font-mono text-sm text-gray-500">
                    {{ currentToken }}
                </div>
            </template>

            <template #footer>
                <div class="flex gap-3">
                    <SecondaryButton
                        @click="
                            displayingToken = false;
                            currentToken = null;
                        "
                    >
                        Close
                    </SecondaryButton>
                    <PrimaryButton @click="copyToken()"> Copy </PrimaryButton>
                </div>
            </template>
        </DialogModal>

        <!-- API Token Permissions Modal -->
        <DialogModal :show="managingPermissionsFor != null" @close="managingPermissionsFor = null">
            <template #title> API Token Permissions </template>

            <template #content>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div v-for="permission in availablePermissions" :key="permission">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="updateApiTokenForm.permissions" :value="permission" />
                            <span class="ms-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="managingPermissionsFor = null"> Cancel </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateApiTokenForm.processing }"
                    :disabled="updateApiTokenForm.processing"
                    @click="updateApiToken"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Delete Token Confirmation Modal -->
        <ConfirmationModal :show="apiTokenBeingDeleted != null" @close="apiTokenBeingDeleted = null">
            <template #title> Delete API Token </template>

            <template #content> Are you sure you would like to delete this API token? </template>

            <template #footer>
                <SecondaryButton @click="apiTokenBeingDeleted = null"> Cancel </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteApiTokenForm.processing }"
                    :disabled="deleteApiTokenForm.processing"
                    @click="deleteApiToken"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>
