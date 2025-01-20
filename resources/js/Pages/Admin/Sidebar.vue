<template>
    <div class="flex flex-col  p-0 bg-hcdc1 z-40 mt-2" :class="isOpenMenu?'w-14':'w-56'">
        <div class="flex w-full justify-center mb-2 border-b-2 border-white py-2 cursor-pointer text-white bg-hcdc2">
           
            <span v-if="isOpenMenu" class="text-center flex justify-center"  @click="handleClose"><ChevronRightIcon class="w-6 h-6 text-white"/></span>
            <span v-else class="pl-4"><ChevronLeftIcon class="w-6 h-6 text-white"  @click="handleOpen"/></span> 
        </div>    
        <div class="flex w-full p-2 z-40">
            <div class="flex flex-col  w-8 space-y-4">
                <div class="text-right flex justify-end items-center h-10" v-for="(menu,i) in $page.props.menuPermission.menuAccess" :key="i">
                        <NavLink  :href="route(menu.Url)" class="text-white capitalize border-t w-full h-10" :class="menu.menu_group==2?'bg-green-600':''"  >
                            <span class="tooltip_menu11 z-50 text-xs" :data-tip1="menu.menuName">
                                <component v-bind:is="menu.icon"  class="w-6 h-6" :class="route().current(menu.Url)?'text-hcdc2':'text-white'"/> 
                            </span>
                        </NavLink>
                </div>
            </div>
            <div class="flex flex-col flex-1  space-y-4" v-if="!isOpenMenu" @click="handleOpen">
                 <span v-for="(menu,i) in $page.props.menuPermission.menuAccess" :key="i">
                    <NavLink :href="route(menu.Url)" class=" capitalize border-t w-full h-10 text-bottom hover:bg-yellow-300" :class="route().current(menu.Url)?'text-hcdc2':'text-white'" :active="route().current(menu.Url)"  >{{ menu.menuName }}</NavLink>
                </span>    
            </div>
        </div>
    </div>
</template>

<script>
import '../../../css/sidebar.css'
   import NavLink from '@/Components/NavLink.vue';
    import { BeakerIcon,ChevronRightIcon,ChevronLeftIcon,ListBulletIcon,ShieldCheckIcon,UsersIcon,UserGroupIcon,HomeIcon,
        IdentificationIcon,DocumentDuplicateIcon,ClipboardIcon,PencilIcon,BookOpenIcon,BookmarkIcon, CurrencyDollarIcon,DocumentTextIcon,ArchiveBoxIcon,CubeIcon,DeviceTabletIcon,CreditCardIcon } from '@heroicons/vue/24/solid'
    export default{
        props:{
            isOpenSidebar:""
        },
        components:{
            NavLink,
            BeakerIcon,ChevronRightIcon,ChevronLeftIcon,ListBulletIcon,
            ShieldCheckIcon,UsersIcon,UserGroupIcon,HomeIcon,IdentificationIcon,
            DocumentDuplicateIcon,ClipboardIcon,PencilIcon,BookOpenIcon,BookmarkIcon,CurrencyDollarIcon,DocumentTextIcon,ArchiveBoxIcon,CubeIcon,DeviceTabletIcon,CreditCardIcon,
        },
        data(){
            return{
               isOpenMenu:this.isOpenSidebar
            }
        },
        computed: {
         
        },
        methods:{
            handleOpen(){
              
                this.$emit('handleEventOpenSidebar',this.isOpenMenu=true)
            },
            handleClose(){
               
                this.$emit('handleEventCloseSidebar',this.isOpenMenu=false)
            }
        }
    }
</script>
