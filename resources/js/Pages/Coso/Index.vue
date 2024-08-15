<template>
    <AdminLayout>
        <div class="h-[75%]">
          <div class="flex justify-between py-0 px-4">
            <span>Danh sách Cơ sở thu phí</span>
           
            <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
            </div>
            <div class="flex justify-between mt-2 mx-8">
                <!-- <Search v-on:eventSearch="handleSearch" :classSearch="classSearch"/>
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" /> -->
            </div>
            <div class="relative h-[95%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
                <div class="flex flex-col h-screen">
                <div class="flex-grow overflow-auto">
                <Table :classTable="classTable" :classThead="classThead">
                    <template #header>
                        <TableHeader :headers="headers"/>
                    </template>    
                    <template #tbody>
                        <template v-for="(c,i) in cosos.data" :key="i">
                        <TableRow :classRow="classRow" >
                            <Tbody>{{ i+1 }}</Tbody>
                            <Tbody class="text-center">{{ c.code }}</Tbody>
                            <Tbody class="text-left">{{ c.name }}</Tbody>
                            <Tbody class="text-center">{{ c.address }}</Tbody>
                            <Tbody class="text-center">{{ c.phone }}</Tbody>
                            <Tbody>{{ formattedDate(c.updated_at) }}</Tbody>
                            <Tbody>{{ c.province_code }}</Tbody>
                            <Tbody class="flex justify-end pr-4 space-x-8 z-10"> 
                                <span class="tooltip_edit z-50 cursor-pointer" data-tip="Sửa" v-if="$page.props.can?$page.props.can.edit:''">
                                    <PencilIcon class="classPencil" @click="openEditPost(c)" />
                                </span> 
                                <span title="Xóa">
                                    <XCircleIcon class="classXIcon" @click="openConfirm(c)" /> 
                                </span>
                            </Tbody>
                        </TableRow>
                        </template>

                    </template>
                </Table>  
                </div>     
                </div>     
            </div>
            <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
                    <!-- <Pagination :links="posts.links"/> -->
                </div>
        </div>
         <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật</span>
                <span v-else>Thêm Cơ sở thu phí</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="savePost">
                    <div class="">
                         <label for="code" class="classLabel">Mã Cơ sở </label>
                        <TextInputApp id="code" type="text" class="inputText border border-blue-700" v-model="form.code" autocomplete="code" />
                        <InputErrorApp :message="form.errors.code" class="mt-2" />
                    </div> 
              
                    <div class="">
                         <label for="name" class="classLabel">Tên cơ sở</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                        <InputErrorApp :message="form.errors.name" class="mt-2" />
                    </div> 
                   
              
                   <div class="mt-4">
                        <label class="classLabel">Address</label>
                        <TextInputApp id="address" type="text" class="inputText border border-blue-600" v-model="form.address" autocomplete="address" />
                        <InputErrorApp :message="form.errors.address" class="mt-2" /> 
                    </div>   
                  
                    <div class="mt-4">
                        <label class="classLabel">Phone</label>
                        <TextInputApp id="phone" type="text" class="inputText border border-blue-600" v-model="form.phone" autocomplete="phone" />
                        <InputErrorApp :message="form.errors.phone" class="mt-2" />
                    </div>
                 
                   <div class="mt-4">
                        <Checkbox :checked="checkededit" v-model="form.status" class="border-2 border-blue-600"/><span class="ml-2">Hiển thị</span> 
                    </div>   
                 
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
        <!-- <ConfirmModalApp :show="confirmModel">
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
        </ConfirmModalApp>-->
    </AdminLayout>
</template>
<script src="./coso"></script>