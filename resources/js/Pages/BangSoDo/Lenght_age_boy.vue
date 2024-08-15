<template>
    <AdminLayout>
        <Head title="Nhóm DM"/>
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <div class="flex m-auto flex-col leading-4 justify-center bg-blue-600 py-1 mb-1">
                <span class="font-bold uppercase py-2 px-4 m-auto leading-3 text-white">Bảng đánh giá <span class="text-hcdc2 font-bold">chiều cao</span> theo <span class=" font-bold text-hcdc2">tuổi</span> bé trai</span>
            </div>
            <div class="flex items-center space-x-8">
                <span class="underline cursor-pointer hover:text-hcdc1">Import</span>
                <ButtonApp class=" bg-hcdc2 rounded-sm px-4 hover:text-white" @click="openForm" v-if="!openModal">Add+</ButtonApp>
            </div>    
          </div>
          <div class="border border-hcdc2" v-if="openModal">
                <div class="flex justify-between mb-2">
                    <span>Nhập thông số </span>
                    <span class="border border-hcdc2 px-2 cursor-pointer hover:text-hcdc2" @click="closeForm"> Đóng (x)</span>
                </div>
                <div class=" m-auto p-2 my-2 mx-4 border-2 border-hcdc1">
                    <form @submit.prevent="save">
                        <div class="flex">
                            <div class="w-[10%] flex px-1">
                                <label class="w-[70%] font-bold">Tháng tuổi</label>
                                <input type="text" class="w-[30%] h-7 rounded-sm p-0" v-model="form.month">
                            </div>
                            <div class="w-[60%] flex space-x-2 px-2 border border-hcdc1 py-0">
                                
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">-3SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.am_3SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">-2SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.am_2SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">-1SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.am_1SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">Median</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.median"/>
                                        </div>
                                    </div>
                                    <div class="w-12%">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">1SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.mot_SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">2SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.hai_SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex">
                                            <label class="w-[50%] text-right">3SD</label>
                                            <input class="w-[50%] h-7 rounded-sm p-0" v-model="form.ba_SD"/>
                                        </div>
                                    </div>
                            </div> 
                        </div>
                        <div class="flex justify-center items-center mt-4">
                            <button type="submit" class="bg-hcdc1 rounded-sm px-4 py-1 h-7 text-white cursor-pointer hover:bg-hcdc2 text-center flex items-center">Save</button>
                        </div>
                    </form>
                </div> 
          </div>
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg flex flex-col justify-center">
            <div  class="bg-blue-600 text-center text-white py-1" >WHO Child Growth Standards <span class="text-hcdc2">(Lenght-for-age BOYS)</span></div>
                <Table :classTable="classTable" :classThead="classThead" class="w-[70%]">
                    <template #header>
                        <TableHeader :headers="headers" class="bg-blue-500 text-center text-white"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" >
                            <Tbody class="text-center w-16">0:0</Tbody>
                            <Tbody class="text-center w-16">0</Tbody>
                            <Tbody class="text-center w-16">1</Tbody>
                            <Tbody class="text-center w-16">49.8842</Tbody>
                            <Tbody class="text-center w-16">0.03795</Tbody>
                            <Tbody class="text-center w-16">1.8931</Tbody>
                            <Tbody class="text-center w-16">44.2</Tbody>
                            <Tbody class="text-center w-16">46.1</Tbody>
                            <Tbody class="text-center w-16">48.0</Tbody>
                            <Tbody class="text-center w-16">49.9</Tbody>
                            <Tbody class="text-center w-16">51.8</Tbody>
                            <Tbody class="text-center w-16">53.7</Tbody>
                            <Tbody class="text-center w-16">55.6</Tbody>
                            <Tbody class="w-36"> 
                                <div class="flex justify-center space-x-3"> 
                                    <span class="tooltip_edit11 z-40 cursor-pointer" data-tip="Sửa" >
                                        <PencilIcon class="classPencil" />
                                    </span> 
                                    <span title="Xóa">
                                        <XCircleIcon class="classXIcon"  /> 
                                    </span>
                                </div>
                            </Tbody>
                        </TableRow> 
                    </template>
                </Table>
           </div>
           <div class="flex mt-2 bg-blue-500 items-center">
                <!-- <Pagination :links="weight_age_boys.links"/>  -->
            </div>
        </div>
        <!-- <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật nhóm danh mục</span>
                <span v-else>Thêm Nhóm</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveGroup">
              
                    <div class="">
                         <label for="name" class="classLabel">Nhóm</label>
                        <textarea id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name">
                        </textarea>
                        <InputErrorApp :message="form.errors.name" class="mt-2" /> 
                    </div> 
                    <div class="">
                         <label for="content" class="classLabel">Nội dung</label>
                      
                        <textarea id="content" type="text" class="inputText border border-blue-700" v-model="form.content" autocomplete="content">

                        </textarea>
                       
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
        </ModalApp> -->
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
        </ConfirmModalApp> -->
    </AdminLayout>
</template>
<script src="./lenght_age_boy"></script>
