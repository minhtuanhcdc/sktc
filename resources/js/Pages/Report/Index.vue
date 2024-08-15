<template>
    <AdminLayout :chuyenKhoan="chuyenKhoan" :tienMat="tienMat"  :total_pay="total_pay" :unpaid="unpaid">
      <Head title="Custommer"/>
     <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
        <span class="text-white">Thống kê - Báo cáo theo Biên lai</span>
      </div>
      <div class="flex border-2 border-blue-900 items-center space-x-2 py-2 px-3">
        <div class="flex w-3/4 space-x-4">
            <div class="flex w-32 items-center">
                <label class="text-blue-900 w-10 pr-1">Buổi:</label>
                <select v-model="buoi" class="text-sm w-full h-7 p-0 px-2 border border-blue-900 rounded-lg">
                    <option value="">All</option>
                    <option value="am">Sáng</option>
                    <option value="pm">Chiều</option>
                    
                </select>
            </div> 
           
            <div class="flex w-96">
                <div class="w-44 flex flex-row items-center" >
                    <label class="text-blue-900 w-16 leading-4 text-center">Từ ngày: </label>
                    <input
                        id="startDate"
                        type="date"
                        class="h-8 block w-full text-sm rounded-md px-1"
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
            </div>
            <div class="flex-1 flex items-center">
                <label class="text-blue-900 w-16 text-left">Vắc xin:</label>
                <select v-model="id_service" class="w-full h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                    <option value="">All</option>
                    <template v-for="(s,i) in services" :key="i">
                        <option :value="s.id" class="">- {{ s.name }}</option>
                    </template>
                    
                </select>
            </div>
        </div>
        <div class="flex space-x-8">
            <div class="flex space-x-3">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2" @click="filterData">Filter</span>
                <span class="text-white bg-green-500 rounded-sm cursor-pointer px-2 py-1 items-center flex" @click="Clear()">Clear Filter</span>
            </div>
            <div class="flex items-center flex-1 justify-end">
                <a :href="route('exportReport',{'buoi':buoi,'startDate':startDate,'endDate':endDate,'id_service':id_service,'id_post':id_post,'pay':pay})" target="blank">
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
    </div>
    <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
        <Table :classTable="classTable" :classThead="classThead">
              <template #header>
                  <TableHeader :headers="headers"/>
              </template>    
              <template #tbody>
               <template v-for="(b,i) in bills.data" :key="i">
                  <TableRow :classRow="classRow">
                    <Tbody>{{ i+1 }}</Tbody>
                    <Tbody>{{ b.seri_bill }}</Tbody>
                    <Tbody>{{ formatDate(b.created_at)}}</Tbody>
                    <Tbody class="text-left">{{ b.custommer.name }}</Tbody>
                    <Tbody class="text-left text-xs w-44">
                        <span>{{ b.custommer.address }},</span>
                         <span v-if="b.custommer.ward">{{ b.custommer.ward.name }}, </span>
                         <span v-if="b.custommer.district">{{ b.custommer.district.name }}, </span>
                         <span v-if="b.custommer.province">{{ b.custommer.province.name }}, </span>
                    </Tbody>
                    <Tbody class="w-56">
                        <template v-for="(catelogy, i) in b.catelogies" : key="i">
                           <p class="line-clamp-1 hover:line-clamp-2 px-1 text-left">-{{catelogy.name }}</p>
                        </template>   
                    </Tbody>
                    <Tbody class="text-center w-10">
                            <template v-for="(service, i) in b.services" : key="i">
                                <p class="line-clamp-1 hover:line-clamp-2"> {{ service.sl }}</p>
                            </template> 
                    </Tbody>
                    <Tbody class="text-right w-32">{{ formatPrice_1(b.total_pay) }}</Tbody>
                    <Tbody class="text-left text-xs first-letter:uppercase">{{ b.text_total_pay}}</Tbody>
                      
                    <Tbody class="w-20 ">
                        <span v-if="b.pay_cash==1" class="flex justify-center">
                            <CheckCircleIcon class="w-6 h-6 text-blue-900"/>
                        </span>
                        <span v-else class="flex justify-center cursor-pointer">
                            <CheckCircleIcon class="w-6 h-6 text-gray-400"/>
                        </span>
                    </Tbody>
                    <Tbody class="w-20 text-center">
                        <span v-if="b.pay_transfer==1" class="flex justify-center">
                            <CheckCircleIcon class="w-6 h-6 text-blue-900"/>
                        </span>
                        <span v-else class="flex justify-center cursor-pointer">
                            <CheckCircleIcon class="w-6 h-6 text-gray-400"/>
                        </span>
                    </Tbody>
                    <Tbody class="text-center text-xs first-letter:uppercase w-32">
                        <span v-if="b.buoi == 'am'">Sáng</span>
                        <span v-if="b.buoi == 'pm'">Chiều</span>
                      
                    </Tbody>
                    <Tbody class="text-center text-xs first-letter:uppercase w-32">
                        <span v-if="b.user">
                            {{ b.user.name}}
                        </span>
                    </Tbody>
                      
                  </TableRow> 
               </template>
               <TableRow >
                    <Tbody class="text-right px-4 font-bold text-blue-900" colspan="6">Tổng:</Tbody>
                    <Tbody class="text-right px-1 font-bold text-blue-900"></Tbody>
                    <Tbody class="text-right px-1 font-bold text-blue-900">{{ formatPrice_1(sum_pay) }}</Tbody>
                    <Tbody class="first-letter:uppercase  text-left px-2 font-bold text-blue-900">{{ text_price }} đồng</Tbody>
                    <Tbody class="first-letter:uppercase  text-right px-2 font-bold text-blue-900">{{  formatPrice_1(sumTienMat) }}</Tbody>
                    <Tbody class="first-letter:uppercase  text-right px-2 font-bold text-blue-900 border-r">{{  formatPrice_1(sumChuyenKhoan) }}</Tbody> 
                  
              </TableRow>
              </template>
              
        </Table>
        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
            <Pagination :links="bills.links"/>
        </div> 
    </div>
      <ConfirmModalApp :show="confirmModel">
            <template #title class="w-full flex justify-end">
                <span @click="closeConfirmModal" class="px-4 py-1 cursor-pointer bg-yellow-900 text-white rounded-sm">Close</span>
            </template>
            <template #content>
                <div class="flex space-x-4 w-full text-md">
                    <span>Bạn chắc xóa:</span>
                    <span class="font-bold pl-2 underline text-red-600 pr-1">Ghi nhận thanh toán Số BN: </span>
                    <span class="text-blue-900 font-bold">{{ id_pay }} ? </span>
                </div>
            </template>
            <template #footer class="text-center">
                <button class="bg-blue-600 text-white px-3 py-1 rounded-lg" @click="handlePayConfirm(id_pay)">Xác Nhận Thanh Toán</button>
            </template>
        </ConfirmModalApp>
    </AdminLayout>
</template>
<script src="./report"></script>

<style>
</style>