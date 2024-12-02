<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Kitchen Orders
            </h2>
        </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
              <div v-if="orders.length === 0" class="text-center text-gray-500">
                No orders available.
              </div>

              <table v-else class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">#</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Total Cost</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="(order, index) in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">{{ order.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">{{ order.status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">Rs.{{ order.total_cost }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <a @click="markAsCompleted(order.id)" class="text-green-600" style="cursor: pointer;">Complete</a>
                            |
                            <a :href="route('kitchen.view', order.id)" class="text-blue-500">View</a>
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
import axios from 'axios';
import Swal from 'sweetalert2';
</script>

<script>
export default {
    props: {
        orders: Array,
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

                    this.$inertia.visit(route('kitchen.index'));
                }
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
        },
    },
};
</script>
