<template>
    <AdminLayout :tienMat="tienMat" :chuyenKhoan="chuyenKhoan" :hcdcconfimred="hcdcconfimred" :total_pay="total_pay">
        <Head title="NhapThongTin"/>
     
        <div class="border-1 p-1 ">
            <div class="flex px-6 justify-between mt-0"> 
                <span class="text-hcdc1 font-bold" >Nhập thông tin</span>
                <span class="text-hcdc2 font-bold cursor-pointer underline" >Import file</span>
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
                    v-on:saveCustommerEmit="saveInfo"
                
                    />
                </div>   
            </div>
            <div class="mt-100"></div>
            <div class="flex mt-0 mx-8">
            <div class="w-1/2 flex space-x-2">
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
           
            </div>
            <div class="w-1/2 flex space-x-2">
                <!-- <Search v-on:eventSearch="handleSearch" :classSearch="classSearch"/>
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" /> -->
            </div>
            </div>
            <ViewInfo  :bills="bills"
                :provinces="provinces"
                :districts="districts"
                :wards="wards"
                :catelogies="catelogies" 
                v-on:confirmCashEvent='handleCash'
                v-on:confirmTransferEvent='handleTransfer'
                v-on:prinPdfEvent='prinPdf'
                v-on:deleteEvent='deletePay'
                v-on:districtEvent='districtHandle'
                v-on:updateCustommerEvent='updateCustommer'
             />
        </div>
    </AdminLayout>
</template>
<script src="./inputInfo.js"></script>

<style>
    .active{
        background-color: blue;
        color:white;
    }
</style>