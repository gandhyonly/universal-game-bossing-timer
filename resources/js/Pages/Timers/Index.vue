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
const showResetModal = ref(false);
const selectedTimer = ref(null);

const formatSpawnTime = (timer) => {
    if (!timer.spawn_at) return 'Not set';
    
    const now = new Date();
    const spawnTime = new Date(timer.spawn_at);

    console.log('Timer.spawn_at', timer.spawn_at);
    console.log('spawnTime', spawnTime);
    
    if (spawnTime <= now) {
        return 'Spawned';
    }
    
    const diff = spawnTime.getTime() - now.getTime();
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    const spawnTimeStr = spawnTime.toLocaleString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });
    console.log(spawnTimeStr);
    
    if (hours > 0) {
        return `${hours}h ${minutes}m ${seconds}s\nSpawns at ${spawnTimeStr}`;
    }
    return `${minutes}m ${seconds}s\nSpawns at ${spawnTimeStr}`;
};

const updateCountdowns = () => {
    props.timers.forEach(timer => {
        if (timer.spawn_at) {
            countdowns.value[timer.id] = formatSpawnTime(timer);
            
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
    const form = useForm({
        timer_id: timer.id
    });

    form.post(route('timers.update-spawn', { timer: timer.id }));
};

const confirmReset = (timer) => {
    selectedTimer.value = timer;
    showResetModal.value = true;
};

const cancelReset = () => {
    showResetModal.value = false;
    selectedTimer.value = null;
};

const resetTimer = () => {
    if (selectedTimer.value) {
        const form = useForm({
            timer_id: selectedTimer.value.id
        });

        form.post(route('timers.reset-spawn', { timer: selectedTimer.value.id }), {
            onSuccess: () => {
                showResetModal.value = false;
                selectedTimer.value = null;
            }
        });
    }
};

const isSpawned = (timer) => {
    return new Date(timer.spawn_at) <= new Date();
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
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">Timer Name</th>
                                        <th class="py-2 px-4 border-b">Location (Duration)</th>
                                        <th class="py-2 px-4 border-b">Level</th>
                                        <th class="py-2 px-4 border-b">Spawn Time</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="timer in timers" :key="timer.id" class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">
                                            <div class="font-medium">{{ timer.name }}</div>
                                            <div v-if="timer.note" class="text-sm text-gray-500 mt-1">{{ timer.note }}</div>
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ timer.location }} ({{ timer.delay_minutes }}m)</td>
                                        <td class="py-2 px-4 border-b">{{ timer.level }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span :class="{
                                                'text-red-500': isSpawned(timer),
                                                'text-green-500': !isSpawned(timer)
                                            }" class="whitespace-pre-line">
                                                {{ countdowns[timer.id] || formatSpawnTime(timer) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex space-x-2">
                                                <button @click="confirmReset(timer)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-sm">
                                                    Reset
                                                </button>
                                                <button @click="updateTimer(timer)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-sm">
                                                    Update
                                                </button>
                                                <Link :href="route('timers.edit', timer.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm">
                                                    Edit
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Confirmation Modal -->
        <div v-if="showResetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Reset Timer</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to clear the spawn time for "{{ selectedTimer?.name }}"?
                            This will remove the current spawn and death times.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button @click="cancelReset" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button @click="resetTimer" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 