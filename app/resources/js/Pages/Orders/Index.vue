<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Orders
            </h2>
        </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
              <div class="flex justify-end mb-4">
                <a
                    :href="route('orders.create')" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Create New Order
                </a>

              </div>
  
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Total Cost</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Send to Kitchen</th>
                        <th style="text-align: left;" class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-gray-900">{{ order.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">{{ order.status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">Rs.{{ order.total_cost }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500">{{ order.send_to_kitchen_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <a :href="route('orders.view', order.id)" class="text-blue-500">View</a>
                            <button @click="deleteOrder(order.id)" class="text-red-500 ml-2">
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
      async deleteOrder(id) {
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
                    const response = await axios.delete(route('orders.destroy', id));
                    
                    this.$toast.fire({
                        icon: 'success',
                        title: response.data.message,
                    });

                    this.$inertia.visit(route('orders.index'));
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
    },
};
</script>
