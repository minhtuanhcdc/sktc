<template>
    <AdminLayout>
        <Head title="Nhóm DM" />
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span>Nhóm danh mục</span>
            <template v-if="$page.props.can.create">
                <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
            </template>
          </div>
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg flex justify-center">
                <Table :classTable="classTable" :classThead="classThead" class="w-[70%]">
                    <template #header>
                        <TableHeader :headers="headers"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(catelogy,i) in catelogies.data">
                            <Tbody class="text-center w-16">{{ i+1 }}</Tbody>
                            <Tbody class="w-24 text-center font-bold">{{ catelogy.name }}</Tbody>
                            <Tbody>{{ catelogy.content }}</Tbody>
                            <Tbody class="w-44 text-center">{{ formattedDate(catelogy.updated_at) }}</Tbody>
                            <Tbody class="w-36"> 
                                <div class="flex justify-center space-x-3"> 
                                    <span class="tooltip_edit11 z-40 cursor-pointer" data-tip="Sửa" v-if="$page.props.can.edit">
                                        <PencilIcon class="classPencil" @click="openEditMenu(catelogy)" />
                                    </span> 
                                    <span title="Xóa" v-if="$page.props.can.delete">
                                        <XCircleIcon class="classXIcon" @click="openConfirm(catelogy)" /> 
                                    </span>
                                </div>
                            </Tbody>
                        </TableRow> 
                      
                    </template>
                </Table>
                
           </div>
           <div class="flex mt-2 bg-blue-500 items-center">
                    <Pagination :links="catelogies.links"/> 
                </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật nhóm danh mục</span>
                <span v-else>Thêm Nhóm</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveGroup">
                <!--Name--->
                    <div class="">
                         <label for="name" class="classLabel">Nhóm</label>
                        <!-- <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" /> -->
                        <textarea id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name">

                        </textarea>
                        <!--<InputErrorApp :message="form.errors.name" class="mt-2" /> -->
                    </div> 
                    <div class="">
                         <label for="content" class="classLabel">Nội dung</label>
                        <!-- <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" /> -->
                        <textarea id="content" type="text" class="inputText border border-blue-700" v-model="form.content" autocomplete="content">

                        </textarea>
                        <!--<InputErrorApp :message="form.errors.name" class="mt-2" /> -->
                    </div> 
                  
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
<script src="./catelogyGroup"></script>
