<template>
    <div class="flex justify-center">
                <template v-for="(link, index) in links" :key="index" class="space-x-1">
                  <div v-if="link.url === null" v-html="link.label" class="mb-1 px-4 py-2 text-gray-900 text-sm leading-4 border"></div>
                   <Link v-else  :href="link.url" v-html="link.label" 
                   class="mb-1 px-4 py-2 text-sm leading-4 border bordered focus:text-indigo-500  focus:border-indigo-500 hover:bg-white"
                   :class="{'bg-indigo-400 font-bold':link.active}"
                   />
               </template>
    </div>
 </template>
 
 <script>
 //import {Link} from "@inertiajs/inertia-vue3";
 import { Link } from '@inertiajs/vue3';
 export default {
     props:{
         links:'',
     },
     components: {
         Link
     }
 
 }
 </script>
 
 <style>
 
 </style>