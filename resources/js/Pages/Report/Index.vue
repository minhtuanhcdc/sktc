<template>
    <AdminLayout>
      <Head title="Custommer"/>
     <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
        <span class="text-white">Thống kê - Báo cáo</span>
        <span class="text-white">{{ phuong.name}} - {{ quan.name }}</span>
      </div>
      <div class="flex border-2 border-blue-900 items-center space-x-2 py-2 px-3">
        <div class="flex w-4/5 space-x-4">
            <div class="flex w-1/3 border-r-2 items-center" v-if="activeDanhSach">
                <label class="text-blue-900 w-[20%] leading-4 text-right">Ngày cân đo </label>
                <div class="flex w-[80%] pl-4">
                    <div class=" flex flex-row items-center" >
                        <input
                            id="startDate"
                            type="date"
                            class="h-8 block w-full text-sm rounded-md px-1"
                            v-model="startDate"
                            autocomplete="startDate"/> 
                    </div>
                    <div class="flex flex-row ml-2 items-center">
                        <span class="text-blue-900">đến: </span>
                        <input
                            id="endDate"
                            type="date"
                            class= "h-8 block text-sm rounded-md"
                            v-model="endDate"
                            autocomplete="endDate"/>
                    </div>
                </div>
            </div>
            <div class="flex" v-else>
                <span class="cursor-pointer">Năm: </span>
                <input v-model="nam" class="h-7 px-2 rounded-lg border border-blue-900 w-24" placeholder="...Nhập năm">
            </div>
            <div class="flex w-2/3">
                <div class="flex-1 flex items-center w-1/4">
                    <label class="text-blue-900 w-16 text-right pr-1">Q/H:</label>
                    <select v-model="id_service" class="w-full h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                        <option value="">All</option>
                        <template v-for="(s,i) in services" :key="i">
                            <option :value="s.id" class="">- {{ s.name }}</option>
                        </template>
                        
                    </select>
                </div>
                <div class="flex-1 flex items-center w-1/4">
                    <label class="text-blue-900 w-16 text-right pr-1">P/X:</label>
                    <select v-model="id_service" class="w-full h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                        <option value="">All</option>
                        <template v-for="(s,i) in services" :key="i">
                            <option :value="s.id" class="">- {{ s.name }}</option>
                        </template>
                        
                    </select>
                </div>
           
            </div>
        </div>
        <div class="flex space-x-8 w-1/5">
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
    <div class="flex items-center justify-center my-1 px-4">
        <div class="flex items-center px-2" v-show="activeDanhSach">
            <input v-model="termSearch" class="h-7 px-2 rounded-lg border border-blue-900 w-96" placeholder="... nhập mã định danh, tên trẻ">
            <span class="cursor-pointer">Search</span>
        </div>
        <div class="flex -mb-1 space-x-1 justify-center  z-50">
            <div class="ml-20 cursor-pointer" @click="handleThongKe">
                <div class="w-24 text-center md:text-center h-6" :class="[activeThongKe?backgroundClass:'bg-hcdc2']" >Thống kê</div>
                <div class="mx-auto h-0 w-0 border-r-[48px] border-b-[8px] border-l-[48px] border-solid border-r-transparent border-l-transparent border-b-blue-600 transform rotate-180" v-show="activeThongKe"></div>
            </div>
            <div class="cursor-pointer" @click="handleDanhSach">
                <div class="w-24 text-center md:text-center h-6" :class="[activeDanhSach?backgroundClass:'bg-hcdc2']" >Danh sách</div>  
                <div class="mx-auto h-0 w-0 border-r-[48px] border-b-[8px] border-l-[48px] border-solid border-r-transparent border-l-transparent border-b-blue-600 transform rotate-180" v-show="activeDanhSach"></div>
             </div>
        </div>
        <div class="flex items-center space-x-2" v-show="activeDanhSach">
            <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" />
        </div>
    </div>
    <div class="relative h-[85%] overflow-x-auto shadow-md sm:rounded-lg mt-1 ">
       <div v-if="activeDanhSach">
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
                                <span class="flex flex-column justify-center" v-if=" w.weigthForLength">{{ w.weigthForLength }}</span>
                                <span class="flex flex-column justify-center" v-else>&nbsp;</span>
                                <hr v-show="index < c.paraminput.length - 1" class="border-gray-800 border-1 w-full">
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" >
                            <span v-for="(k,i) in c.khamdinhkis" :key="i">
                                <span class="flex flex-column" v-if=" k.ngay_kham">- {{ k.ngay_kham }}</span>
                                <span v-else> </span>
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
       </div>
       <div v-else class="flex justify-center">
        <table class="border-collapse border border-slate-400 w-[60%] h-full">
            <thead>
                <tr class="bg-gray-200 sticky top-0">
                    <th class="border border-slate-300 ">Stt</th>
                    <th class="border border-slate-300 w-[50%] ">Nội dung</th>
                    <th class="border border-slate-300 ">Quí I</th>
                    <th class="border border-slate-300 ">Quí II</th>
                    <th class="border border-slate-300 ">Qui III</th>
                    <th class="border border-slate-300 ">Qui IV</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-slate-300 text-center font-bold">I</td>
                    <td class="border border-slate-300 font-bold">CHỈ SỐ CƠ BẢN</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    
                    <td class="border border-slate-300 ..."></td>
                    
                </tr> 
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 font-bold">Số trẻ 0-60 tháng: </td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                   
                   
               
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Số trẻ gái</td>
                    <td class="border border-slate-300 text-center">{{ girl_I }}</td>
                    <td class="border border-slate-300 text-center">{{ girl_II }}</td>
                    <td class="border border-slate-300 text-center">{{ girl_III }}</td>
                    <td class="border border-slate-300 text-center">{{ girl_IV }}</td>
                    
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ trai</td>
                    <td class="border border-slate-300 text-center">{{ boy_I }}</td>
                    <td class="border border-slate-300 text-center">{{ boy_II }}</td>
                    <td class="border border-slate-300 text-center">{{ boy_III }}</td>
                    <td class="border border-slate-300 text-center">{{ boy_IV }}</td>
                   
                   
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ 25-60 tháng</td>
                    <td class="border border-slate-300 text-center">{{ child_25_60_I }}</td>
                    <td class="border border-slate-300 text-center">{{ child_25_60_II }}</td>
                    <td class="border border-slate-300 text-center">{{ child_25_60_III }}</td>
                    <td class="border border-slate-300 text-center">{{ child_25_60_IV }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ 0-24 tháng</td>
                    <td class="border border-slate-300 text-center">{{ child_0_24_I }}</td>
                    <td class="border border-slate-300 text-center">{{ child_0_24_II }}</td>
                    <td class="border border-slate-300 text-center">{{ child_0_24_III }}</td>
                    <td class="border border-slate-300 text-center">{{ child_0_24_IV }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ từ dưới 6 tháng</td>
                    <td class="border border-slate-300 text-center">{{child_tu_duoi_6_I }}</td>
                    <td class="border border-slate-300 text-center">{{child_tu_duoi_6_II }}</td>
                    <td class="border border-slate-300 text-center">{{child_tu_duoi_6_III }}</td>
                    <td class="border border-slate-300 text-center">{{child_tu_duoi_6_IV }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ sơ sinh sống</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ child_alive }}</td>
                </tr>
                
                <tr>
                    <td class="border border-slate-300 text-center font-bold">II</td>
                    <td class="border border-slate-300 font-bold">KẾT QUẢ THỰC HIỆN</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                
                </tr>
                <tr>
                    <td class="border border-slate-300 font-bold text-right pr-2">1. </td>
                    <td class="border border-slate-300 font-bold">Quản lý trẻ </td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
               
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.1</td>
                    <td class="border border-slate-300 ">Số trẻ từ 25-60 tháng tuổi được cân, đo 1 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ cando1 }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Tỉ lệ 1.1(%)</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ tileCanDoLan1_25_60 }}</td>
                </tr>              
                <tr>
                    <td class="border border-slate-300 text-right">1.2 </td>
                    <td class="border border-slate-300 ">Số trẻ từ 25-60 tháng tuổi được cân, đo 2 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ cando2 }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"> </td>
                    <td class="border border-slate-300 ">- Tỉ lệ 1.2(%)</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td clas
                    s="border border-slate-300 text-center">{{ tileCanDoLan2_25_60 }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.3 </td>
                    <td class="border border-slate-300 ">Số trẻ từ 0-24 tháng tuổi được cân, đo 3 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ cando23}}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Tại TYT</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tại cơ sở y tế khác</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 1.3(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.4 </td>
                    <td class="border border-slate-300 ...">Số trẻ sơ sinh nhẹ cân &lt; 2500gr (KH 2022&lt;8%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">{{ soSinhDuoi2500 }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">-Tỉ lệ 1.4(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">{{ tiLeDuoi2500 }}</td>
                </tr>
                <tr>
                <td class="border border-slate-300 font-bold text-right pr-2">2. </td>
                <td class="border border-slate-300 font-bold">Quản lý trẻ suy dinh dưỡng béo phì</td>
                <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
               
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.1</td>
                    <td class="border border-slate-300 ...">Số trẻ từ 0-60 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 2.1(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>   
                <tr>
                    <td class="border border-slate-300 text-right">1.2 </td>
                    <td class="border border-slate-300 ...">-Tổng số trẻ SDD vừa</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.2 </td>
                    <td class="border border-slate-300 ...">Số trẻ từ 0-60 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ">-Tỉ lệ mục 2.2(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tổng số trẻ thấp còi độ I</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tổng số trẻ thấp còi độ II</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.3</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.3(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T được theo dõi hàng tháng</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.4</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.4(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T được theo dõi hàng tháng</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.5</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.5(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T được theo dõi 2 tháng /lần</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                
                <tr>
                    <td class="border border-slate-300 text-right">2.6</td>
                    <td class="border border-slate-300 ...">Số trẻ 25-60 tháng tuổi SĐ CC/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.6(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CC/T được theo dõi 2 tháng /lần</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CC/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.7</td>
                    <td class="border border-slate-300 ...">Số trẻ dưới 5 tuổi tại nhà trẻ mẫu giáo</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">Tổng số cơ sở giáo dục mầm non trên địa bàn</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">Tổng số cơ sở mầm non gửi danh sách kết quả cân đo trẻ định kỳ về TYT</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ dưới 5 tuổi được phát hiện béo phì</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 2.7(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                </tr>
            </tbody>
        </table>
       </div>

        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8 sticky -bottom-1"  v-show="activeDanhSach">
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