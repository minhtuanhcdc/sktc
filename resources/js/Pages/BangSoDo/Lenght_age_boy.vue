<template>
    <AdminLayout>
        <Head title="Nhóm DM"/>
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <div class="flex m-auto flex-col leading-4 justify-center bg-blue-600 py-1 mb-1">
                <span class="font-bold uppercase py-2 px-4 m-auto leading-3 text-white">Bảng đánh giá <span class="text-hcdc2 font-bold">chiều cao</span> theo <span class=" font-bold text-hcdc2">tuổi</span> bé trai</span>
            </div>
            <div class="flex items-center space-x-8">
                <form @submit.prevent="uploadFile">
                    <div class="flex flex-row border border-md border-blue-900 p-2">
                        <div class=" p-0 w-56"> 
                            <input type="file"
                            class=" px-2 py-0 mt-0 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            @change="previewImage" ref="fileupload" />
                        </div>
                        <div class="flex items-center mt-0">
                            <button class="px-2 py-1 text-white bg-blue-900  rounded">Upload File</button>
                        </div>
                    </div>
                </form>
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
                            <div class="w-[10%] flex py-2">
                                <label class="w-[70%] font-bold text-right pr-2">Tháng tuổi</label>
                                <input type="text" class="w-[30%] h-7 rounded-sm p-1" v-model="form.month">
                            </div>
                            <div class="w-[90%] flex space-x-2 px-2 border border-hcdc1 py-2 mx-4">
                                <div class="w-[30%] flex bg-gray-300 justify-center px-4 items-center">
                                    <div class="w-25% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[50%] text-right">L</label>
                                            <input class="w-[50%] h-7 rounded-sm p-1" v-model="form.L"/>
                                        </div>
                                    </div>
                                    <div class="w-25% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">M</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.M"/>
                                        </div>
                                    </div>
                                    <div class="w-25% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">S</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.S"/>
                                        </div>
                                    </div>
                                    <div class="w-25% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.SD"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-[30%] flex bg-gray-400 justify-center px-4 items-center ">
                                    <div class="w-30% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">-3SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.neg3SD"/>
                                        </div>
                                    </div>
                                    <div class="w-30% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">-2SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.neg2SD"/>
                                        </div>
                                    </div>
                                    <div class="w-30% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">-1SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.neg1SD"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-10% p-0 flex justify-center bg-hcdc2 px-4 mx-2">
                                    <div class="flex justify-center bg-white m-auto">
                                        <label class="w-14 ">Median</label>
                                        <input class="w-16 h-7 rounded-sm p-1" v-model="form.median"/>
                                    </div>
                                </div>
                                <div class="w-[30%] flex flex bg-gray-300 justify-center px-4 items-center ">
                                    <div class="w-12%">
                                        <div class="flex space-x-1">
                                            <label class="w-[20%] text-right">1SD</label>
                                            <input class="w-[80%] h-7 rounded-sm p-1" v-model="form.mot_SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">2SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.hai_SD"/>
                                        </div>
                                    </div>
                                    <div class="w-10% p-0">
                                        <div class="flex space-x-1">
                                            <label class="w-[30%] text-right">3SD</label>
                                            <input class="w-[70%] h-7 rounded-sm p-1" v-model="form.ba_SD"/>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="flex">
                            <div class="w-[15%]">
                                <div v-if="form.errors.month" class="text-cente text-red-800" >-{{form.errors.month }}</div>
                            </div>
                            <div class="w-[25%]">
                                <div v-if="form.errors.L" class="text-cente text-red-800" >-{{form.errors.L }}</div>
                                <div v-if="form.errors.M" class="text-cente text-red-800" >-{{form.errors.M }}</div>
                                <div v-if="form.errors.S" class="text-cente text-red-800" >-{{form.errors.S }}</div>
                                <div v-if="form.errors.SD" class="text-cente text-red-800" >-{{form.errors.SD }}</div>
                            </div>
                            
                            <div class="w-[25%]">
                                <div v-if="form.errors.neg1SD" class="text-cente text-red-800" >-{{form.errors.neg1SD }}</div>
                                <div v-if="form.errors.neg2SD" class="text-cente text-red-800" >-{{form.errors.neg2SD}}</div>
                                <div v-if="form.errors.neg3SD" class="text-cente text-red-800" >-{{form.errors.neg3SD }}</div>
                            </div>
                            <div class="w-[10%]">
                                <span v-if="form.errors.median" class="text-cente text-red-800" >-{{form.errors.median }}</span>
                            </div>
                            <div class="w-[25%]">
                                <div v-if="form.errors.mot_SD" class="text-cente text-red-800" >-{{form.errors.mot_SD }}</div>
                                <div v-if="form.errors.hai_SD" class="text-cente text-red-800" >-{{form.errors.hai_SD}}</div>
                                <div v-if="form.errors.ba_SD" class="text-cente text-red-800" >-{{form.errors.ba_SD }}</div>
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
                        <TableRow :classRow="classRow" v-for="(lfa,i) in lengthforages.data" :key="i">
                            <Tbody class="text-center w-24">{{ changeYear(lfa.month)}} : {{getMonth(lfa.month)}} </Tbody>
                            <Tbody class="text-center w-16">{{ lfa.month}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.L}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.M}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.S}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.neg1SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.neg2SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.neg3SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.median}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.mot_SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.hai_SD}}</Tbody>
                            <Tbody class="text-center w-16">{{ lfa.ba_SD}}</Tbody>
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
                 <Pagination :links="lengthforages.links"/> 
            </div>
        </div>
       
    </AdminLayout>
</template>
<script src="./lenght_age_boy"></script>
