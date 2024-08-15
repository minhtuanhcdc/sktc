<template>
    <AdminLayout>
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span>Tỉ giá cố định</span>
            <ButtonApp class="button_add bg-red-500" @click="openModalLock">Khóa tỉ giá cố định</ButtonApp>
            <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
          </div>
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
                <Table :classTable="classTable" :classThead="classThead">
                    <template #header>
                        <TableHeader :headers="headers"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(ex,i) in exchanges.data">
                            <Tbody class="text-center">{{ i+1 }}</Tbody>
                            <Tbody class="text-center">{{ fixNumber_us(ex.exchange_usd) }}</Tbody>
                            <Tbody class="text-center">{{ formattedDate(ex.updated_at) }}</Tbody>
                            <Tbody class="text-center">{{ ex.user.name }}</Tbody>
                            <Tbody class="text-center">
                                <span v-if="ex.status == 1" class="bg-blue-900 rounded-md px-2 py-1 text-white cursor-pointer" @click="handleClock(ex.id)">Đang sử dụng</span>
                                <span v-else class="bg-red-700 rounded-md px-2 text-white py-1 cursor-pointer">Clock</span>
                            </Tbody>
                            <!-- <Tbody class="flex justify-end pr-4 space-x-8 z-10"> 
                                <span class="tooltip_edit z-40" data-tip="Sửa">
                                    <PencilIcon class="classPencil" @click="openEditMenu(ex)" />
                                </span> 
                                <span title="Xóa">
                                    <XCircleIcon class="classXIcon" @click="openConfirm(ex)" /> 
                                </span>
                            </Tbody> -->
                        </TableRow> 
                      
                    </template>
                </Table>
                <div class="flex mt-2 bg-blue-500 items-center">
                    <Pagination :links="exchanges.links"/> 
                </div>
           </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật Tỉ giá</span>
                <span v-else>Thêm Tỉ giá</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveExchange">
                <!--Name--->
                    <div class="">
                         <label for="exchange" class="classLabel">Tỉ gía</label>
                        <TextInputApp id="exchange" type="text" class="inputText border border-blue-700" v-model="form.exchange_usd" autocomplete="name" />
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
                    <span class="font-bold pl-2 underline text-red-600 pr-1">{{Question}} ?</span> 
                </div>
            </template>
            <template #footer class="text-center">
                <button v-if="disable_Exchange" class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="clockExchange()">Xác nhận Clock</button>
                <button v-else class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="disableExchange(id_exchange)">Xác nhận</button>
            </template>
        </ConfirmModalApp>
    </AdminLayout>
</template>
<script src="./exchangeFix"></script>
