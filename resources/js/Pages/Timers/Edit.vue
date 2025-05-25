<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    timer: Object
});

const form = useForm({
    name: props.timer.name,
    level: props.timer.level,
    delay_minutes: props.timer.delay_minutes,
    location: props.timer.location,
    died_at: props.timer.died_at ? new Date(props.timer.died_at).toISOString().slice(0, 16) : null,
    note: props.timer.note
});

const submit = () => {
    form.put(route('timers.update', props.timer.id));
};
</script>

<template>
    <Head title="Edit Timer" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Timer</h2>
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
                                <InputLabel for="note" value="Note" />
                                <textarea
                                    id="note"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.note"
                                    rows="3"
                                    placeholder="Add any additional notes about this timer..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.note" />
                            </div>

                            <div>
                                <InputLabel for="level" value="Level" />
                                <TextInput
                                    id="level"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.level"
                                    min="1"
                                    max="100"
                                />
                                <InputError class="mt-2" :message="form.errors.level" />
                            </div>

                            <div>
                                <InputLabel for="delay_minutes" value="Delay (minutes)" />
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
                                <InputLabel for="location" value="Location" />
                                <TextInput
                                    id="location"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.location"
                                />
                                <InputError class="mt-2" :message="form.errors.location" />
                            </div>

                            <div>
                                <InputLabel for="died_at" value="Death Time" />
                                <TextInput
                                    id="died_at"
                                    type="datetime-local"
                                    class="mt-1 block w-full"
                                    v-model="form.died_at"
                                />
                                <InputError class="mt-2" :message="form.errors.died_at" />
                                <p class="mt-1 text-sm text-gray-500">
                                    Leave empty to clear the death time
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Save Changes</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 