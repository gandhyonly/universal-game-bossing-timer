<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    users: Array,
});

const page = usePage();
const isAdmin = computed(() => {
    return page.props.auth?.user?.is_admin || false;
});

const showDeleteModal = ref(false);
const selectedUser = ref(null);

const confirmDelete = (user) => {
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    selectedUser.value = null;
};

const deleteUser = () => {
    if (selectedUser.value) {
        const form = useForm({});
        form.delete(route('users.destroy', selectedUser.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedUser.value = null;
            }
        });
    }
};

const getRoleBadgeClass = (role) => {
    switch(role) {
        case 'admin':
            return 'bg-red-100 text-red-800';
        case 'editor':
            return 'bg-blue-100 text-blue-800';
        case 'viewer':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
                <Link
                    :href="route('users.create')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    Create New User
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
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Email</th>
                                        <th class="py-2 px-4 border-b">Role</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">{{ user.name }}</td>
                                        <td class="py-2 px-4 border-b">{{ user.email }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span :class="getRoleBadgeClass(user.role)" class="px-2 py-1 rounded-full text-xs font-semibold">
                                                {{ user.role || 'viewer' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <button 
                                                v-if="user.id !== page.props.auth.user.id"
                                                @click="confirmDelete(user)" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm"
                                            >
                                                Delete
                                            </button>
                                            <span v-else class="text-gray-400 text-sm">Current User</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete User</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete user "{{ selectedUser?.name }}" ({{ selectedUser?.email }})?
                            This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button @click="cancelDelete" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button @click="deleteUser" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

