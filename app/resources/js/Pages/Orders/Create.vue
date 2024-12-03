<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Create Order
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-6 bg-gray-50">
                        <form @submit.prevent="createOrder" class="space-y-6">
                            <div>
                                <label class="block text-lg font-semibold text-gray-700">Select Concessions</label>
                                <div v-for="concession in concessions" :key="concession.id" class="flex items-center space-x-4 mt-4">
                                    <div class="flex-shrink-0">
                                        <img
                                            v-if="concession.image"
                                            :src="$baseImageUrl + concession.image"
                                            alt="Concession Image"
                                            class="w-16 h-16 object-cover rounded-md"
                                        />
                                    </div>

                                    <input
                                        type="checkbox"
                                        :value="concession.id"
                                        v-model="selectedConcessions"
                                        :id="'concession_' + concession.id"
                                        class="h-5 w-5 text-indigo-600 border-gray-300 rounded"
                                    />
                                    <label :for="'concession_' + concession.id" class="flex-1 text-gray-600 font-medium">
                                        {{ concession.name }} - Rs.{{ concession.price }}
                                    </label>

                                    <input
                                        type="number"
                                        v-model="concessionQuantities[concession.id]"
                                        :id="'quantity_' + concession.id"
                                        :min="1"
                                        class="w-20 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Qty"
                                    />
                                </div>
                                <span v-if="errors.concessions" class="text-red-500 text-sm mt-2">
                                    {{ errors.concessions[0] }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-lg font-semibold text-gray-700">Send to Kitchen Time</label>
                                <input
                                    type="datetime-local"
                                    v-model="sendToKitchenTime"
                                    :min="minDateTime"
                                    class="w-40 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                                <br>
                                <span v-if="errors.send_to_kitchen_time" class="text-red-500 text-sm mt-2">
                                    {{ errors.send_to_kitchen_time[0] }}
                                </span>
                            </div>

                            <!-- Total Amount -->
                            <div class="mt-4">
                                <label class="block text-lg font-semibold text-gray-700">Total Amount</label>
                                <div class="text-xl font-semibold text-indigo-600">
                                    Rs.{{ totalAmount.toFixed(2) }}
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Create Order
                                </button>
                            </div>
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
import Swal from 'sweetalert2';
</script>

<script>
export default {
    props: {
        concessions: Array,
    },
    data() {
        return {
            selectedConcessions: [],
            concessionQuantities: {},
            sendToKitchenTime: '',
            errors: {},
        };
    },
    computed: {
        minDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        },
        totalAmount() {
            return this.selectedConcessions.reduce((total, concessionId) => {
                const concession = this.concessions.find(c => c.id === concessionId);
                const quantity = this.concessionQuantities[concessionId] || 1;
                return total + (concession.price * quantity);
            }, 0);
        }
    },
    methods: {
        async createOrder() {
            try {
                const concessionsData = this.selectedConcessions.map(id => ({
                    concession_id: id,
                    quantity: this.concessionQuantities[id] || 1,
                }));

                const response = await axios.post(route('orders.store'), {
                    concessions: concessionsData,
                    send_to_kitchen_time: this.sendToKitchenTime,
                });

                this.$toast.fire({
                    icon: 'success',
                    title: response.data.message,
                });

                this.$inertia.visit(route('orders.index'));
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    this.$toast.fire({
                        icon: 'error',
                        title: 'Validation error',
                    });
                } else if (error.response) {
                    this.$toast.fire({
                        icon: 'error',
                        title: error.response.data.message || 'An unknown error occurred.',
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
