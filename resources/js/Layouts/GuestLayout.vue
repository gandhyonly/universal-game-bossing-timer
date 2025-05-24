<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-900">
        <div class="flex justify-center pt-6">
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-indigo-500" />
            </Link>
        </div>

        <slot />
    </div>
</template>
