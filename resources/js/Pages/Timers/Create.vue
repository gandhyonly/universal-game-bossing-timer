<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    delay_minutes: '',
    location: '',
});

const submit = () => {
    form.post(route('timers.store'));
};
</script>

<template>
    <Head title="Create Timer" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Timer</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Timer Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="delay_minutes" value="Respawn Delay (minutes)" />
                                <TextInput
                                    id="delay_minutes"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.delay_minutes"
                                    required
                                    min="1"
                                />
                                <InputError class="mt-2" :message="form.errors.delay_minutes" />
                            </div>

                            <div>
                                <InputLabel for="location" value="Location (optional)" />
                                <TextInput
                                    id="location"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.location"
                                />
                                <InputError class="mt-2" :message="form.errors.location" />
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Create Timer
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 