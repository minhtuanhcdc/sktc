<template>
    <AdminLayout>
        <Head title="Menu" />
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span>Menu</span>
            <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
          </div>
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
                <Table :classTable="classTable" :classThead="classThead">
                    <template #header>
                        <TableHeader :headers="headers"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(menu,i) in menus">
                            <Tbody>{{ i+1 }}</Tbody>
                            <Tbody>{{ menu.name }}</Tbody>
                            <Tbody>{{ menu.id_parent }}</Tbody>
                            <Tbody>{{ menu.url }}</Tbody>
                            <Tbody>{{ menu.icon }}</Tbody>
                            <Tbody>{{ menu.menu_group }}</Tbody>
                            <Tbody>{{ formattedDate(menu.updated_at) }}</Tbody>
                            <Tbody>
                                <span v-if="menu.status == 1" class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-blue-700"/>
                                </span>
                                <span v-else  class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-gray-200"/>
                                </span>
                            </Tbody>
                            <Tbody class="flex justify-end pr-4 space-x-8 z-10"> 
                                <span class="tooltip_edit z-50" data-tip="Sửa">
                                    <PencilIcon class="classPencil" @click="openEditMenu(menu)" />
                                </span> 
                                <span title="Xóa">
                                    <XCircleIcon class="classXIcon" @click="openConfirm(menu)" /> 
                                </span>
                            </Tbody>
                        </TableRow>
                      
                    </template>
                </Table>
           </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật user</span>
                <span v-else>Thêm user</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveMenu">
                <!--Name--->
                    <div class="">
                         <label for="name" class="classLabel">Tên menu</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                        <!--<InputErrorApp :message="form.errors.name" class="mt-2" /> -->
                    </div> 
                     <!--Slug--->
          
                <!--Id Parent--->
                     <div class="mt-4">
                        <label class="classLabel">Menu cha</label>
                        <select name="parent_id"
                                id="parent_id"
                                class="class_select border border-blue-600"
                                v-model="form.id_parent">
                        <option value="">--Select--</option>
                        <option v-for="menu in menus"
                                :key="menu.id"
                                :value="menu.id">{{ menu.name }}</option>
                        </select>
                        <!-- <InputErrorApp :message="form.errors.parent_id"
                            class="mt-2" /> -->
                    </div>
                   <!--Url--->
                   <div class="mt-4">
                        <label class="classLabel">Route(Url)</label>
                        <TextInputApp id="url" type="text" class="inputText border border-blue-600" v-model="form.url" autocomplete="url" />
                        <!-- <InputErrorApp :message="form.errors.url" class="mt-2" /> -->
                    </div>   
                   <!--Icon--->
                    <div class="mt-4">
                        <label class="classLabel">Icon HeroIcon</label>
                        <TextInputApp id="icon" type="text" class="inputText border border-blue-600" v-model="form.icon" autocomplete="icon" />
                        <InputErrorApp :message="form.errors.icon" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <label class="classLabel">Menu group</label>
                        <select name="parent_id"
                                id="parent_id"
                                class="class_select border border-blue-600"
                                v-model="form.menu_group">
                        <option value="">--Chọn group--</option>
                        <option v-for="(g,i) in groups"
                                :key="i"
                                :value="g">{{ g }}</option>
                        </select>
                        <!-- <InputErrorApp :message="form.errors.parent_id"
                            class="mt-2" /> -->
                    </div>
                   <!--Status--->
                   <div class="mt-4">
                        <Checkbox :checked="checkededit" v-model="form.status" class="border-2 border-blue-600"/><span class="ml-2">Hiển thị</span> 
                    </div>   
                   <!--Action--->
                    <div class="text-center">
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
                <button class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="deleteMenu(viewMenu.id)">Delete</button>
            </template>
        </ConfirmModalApp>
    </AdminLayout>
</template>

<script src="./menu"></script>