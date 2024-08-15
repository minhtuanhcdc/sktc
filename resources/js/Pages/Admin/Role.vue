<template>
    <AdminLayout>
         <Head title="Role" />
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span>Roles</span>
            <ButtonApp class="button_add bg-blue-500" v-if="!showAdd" @click="showAdd = !showAdd">Add+</ButtonApp>
            <ButtonApp class="button_add bg-yellow-500" v-else  @click="showAdd = !showAdd">Close</ButtonApp>
          </div>
            <form @submit.prevent="saveRole" v-if="showAdd">
                <fieldset class="border border-solid border-blue-900 p-3 bg-green-200">
                    <legend class="text-sm">Thêm Role</legend>
                    <div class="flex justify-between w-full items-center"> 
                        <div class="flex w-1/2 items-center">
                                <label for="name" class="classLabel w-32">Tên Role</label>
                                <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                            <!--<InputErrorApp :message="form.errors.name" class="mt-2" /> -->
                        </div> 
                        <div class="w-1/4 flex items-center pl-4">
                            <Checkbox :checked="checkededit" v-model="form.status" class="border-2 border-blue-600"/><span class="ml-2">Hiển thị</span> 
                        </div>   
                        <!--Action--->
                        <div class="w-1/4">
                            <ActionMessageApp :on="form.recentlySuccessful" class="mr-3">
                                <span>Saved.</span>                    
                            </ActionMessageApp>
                            <ButtonApp type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="button_save bg-blue-700">
                                <span >Save</span>
                            </ButtonApp>
                        </div>
                    </div>
                </fieldset>
            </form>   
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-2">
                <Table :classTable="classTable" :classThead="classThead">
                    <template #header>
                        <TableHeader :headers="headers"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(role,i) in roles">
                            <Tbody>{{ i+1 }}</Tbody>
                            <Tbody>{{ role.name }}</Tbody>
                            <Tbody>
                                <!-- <span v-for="(r,i) in role.permissions" :key="i">
                                    {{ r }}
                                </span> -->
                            </Tbody>
                            <Tbody>{{ formattedDate(role.updated_at) }}</Tbody>
                            <Tbody>
                                <span v-if="role.status == 1" class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-blue-700"/>
                                </span>
                                <span v-else  class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-gray-200"/>
                                </span>
                            </Tbody>
                            <Tbody class=""> 
                                <div class="flex space-x-3">
                                        <span class="tooltip_edit z-30 h-full" data-tip="Cấp quyền"  v-if="$page.props.can.edit">
                                            <ShieldCheckIcon class="classPencil" @click="openEditRole(role)" />
                                        </span> 
                                        <span title="Xóa" v-if=$page.props.can.delete>
                                            <XCircleIcon class="classXIcon" @click="openConfirm(role)" /> 
                                        </span>
                                </div>
                            </Tbody>
                        </TableRow>
                    </template>
                </Table>
           </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span >Cập nhật Role  - Permission {{ id_role }}</span>
             <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="savePermission(form)">
                <!--Name--->
                    <div class="">
                        <TextInputApp id="id" type="text" class="inputText border border-blue-700 hidden" v-model="form.id" autocomplete="id" />
                        <!--<InputErrorApp :message="form.errors.name" class="mt-2" /> -->
                         <label for="name" class="classLabel">Tên Role</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                        <InputErrorApp :message="form.errors.name" class="mt-2" />
                    </div> 
                    <div class="grid grid-cols-2 mt-3">
                        <span v-for="(menu,i) in menus" :key="i" class="w-full flex items-center">
                            <input type="checkbox" v-model="form.id_menu" class="border-2 border-blue-600 h-4 w-4" :value="menu.id"/><span class="ml-2"><span class="font-bold text-md">{{ menu.name }}</span> </span>
                            (
                                <span ><input class="bg-blue-400 w-3 h-3 ml-2" type="checkbox" v-model="form.show_" :value="menu.id">Xem</span>      
                                <span ><input class="bg-blue-400 w-3 h-3 ml-2" type="checkbox" v-model="form.add_" :value="menu.id">Thêm</span>      
                                <span ><input class="bg-blue-400 w-3 h-3 ml-2" type="checkbox" v-model="form.edit_" :value="menu.id">Sửa</span>      
                                <span ><input class="bg-blue-400 w-3 h-3 ml-2" type="checkbox" v-model="form.delete_" :value="menu.id">Xóa</span>      
                            )
                        </span>
                        
                    </div>   
                   <!--Action--->
                    <div class="text-center mt-2">
                    <ActionMessageApp :on="form.recentlySuccessful" class="mr-3">
                        <span v-if="edit">Updated.</span>
                        <span v-else >Saved.</span>                    
                    </ActionMessageApp>
                    <ButtonApp type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="button_save bg-blue-700">
                        <span v-if="edit">Update</span>
                        <span v-else >Save</span>
                    </ButtonApp>
                    </div>
                </form>   
            </div>  
        </ModalApp>

        <ConfirmModalApp :show="confirmModel">
            <template #title class="w-full flex justify-end">
                <span @click="closeConfirmModal" class="px-4 py-1 cursor-pointer bg-blue-600 text-white rounded-sm">Close</span>
            </template>
            <template #content>
                <div class="flex justify-between w-full">
                    <span>Bạn chắc xóa:
                    <span class="font-bold pl-2 underline text-red-600 pr-1">{{viewMenu.name}}</span> ? </span>
                </div>
            </template>
            <template #footer class="text-center">
                <button class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="deleteRole(viewMenu.id)">Delete</button>
            </template>
        </ConfirmModalApp>
    </AdminLayout>
</template>

<script src="./role"></script>