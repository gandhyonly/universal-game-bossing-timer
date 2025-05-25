<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Game Bossing Timer - Login" />

        <div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8 bg-gray-800 p-8 rounded-lg shadow-2xl">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                        Game Bossing Timer
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-400">
                        Track your boss respawn timers across different games
                    </p>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-400 bg-green-900/50 p-3 rounded">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <InputLabel for="email" value="Email" class="text-gray-300" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full bg-gray-700 border-gray-600 text-white"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Enter your email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" class="text-gray-300" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full bg-gray-700 border-gray-600 text-white"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" class="bg-gray-700 border-gray-600" />
                            <span class="ms-2 text-sm text-gray-300">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-indigo-400 hover:text-indigo-300"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <div>
                        <PrimaryButton
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Sign in
                        </PrimaryButton>
                    </div>

                    <div class="text-center">
                        <Link
                            :href="route('register')"
                            class="text-sm text-indigo-400 hover:text-indigo-300"
                        >
                            Don't have an account? Register here
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
