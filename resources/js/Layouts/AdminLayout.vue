<template>
    <AppLayout>
        <div class="flex flex-wrap mt-0 px-2">
            <Sidebar></Sidebar>
            <div class="flex-1 overflow-hidden shadow-xl w-full sm:w-2/3 h-screen py-0 px-8">
                <slot>
                </slot>
            </div>
        </div>
    </AppLayout>
</template>
<script>
    import AppLayout from './AppLayout.vue'
    import Sidebar from '../Pages/Admin/Sidebar.vue'
    export default{
        components:{
            AppLayout,
            Sidebar
        }
    }
</script>

