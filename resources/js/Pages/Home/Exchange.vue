<template>
    <AdminLayout>
      <Head title="Home" />
      <iframe src="https://hcdc.vn/" width="100%" height="80%" name=”iframe”></iframe>
    </AdminLayout>
</template>

<script>

   import AdminLayout from '../../Layouts/AdminLayout.vue';
   import { Head, Link, useForm } from '@inertiajs/vue3';
    import { BeakerIcon,ChevronRightIcon,ChevronLeftIcon,ListBulletIcon,ShieldCheckIcon,UsersIcon,UserGroupIcon } from '@heroicons/vue/24/solid'
    export default{
      name:'Home',
      props:{
            currencyWolrd:'',
            currencyVietcomBank:''
        },
        components:{
            AdminLayout,
            Head  
        },
        data(){
            return{
                
            }
        }
    }
</script>