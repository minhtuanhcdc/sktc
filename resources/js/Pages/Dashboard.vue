<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminLayout from '../Layouts/AdminLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowRightIcon,ArrowDownIcon} from '@heroicons/vue/24/solid'
</script>

<template>
    <AdminLayout title="Dashboard">
        <Head title="Home" />
      <!-- <iframe src="https://hcdc.vn/" width="100%" height="100%" name=”iframe”></iframe> -->
      <div class="flex flex-col justify-center items-center pt-24">
        <div class="flex space-x-8 items-center">
          <div class="center relative inline-block select-none whitespace-nowrap rounded-lg bg-blue-700 px-4 py-8 align-baseline font-sans text-xs font-bold uppercase leading-none text-white">
            <div class="mt-px px-4">Import, Nhập thông tin </div>
          </div>
          <!-- <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
          </svg> -->
          
        </div>
        <!-- <div class="border-l-2 border-r-2 border-b-2 h-8 border-blue-900 w-96"></div> -->
       
        <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v12m0 0 4-4m-4 4L1 9"/>
        </svg>
        <div class="flex justify-center items-center">
          <button class="bg-cyan-500 shadow-xl shadow-cyan-500/50 md:bg-blend-darken ml-60 my-8 center relative inline-block select-none whitespace-nowrap rounded-lg py-14 px-4 align-baseline font-sans text-xs font-bold uppercase leading-none text-white">
            Database
          </button>
          <svg class="mx-12 w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
          </svg>
          <div class="h-16 center relative inline-block select-none whitespace-nowrap rounded-lg bg-blue-700 py-4 px-4 align-baseline font-sans text-xs font-bold uppercase leading-none text-white">
            <div class="mt-px h-full flex justify-center items-center">Export Excel</div>
          </div>
          </div>
          <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v12m0 0 4-4m-4 4L1 9"/>
          </svg>
        <div class="mt-6 center relative inline-block select-none whitespace-nowrap rounded-lg bg-blue-700 py-8 px-4 align-baseline font-sans text-xs font-bold uppercase leading-none text-white">
            <div class="mt-px">Thống kê - Báo cáo</div>
          </div>
      </div>
         
    </AdminLayout>
</template>
