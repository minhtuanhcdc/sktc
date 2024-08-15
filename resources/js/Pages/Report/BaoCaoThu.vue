<template>
    <AdminLayout :chuyenKhoan="chuyenKhoan" :tienMat="tienMat"  :total_pay="total_pay" :unpaid="unpaid">
      <Head title="Custommer"></Head>
        <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
            <span class="text-white">Thống kê - Tổng hợp </span>
           
        </div>
        <div class="flex border-2 border-blue-900 items-center space-x-2 py-2 px-4">
            <div class="flex space-x-3 w-3/4">
                <div class="flex w-32">
                    <label class="text-blue-900 w-10">Buổi:</label>
                    <select v-model="buoi" class="text-sm w-full h-7 p-0 px-2 border border-blue-900 rounded-lg">
                        <option value="">All</option>
                        <option value="am">Sáng</option>
                        <option value="pm">Chiều</option>
                    
                    </select>
                </div>
                <div class="flex">
                    <div class=" flex flex-row items-center" >
                        <label class="text-blue-900 w-16 leading-4 text-center">Từ ngày: </label>
                        <input
                            id="startDate"
                            type="date"
                            class="h-8 block w-full text-sm rounded-md px-1"
                            v-model="startDate"
                            autocomplete="startDate"/> 
                    </div>
                    <div class=" flex flex-row ml-2 items-center">
                        <span class="text-blue-900">đến: </span>
                        <input
                            id="endDate"
                            type="date"
                            class= "h-8 block text-sm rounded-md"
                            v-model="endDate"
                            autocomplete="endDate"/>
                    </div>
                </div> 
                <div class="flex">
                    <label class="text-blue-900 w-6 mr-1">DV:</label>
                    <select v-model="id_service" class="w-full h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                        <option value="">All</option>
                        <template v-for="(s,i) in services" :key="i">
                            <option :value="s.id" class="">- {{ s.name }}</option>
                        </template>  
                    </select>
                </div>
            </div>
            <div class="flex space-x-4">
                <div class="w-18 py-1">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2" @click="filterData">Filter</span>
            </div>
            <div class="w-32 py-1">
                <span class="text-white bg-yellow-500 rounded-sm cursor-pointer px-2 py-1" @click="Clear()">Clear Filter</span>
            </div>
            </div>
            <div class="flex justify-end">
                <div class="w-24 py-1">
                <a :href="route('BaoCaoThuExport',{'buoi':buoi,'startDate':startDate,'endDate':endDate,'id_service':id_service})" target="blank">
                    <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2 ">Xuất Excel</span>
                </a>
            </div> 
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
            <div>
                <span><PrinterIcon class="w-8 h-8 cursor-pointer text-blue-900" @click="printBaocaoThu(bills)"/></span>
            </div>
        </div>
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
            <Table :classTable="classTable" :classThead="classThead">
                <template #header>
                    <TableHeader :headers="headers"/>
                </template>    
                <template #tbody>
                <template v-for="(s,i) in bills.data" :key="i">
                    <TableRow :classRow="classRow">
                        <Tbody class="w-32">
                            {{ s.bills.seri_bill }}
                        </Tbody>
                        <Tbody class="w-24">
                            {{ formatDate(s.bills.created_at) }}
                        </Tbody>
                        <Tbody>
                          {{ s.bills.custommer.name }}
                        </Tbody>
                        <Tbody>
                            <span v-if="s.bills.custommer.address">
                                {{ s.bills.custommer.address }}, 
                            </span>
                            <span v-if="s.bills.custommer.ward">
                                {{ s.bills.custommer.ward.name }},  
                            </span>
                            <span v-if="s.bills.custommer.district">
                                {{ s.bills.custommer.district.name }}, 
                            </span>
                            <span v-if="s.bills.custommer.province">
                                {{ s.bills.custommer.province.name }}
                            </span>
                           
                        </Tbody>
                        <Tbody>{{ i+1 }}</Tbody>
                        <Tbody>
                            {{ s.catelogies.medicine_name }}
                        </Tbody>
                        <Tbody>
                          {{ s.catelogies.donvi_tinh }}
                        </Tbody>
                        <Tbody class="pr-1 w-32">
                          {{ formatPrice_1(s.don_gia) }}
                        </Tbody>
                        <Tbody class="w-10">
                          {{ s.sl }}
                        </Tbody>
                       
                        <Tbody class="w-32 text-right pr-2">
                          {{ formatPrice_1((s.don_gia)*(s.sl)) }}
                        </Tbody>
                        <Tbody>
                            <span v-if="s.bills.buoi=='am'">Sáng</span>
                            <span v-if="s.bills.buoi=='pm'">Chiều</span>
                        </Tbody>
                        <Tbody>
                            <div v-if="s.bills.pay_status == 1">
                                <span v-if="s.bills.pay_cash == 1">
                                    TM
                                </span>
                                <span v-else>
                                    CK
                                </span>
                            </div>
                         
                        </Tbody>
                        <Tbody>2K23THA{{ s.bills.seri_bill }}</Tbody>
                        <Tbody>
                            <span v-if=" s.bills.user">
                                {{ s.bills.user.name }}
                            </span>
                        </Tbody>
                    </TableRow>
                </template>
                    <TableRow class="bg-gray-200">
                        <Tbody colspan="13" class="font-bold text-gray-800 text-right text-sm pr-24">Tổng cộng: <span v-if="sum_price">{{ formatPrice_1(sum_price) }}</span> </Tbody>
                      
                    </TableRow> 
                    <TableRow class="bg-gray-200">
                        <Tbody colspan="13" class="font-bold text-gray-800 text-right text-sm pr-24">Bằng chữ: <span>{{ DocTienBangChu(sum_price)}}</span> </Tbody>
                      
                    </TableRow> 
                </template> 
            </Table>
            <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
                <Pagination :links="bills.links"/>
            </div> 
        </div>
    </AdminLayout>
</template>
<script src="./baocaothu.js"></script>
<style>
</style>