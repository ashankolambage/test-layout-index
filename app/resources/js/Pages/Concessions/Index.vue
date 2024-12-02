<template>
    <Head title="Concessions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Concessions</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-end mb-4">
                        <a
                            :href="route('concessions.create')" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Add New Concession
                        </a>

                        </div>
                                        
                        <div v-if="concessions.length == 0" class="text-center text-gray-500">
                            No concessions available.
                        </div>

                        <table v-else class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">#</th>
                                    <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Concession ID</th>
                                    <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                    <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Price</th>
                                    <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="(concession, index) in concessions" :key="concession.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">{{ concession.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">{{ concession.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">Rs.{{ concession.price }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">
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