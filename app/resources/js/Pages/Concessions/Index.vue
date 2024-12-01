<template>
    <Head title="Concessions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Concessions</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Concession List</h3>
                    <a
                        :href="route('concessions.create')" 
                        class="bg-blue-500 text-white px-4 py-2 rounded">
                        Add New Concession
                    </a>
                </div>
                                
                <div v-if="!concessions.length" class="text-center">
                    <p class="text-gray-900">Loading concessions...</p>
                </div>

                <div v-else>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Name</th>
                                        <th class="px-4 py-2">Price</th>
                                        <th class="px-4 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="concession in concessions" :key="concession.id">
                                        <td class="border px-4 py-2">{{ concession.name }}</td>
                                        <td class="border px-4 py-2">Rs.{{ concession.price }}</td>
                                        <td class="border px-4 py-2">
                                            <a :href="route('concessions.edit', concession.id)" class="text-blue-500">
                                                Edit
                                            </a>
                                            |
                                            <button @click="deleteConcession(concession.id)" class="text-red-500 ml-2">
                                                Delete
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
</script>

<script>
export default {
    props: {
        concessions: Array,
    },

    methods: {
        deleteConcession(id) {
            if (confirm('Are you sure you want to delete this concession?')) {
                this.$inertia.delete(route('concessions.destroy', id));
            }
        },
        async deleteConcession(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });
            
            if (result.isConfirmed) {
                try {
                    const response = await axios.delete(route('concessions.destroy', id));
                    
                    this.$toast.fire({
                        icon: 'success',
                        title: response.data.message,
                    });

                    this.$inertia.visit(route('concessions.index'));
                } catch (error) {
                    if (error.response) {
                        this.$toast.fire({
                            icon: 'error',
                            title: error.response.data.error || 'An unknown error occurred.',
                        });
                    } else {
                        this.$toast.fire({
                            icon: 'error',
                            title: 'An unknown error occurred. Please try again.',
                        });
                    }
                    console.error(error);
                }
            }
        }
    }
}
</script>