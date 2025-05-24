<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    timers: Array,
});

const countdowns = ref({});
const alarms = ref({});
const audio = new Audio('/sounds/alarm.mp3');

const formatTime = (time) => {
    if (!time) return 'Not set';
    return new Date(time).toLocaleString();
};

const timeUntilSpawn = (spawnAt) => {
    if (!spawnAt) return 'Not set';
    const now = new Date();
    const spawn = new Date(spawnAt);
    const diff = spawn - now;
    
    if (diff <= 0) {
        const elapsed = Math.abs(diff);
        const hours = Math.floor(elapsed / (1000 * 60 * 60));
        const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
        
        if (hours > 0) {
            return `Already Spawn - ${hours}h ${minutes}m ${seconds}s`;
        }
        return `Already Spawn - ${minutes}m ${seconds}s`;
    }
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    if (hours > 0) {
        return `${hours}h ${minutes}m ${seconds}s`;
    }
    return `${minutes}m ${seconds}s`;
};

const updateCountdowns = () => {
    props.timers.forEach(timer => {
        if (timer.spawn_at) {
            countdowns.value[timer.id] = timeUntilSpawn(timer.spawn_at);
            
            // Check for alarm
            const now = new Date();
            const spawn = new Date(timer.spawn_at);
            const diff = spawn - now;
            
            // If less than 2 minutes until spawn and alarm not triggered yet
            if (diff > 0 && diff <= 120000 && !alarms.value[timer.id]) {
                alarms.value[timer.id] = true;
                audio.play();
            }
        }
    });
};

let countdownInterval;

onMounted(() => {
    updateCountdowns();
    countdownInterval = setInterval(updateCountdowns, 1000);
});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
});

const updateTimer = (timer) => {
    const form = useForm({});
    form.post(route('timers.update-spawn', timer.id));
};
</script>

<template>
    <Head title="Timers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Timers</h2>
                <Link
                    :href="route('timers.create')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    Add New Timer
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Timer Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Level
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Location (Duration)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Spawn Time
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="timer in timers" :key="timer.id" :class="{
                                        'bg-yellow-100': alarms[timer.id]
                                    }">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ timer.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ timer.level ? `Lv.${timer.level}` : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ timer.location || 'Not set' }}
                                                <span class="text-gray-500">({{ timer.delay_minutes }}m)</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm" :class="{
                                                'text-green-600': new Date(timer.spawn_at) <= new Date(),
                                                'text-red-600': new Date(timer.spawn_at) > new Date(),
                                                'font-bold': alarms[timer.id]
                                            }">
                                                {{ countdowns[timer.id] || timeUntilSpawn(timer.spawn_at) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                @click="updateTimer(timer)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Update
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 