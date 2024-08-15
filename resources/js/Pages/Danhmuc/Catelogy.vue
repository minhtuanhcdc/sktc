<template>
    <AdminLayout>
        <Head title="Danh mục" />
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span class="text-blue-900 font-bold">Danh mục Kiểm dịch</span>
            <template v-if="$page.props.can.create">
                <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
            </template>
            </div>
            <div class="flex justify-between mt-2 mx-8">
                <!-- <div>
                    <label>Search: </label>
                    <input v-model="search" class="rounded-lg border border-blue-900"/>
                </div> -->
                <Search v-on:eventSearch="handleSearch" :classSearch="classSearch"/>
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" />
                <form @submit.prevent="uploadFile">
                    <div class="flex flex-row border border-md border-blue-900 p-2">
                        <div class=" p-0 w-56"> 
                            <input type="file"
                            class=" px-2 py-0 mt-0 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            @change="previewImage" ref="fileupload" />
                        </div>
                        <div class="flex items-center mt-0">
                            <button class="px-2 py-1 text-white bg-blue-900  rounded">Upload Danh mục</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="relative h-[95%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
                <div class="flex flex-col h-screen">
                    <div class="flex-grow overflow-auto">
                        <Table :classTable="classTable" :classThead="classThead">
                            <template #header>
                                <TableHeader :headers="headers"/>
                            </template>    
                            <template #tbody>
                                <TableRow :classRow="classRow" v-for="(catelogy,i) in catelogies.data">
                                    <Tbody>{{ i+1 }}</Tbody>
                                    <Tbody class="text-center">{{ catelogy.code }}</Tbody>
                                    <Tbody class="text-left">{{ catelogy.name }}</Tbody>
                                    <Tbody class="text-center">{{ catelogy.donvi_tinh }}</Tbody>
                                    <Tbody class="text-right">{{ catelogy.don_gia }}</Tbody>
                                    <Tbody class="text-center">(Nhóm {{ catelogy.group.id }}) - {{ catelogy.group.name }}</Tbody>
                                    <Tbody>{{ formattedDate(catelogy.updated_at) }}</Tbody>
                                    <Tbody class="border border-r">
                                        <span v-if="catelogy.status == 1" class="flex justify-center">
                                            <CheckIcon class="w-4 h-6 text-blue-700"/>
                                        </span>
                                        <span v-else  class="flex justify-center">
                                            <CheckIcon class="w-4 h-6 text-gray-200"/>
                                        </span>
                                    </Tbody>
                                    <Tbody class="">
                                        <div class="flex space-x-4 px-2 justify-center"> 
                                            <span class="tooltip_edit z-50 h-full cursor-pointer" data-tip="Sửa" v-if="$page.props.can.edit">
                                            <PencilIcon class="classPencil" @click="openEditCatelogy(catelogy)" />
                                            </span> 
                                            <span title="Xóa" v-if="$page.props.can.delete">
                                                <XCircleIcon class="classXIcon" @click="openConfirm(catelogy)" /> 
                                            </span>
                                        </div>
                                    </Tbody>
                                </TableRow>
                            
                            </template>
                        </Table>
                        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
                            <Pagination :links="catelogies.links"/>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật danh mục</span>
                <span v-else>Thêm danh mục</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveCatelogy">
                <!--Name--->
                    <div class="">
                         <label for="name" class="classLabel">Tên danh mục</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                        <InputErrorApp :message="form.errors.name" class="mt-2" />
                    </div> 
                    <div class="">
                         <label for="name" class="classLabel">Mã</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.code" autocomplete="name" />
                        <InputErrorApp :message="form.errors.name" class="mt-2" />
                    </div> 
                     <!--Slug--->
          
                <!--Id Parent--->
                     <div class="mt-4">
                        <label class="classLabel">Nhóm</label>
                        <select name="parent_id"
                                id="parent_id"
                                class="class_select border border-blue-600"
                                v-model="form.id_group">
                        <option value="">--Select--</option>
                        <option v-for="g in groups"
                                :key="g.id"
                                :value="g.id">(Nhóm {{g.id}} ){{ g.name }}</option>
                        </select>
                        <InputErrorApp :message="form.errors.id_group" class="mt-2" /> 
                    </div>
                   <!--Url--->
                   <div class="mt-4">
                        <label class="classLabel">Đơn vị tính</label>
                        <TextInputApp id="donvi_tinh" type="text" class="inputText border border-blue-600" v-model="form.donvi_tinh" autocomplete="donvi_tinh" />
                        <InputErrorApp :message="form.errors.donvi_tinh" class="mt-2" /> 
                    </div>   
                
                    <div class="mt-4">
                        <label class="classLabel">Đơn giá (USD)</label>
                        <TextInputApp id="icon" type="text" class="inputText border border-blue-600" v-model="form.don_gia" autocomplete="don_gia" />
                        <InputErrorApp :message="form.errors.don_gia" class="mt-2" />
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

<script src="./catelogy"></script>