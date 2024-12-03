<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Kitchen Order Details - Order #{{ order.id }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <a v-if="order.status == 'In Progress'" @click="markAsCompleted(order.id)" style="cursor: pointer;" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Mark As Completed
              </a>

              <a :href="route('kitchen.index')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 ml-auto">
                Back to Orders
              </a>
            </div>

            <div class="space-y-4">
              <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-48">Order ID:</span>
                <span class="text-gray-500">{{ order.id }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-48">Status:</span>
                <span class="text-sm font-medium">{{ order.status }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-48">Total Cost:</span>
                <span class="text-gray-500">Rs.{{ order.total_cost }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-48">Send to Kitchen:</span>
                <span class="text-gray-500">{{ order.send_to_kitchen_time }}</span>
              </div>

              <div v-if="order.concessions && order.concessions.length > 0" class="mt-6">
                <h3 class="font-semibold text-lg">Concessions:</h3>
                <table class="min-w-full divide-y divide-gray-200 mt-4">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concession ID</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concession</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-for="concession in order.concessions" :key="concession.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ concession.pivot.concession_id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ concession.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ concession.pivot.quantity }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.{{ concession.pivot.price }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.{{ concession.pivot.quantity * concession.pivot.price }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
import axios from 'axios';
import Swal from 'sweetalert2';
</script>

<script>
export default {
    props: {
        order: Object,
    },
    data() {
        return {
        };
    },
    methods: {
      async markAsCompleted(orderId) {
            try {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to mark this order as completed?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, mark as completed!',
                });

                if (result.isConfirmed) {
                  const response = await axios.post(`/orders/${orderId}`, {
                        status: 'Completed',
                    });

                    this.$toast.fire({
                        icon: 'success',
                        title: response.data.message,
                    });

                    this.order.status = 'Completed';
                }
            } catch (error) {
              if (error.response) {
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
  