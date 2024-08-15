<template>
    <div v-if="show"
     class="fixed top-10 right-32 flex items-center text-white px-8 py-4 bg-opacity-80"
     :class="{
         'bg-blue-500':$page.props.flash.success,
         'bg-red-500':$page.props.flash.failure, }">
        <div>
            {{$page.props.flash.success}}
        </div>
        <div>
            {{$page.props.flash.failure}}
        </div>
        <button class="ml-4" @click="hideMessage">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>
    </template>
    
    <script>
    export default {
        data(){
            return{
                show:false
            }
        },
        watch:{
            //$page.props.flash.success || $page.props.flash.failure
            '$page.props.flash':function(value){
                if(value.success || value.failure){
                    this.show=true;
                    setInterval(() => {
                      this.show=false;
                       this.$page.props.flash.success=""
                        this.$page.props.flash.failure=""
                    }, 5000);
                }
               
            }
        },
        methods:{
            hideMessage(){
                this.show=false;
            }
           
        }
    
    }
    </script>