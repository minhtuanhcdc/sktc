<template>
    <AdminLayout>
        <Head title="NhapThongTin"/>
        <div class="border-1 p-1 ">
            <div class="flex px-6 justify-between mt-0"> 
                <span class="text-hcdc1 font-bold" >Nhập thông tin</span>
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
                <button v-if="!showAdd" class=" bg-hcdc1 text-white rounded-lg px-6 py-4 h-6 flex items-center cursor-pointer hover:bg-blue-600" @click="showAdd = !showAdd">+ Nhập</button>
            </div>
            <div v-if="showAdd" class="px-2">
                <div>
                   <InputInfo
                    :showAdd="showAdd"       
                    :provinces="provinces" 
                    :districts="districts" 
                    :wards="wards" 
                    v-on:districtEvent="districtHandle"
                    v-on:closeFormEvent="closeFormHandle"
                    v-on:saveParamEmit="saveInfo"
                    />
                </div>   
            </div>
            <div class="mt-100"></div>
            <div class="flex mt-0 mx-8">
            <!-- <div class="w-1/2 flex space-x-2">
                    <div class="w-56 flex flex-row items-center" >
                        <label class="text-blue-900 w-24">Từ ngày: </label>
                        <input
                            id="startDate"
                            type="date"
                            class="h-8 block w-full text-sm rounded-md"
                            v-model="startDate"
                            autocomplete="startDate"/> 
                    </div>
                    <div class="w-44 flex flex-row ml-2 items-center">
                        <span class="text-blue-900">đến: </span>
                        <input
                            id="endDate"
                            type="date"
                            class= "h-8 block text-sm rounded-md"
                            v-model="endDate"
                            autocomplete="endDate"/>
                    </div>
                
                    <div class="w-24 py-1">
                        <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2" @click="filterData">Filter</span>
                    </div>
                    <div class="w-24 py-1">
                        <span class="text-white bg-yellow-500 rounded-sm cursor-pointer px-2 py-2" @click="Clear()">Clear</span>
                    </div>
           
            </div> -->
            
            </div>
            <div class="flex justify-between">
                <div class="flex items-center">
                    <form >
                        <input v-model="termSearch" class="h-7 rounded rounded-md" placeholder="...Nhập tên, mã định danh"> <span @click="handleSearch" class="cursor-pointer">Search</span>
                    </form>
                  
                </div>
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" /> 
            </div>
            <ViewInfo  
                :info_childs="info_childs"
                :duplicates="duplicates"
                :provinces="provinces"
                :districts="districts"
                :wards="wards"
                v-on:prinPdfEvent='prinPdf'
                v-on:districtEvent='districtHandle'
                v-on:updateInfoEmitEvent='updateInfo'
             />
        </div>
        <AlertModal :show ="showModal">
            <div>
                <div class="flex justify-between py-2 px-4 border-b border:white">
                    <span class="font-bold text-hcdc1 w-[80%] text-center">Dữ liệu trùng</span>
                    <span @click="handleCloseModal()" class="cursor-pointer bg-blue-600 px-3 py-1 text-white hover:bg-yellow-800">close (x)</span>
                </div>
                <div >
                    <Table class="w-[100%]">
                        <template #header>
                            <TableHeader :headers="headers" class=""/>
                        </template>    
                        <template #tbody >
                            <TableRow class="border" v-for="(d,i) in duplicates" :key="i">
                                <Tbody class="text-center w-6 ">{{ i +1 }}</Tbody>
                                <Tbody class="text-center w-6 w-[25%]">{{ d.ten }}</Tbody>
                                <Tbody class="text-center w-6 w-[20%]">
                                    <span v-if="d.sex = 1">Nam</span>
                                    <span v-else>Nữ</span>
                                </Tbody>
                                <Tbody class="text-center w-6 w-[20%]">{{ d.ngaySinh }}</Tbody>
                                <Tbody class="text-center w-6 w-[20%]">{{ d.address }}</Tbody>
                                <Tbody>{{ d.parent }}</Tbody>
                            
                            </TableRow> 
                        </template>
                    </Table>
                </div>
            </div>
        </AlertModal>
       
    </AdminLayout>
</template>
<script src="./inputInfo.js"></script>
<style>
    .active{
        background-color: blue;
        color:white;
    }
</style>