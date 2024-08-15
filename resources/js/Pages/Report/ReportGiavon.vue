<template>
    <AdminLayout >
      <Head title="Custommer"></Head>
        <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
            <span class="text-white">Doanh thu - giá vốn</span>
        </div>
         <div class="flex border-2 border-blue-900 items-center  justify-between py-2 bg-gray-200">
            <div class="flex w-3/4 space-x-3"> 
                <div class="flex w-1/4">
                    <label class="text-blue-900 w-14 pr-1">VX(VT):</label>
                    <select v-model="id_service" class="w-full h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs bg-green-300">
                        <option value="">All</option>
                        <template v-for="(s,i) in services" :key="i">
                            <option :value="s.id" class="">- {{ s.medicine_name }}</option>
                        </template>  
                    </select>
                </div>
                <div class=" flex">
                    <div class="w-44 flex flex-row items-center" >
                        <label class="text-blue-900 w-16 leading-4 text-center">Từ ngày: </label>
                        <input
                            id="startDate"
                            type="date"
                            class="h-8 block w-full text-sm rounded-md px-1 bg-yellow-100"
                            v-model="startDate"
                            autocomplete="startDate"/> 
                    </div>
                    <div class="w-44 flex flex-row ml-2 items-center">
                        <span class="text-blue-900">đến: </span>
                        <input
                            id="endDate"
                            type="date"
                            class= "h-8 block text-sm rounded-md bg-yellow-100"
                            v-model="endDate"
                            autocomplete="endDate"
                           />
                    </div>
                </div> 
                <div class="flex w-36">
                    <label class="text-blue-900 w-14 pr-1">Buổi:</label>
                    <select v-model="buoi" class="w-full h-7 p-0 px-4 border border-blue-900 rounded-lg text-md font-bold bg-yellow-100">
                        <option value="">--</option>
                        <option value="am" class="pl-8"> Sáng </option>
                        <option value="pm" class="pl-4"> Chiều </option>
                       
                    </select>
                </div>
                <div class="flex w-28">
                    <label class="text-blue-900 w-14 pr-1">Quý:</label>
                    <select v-model="qui" class="w-full h-7 p-0 px-4 border border-blue-900 rounded-lg text-lg font-bold">
                        <option value="">--</option>
                        <option value="I" class=""> I </option>
                        <option value="II" class=""> II </option>
                        <option value="III" class=""> III </option>
                        <option value="IV" class=""> IV </option>
                    </select>
                </div>
            </div> 
            <div class=" py-1 space-x-2">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2" @click="filterData">Filter</span>
                <span class="text-white bg-yellow-500 rounded-sm cursor-pointer px-2 py-1" @click="Clear()">Clear Filter</span>
               
            </div>
            <div class="w-24 py-1">
                <a :href="route('vaccineReport',{'startDate':startDate,'endDate':endDate,'id_service':id_service})" target="blank">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2 ">Xuất Excel</span>
                </a>
            </div> 
        </div>
        <div class="flex items-center justify-between my-3 px-4">
            <div class="flex items-center px-2">
                <input v-model="termSearch" class="h-7 px-2 rounded-lg border border-blue-900 w-96" placeholder="... nhập số BN, tên KH">
                <span class="cursor-pointer">Search</span>
               
            </div>
            <div class="flex items-center space-x-2">
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" />
            </div>
            <span><PrinterIcon class="w-8 h-8 cursor-pointer text-blue-900" @click="printReport(bills)"/></span>
        </div>
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
            <div class="text-center font-bold">BẢNG TỔNG HỢP DOANH THU - VACCINE</div>
            <div class="text-center font-bold">Ngày {{ ngay }} Tháng {{ thang }} Năm {{ nam }}</div>
            <div class=" font-bold">Thời gian:
               
                <span v-if="qui">Quí {{ qui }}</span>
                <span v-else>
                    <span v-if="buoi && (!startDate)">
                        <span v-if="buoi =='am'" >Sáng</span>
                        <span v-else>Chiều</span>
                    </span>
                    <span v-else>
                        <span v-if ="(startDate && endDate) && (startDate != endDate)"> Từ {{formatDate(startDate)}} đến {{ formatDate(endDate) }}</span>
                       <span v-else> 
                         <span v-if="(startDate == endDate) && (startDate!=null && endDate !=null)">{{buoi=='am'?'Sáng':'Chiều'}} ( {{ formatDate(startDate) }} )</span>
                         <span v-else>Ngày {{ ngay }} Tháng {{ thang }} Năm {{ nam }}</span>
                        
                        </span>
                    </span>
                   
                </span>
            </div>
            <Table :classTable="classTable">
                <template #header>
                    <TableHeader :headers="headers" :classHeader="classHeader"/>
                </template>    
                <template #tbody>
                <!-- <template v-for="(b,i) in bills.data" :key="i">
                    <TableRow :classRow="classRow" :classTd="classTd">
                        <Tbody>{{ i+1 }}</Tbody>
                        <Tbody class="text-left">{{ b.catelogies.medicine_name}}</Tbody>
                        <Tbody class="text-center w-24">{{ b.catelogies.donvi_tinh}}</Tbody>
                        <Tbody class="text-right w-24">{{ formatPrice_1(b.don_gia) }}</Tbody>
                        <Tbody class="text-center w-14">{{ b.tongSL }}</Tbody>
                        <Tbody class="text-right pr-2">{{ formatPrice_1((b.don_gia)* b.tongSL) }}</Tbody> 
                       
                    </TableRow>
                </template> -->
                <!-- <TableRow>
                        <Tbody colspan="5" class="font-bold text-gray-800 text-right text-sm pr-2">Tổng cộng: </Tbody>
                        <Tbody class="font-bold text-black text-sm text-right pr-2 " > <span v-if="sum_price">{{ formatPrice_1(sum_price) }}</span></Tbody>
                        <Tbody class="font-bold text-gray-800 text-right text-sm pr-2"></Tbody>
                    </TableRow> 
                    <TableRow>
                        <Tbody colspan="6" class="text-black text-right font-bold">Thành tiền bằng chữ: {{ DocTienBangChu(sum_price) }}</Tbody>
                    </TableRow> -->
                </template> 
            </Table>
            <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
                <!-- <Pagination :links="bills.links"/> -->
            </div> 
        </div>
    </AdminLayout>
</template>
<script src="./giavon.js"></script>

<style>
</style>