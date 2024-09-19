<template>
    <AdminLayout>
        <Head title="Nhóm DM"/>
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <div class="flex m-auto flex-col leading-4 justify-center py-1 mb-1">
                <span class="font-bold uppercase py-2 px-4 m-auto leading-3 ">Bảng đánh giá <span class="text-red-700 font-bold">cân nặng</span> theo <span class=" font-bold text-hcdc2">chiều cao</span> bé gái</span>
                <span class="flex justify-center">(Weight for height GIRL)</span>
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
            
            <div class="bg-blue-200 flex" >
                <span class="w-[27%]"></span>
                <span class="w-[73%] flex justify-center border border-r-4 font-bold">Z-scores(Weight in kg)</span>
            </div>
                <Table :classTable="classTable" :classThead="classThead" class="w-[70%]">
                    <template #header>
                        <TableHeader :headers="headers" class="bg-blue-500 text-center text-white"/>                                                                                                                                                                 
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(wfa,i) in weight_height_girls.data" :key="i">
                           
                            <Tbody class="text-center ">{{ wfa.length }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.L }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.M }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.S }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.neg3SD }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.neg2SD }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.neg1SD }}</Tbody>
                            <Tbody class="text-center ">{{wfa.sd0  }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.mot_SD }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.hai_SD }}</Tbody>
                            <Tbody class="text-center ">{{ wfa.ba_SD }}</Tbody>
                            
                        </TableRow> 
                    </template>
                </Table>
           </div>
           <div class="flex mt-2 bg-blue-500 items-center">
                <Pagination :links="weight_height_girls.links"/> 
            </div>
        </div>
       
    </AdminLayout>
</template>
<script src="./weight_height_girl"></script>
                                       