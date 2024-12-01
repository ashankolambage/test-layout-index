<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Create Concession
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="createConcession" class="space-y-4">
                            <div>
                                <label for="name" class="block font-semibold">Name</label>
                                <input type="text" v-model="form.name" id="name" class="w-full border px-4 py-2" required>
                                <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</span>
                            </div>

                            <div>
                                <label for="description" class="block font-semibold">Description</label>
                                <textarea v-model="form.description" id="description" class="w-full border px-4 py-2"></textarea>
                            </div>

                            <div>
                                <label for="image" class="block font-semibold">Image</label>
                                <input type="file" @change="handleImageUpload" id="image" class="w-full border px-4 py-2" required>
                                <span v-if="errors.image" class="text-red-500 text-sm">{{ errors.image[0] }}</span>
                            </div>

                            <div>
                                <label for="price" class="block font-semibold">Price</label>
                                <input type="number" v-model="form.price" id="price" class="w-full border px-4 py-2" required>
                                <span v-if="errors.price" class="text-red-500 text-sm">{{ errors.price[0] }}</span>
                            </div>

                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Concession</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
</script>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    data() {
        return {
            form: {
                name: '',
                description: '',
                image: null,
                price: '',
            },
            errors: {},
        };
    },
    methods: {
        handleImageUpload(event) {
            this.form.image = event.target.files[0];
        },
        async createConcession() {
            try {
                const formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                formData.append('image', this.form.image);
                formData.append('price', this.form.price);

                const response = await axios.post(route('concessions.store'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.$toast.fire({
                    icon: 'success',
                    title: response.data.message,
                });

                this.$inertia.visit(route('concessions.index'));
            } catch (error) {
                if (error.response && error.response.status === 422 && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                    this.$toast.fire({
                        icon: 'error',
                        title: error.response.data.error,
                    });
                } else if (error.response) {
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
        },
    },
};
</script>
