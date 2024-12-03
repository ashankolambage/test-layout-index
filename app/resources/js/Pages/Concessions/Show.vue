<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Concession Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        <div>
                            <strong>Name:</strong> {{ concession.name }}
                        </div>
                        <div>
                            <strong>Description:</strong> {{ concession.description || 'No description provided.' }}
                        </div>
                        <div v-if="concession.image">
                            <strong>Image:</strong>
                            <img :src="$baseImageUrl + concession.image" alt="Concession Image" class="w-32 h-32 object-cover rounded mt-2">
                        </div>
                        <div>
                            <strong>Price:</strong> Rs.{{ concession.price }}
                        </div>
                        <br>
                        <a :href="route('concessions.index')" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<script>
export default {
    props: {
        concession: Object,
    },
};
</script>
