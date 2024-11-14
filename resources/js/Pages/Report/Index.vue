<template>
    <AdminLayout :chuyenKhoan="chuyenKhoan" :tienMat="tienMat"  :total_pay="total_pay" :unpaid="unpaid">
      <Head title="Custommer"/>
     <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
        <span class="text-white">Thống kê - Báo cáo</span>
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
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2 ">Xuất File Excel</span>
                </a>
            </div> 
        </div>
      </div>
    <div class="flex items-center justify-between my-3 px-4">
        <div class="flex items-center px-2">
            <input v-model="termSearch" class="h-7 px-2 rounded-lg border border-blue-900 w-96" placeholder="... nhập mã định danh, tên trẻ">
            <span class="cursor-pointer">Search</span>
        </div>
        <div class="flex items-center space-x-2">
            <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" />
        </div>
    </div>
    <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-2">
        <Table :classTable="classTable" :classThead="classThead">
              <template #header>
                  <TableHeader :headers="headers" class="bg-blue-600 text-white sticky top-0 z-10 text-xs text-center"/>
              </template>    
               <template #tbody>
                <template v-for="(c,i) in childs.data">
                    <TableRow :classRow="classRow">
                        <Tbody :class="classtBody" class="text-center">{{ i+1 }}</Tbody>
                        <Tbody :class="classtBody">{{ c.madinhdanh }}</Tbody>
                        <Tbody :class="classtBody">{{ c.name }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">{{ formatDate(c.birthday)}}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-if="c.sex == 1">Nam</span>
                            <span v-else>Nữ</span>
                        </Tbody>
                        <Tbody :class="classtBody" >{{ c.address }}</Tbody>
                        <Tbody :class="classtBody" >{{  }}</Tbody>
                        <Tbody :class="classtBody" >{{  }}</Tbody>
                        <Tbody :class="classtBody" >{{ c.parent }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-for="(da,i) in c.paraminput" :key="i">
                                <span class="flex flex-column">- {{ formatDate(da.input_date) }}</span>
                                <hr v-show="i < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center px-0">
                            <span v-for="(w,index) in c.paraminput" :key="index" class="text-center px-0">
                                <span class="flex flex-column justify-center">{{ w.weigth }}</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                            </Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-for="(w,index) in c.paraminput" :key="index" class="text-center">
                                <span class="flex flex-column justify-center">{{ w.length }}</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(w,index) in c.paraminput" :key="index" class="text-center">
                                <span class="flex flex-column justify-center">{{ w.lengthForAge }}</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(w,index) in c.paraminput" :key="index" class="text-center">
                                <span class="flex flex-column justify-center">{{ w.weigthForAge }}</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(w,index) in c.paraminput" :key="index" class="text-center">
                                <span class="flex flex-column justify-center">{{ w.weigthForLength }}</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" >
                            <span v-for="(k,i) in c.khamdinhkis" :key="i">
                                <span class="flex flex-column" v-if=" k.ngay_kham">- {{ k.ngay_kham }}</span>
                                <span v-else> 1</span>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" >
                            <span v-for="(v,i) in c.vitamins" :key="i">
                                <span class="flex flex-column">- {{ v.ngay_uong }}</span>
                            </span>
                        </Tbody>
                      
                        
                    </TableRow> 
                </template>
              </template> 
              
        </Table>
        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8 sticky -bottom-1">
            <Pagination :links="childs.links"/>
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