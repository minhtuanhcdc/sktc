<template>
    <AdminLayout>
      <Head title="Custommer"/>
     <div class="bg-blue-600 px-8 flex items-center justify-between h-8"> 
        <span class="text-white">Thống kê - Báo cáo</span>
        <span class="text-white">{{}} - {{ }}</span>
      </div>
      <div class="border-2 border-blue-900 items-center flex">
        <div class=" w-[90%] py-2">
          
                <div class="flex space-x-2 items-center">
                    <label class="text-blue-900 w-24 leading-4 bg-blue-200 font-bold text-center">Ng. cân đo </label>
                    <div class="flex border-r-2 items-center w-[70%]" v-if="danhsach">  
                        <label class="text-xs">Từ tháng: </label>
                        <div class=" flex flex-row items-center" >
                            <input
                                id="startMonth"
                                type="text"
                                class="h-8 block w-8 text-sm rounded-md px-1 text-center"
                                v-model="startMonth"
                                autocomplete="startMonth"/> 
                        </div>
                        <div class="flex flex-row ml-2 items-center">
                            <span class="text-blue-900 text-xs">đến: </span>
                            <input
                                id="endMonth"
                                type="text"
                                class= "h-8 block text-sm rounded-md w-8 px-1 text-center"
                                v-model="endMonth"
                                autocomplete="endMonth"/>
                        </div>
                        <div class="flex items-center">
                            <span class="text-xs w-8 pl-2">Năm: </span>
                            <input v-model="nam" class="h-7 px-1 text-center rounded-lg border border-blue-900 w-14" placeholder="">
                        </div>
                        <div class="flex items-center border-r-2 border-blue-300"> &nbsp;</div>
                        <div class="flex items-center">
                            <span class="text-xs w-14 text-right">Tháng: </span>
                            <input v-model="month" class="h-7 px-1 text-center rounded-lg border border-blue-900 w-8" placeholder="">
                        </div>
                        <div class="flex items-center border-r-2 border-blue-300"> &nbsp;</div>
                        <div class=" flex items-center">
                            <label class="text-blue-900 w-14 text-right pr-1 font-bold pl-2">TTDD: </label>
                            <div class="flex items-center pl-4"> 
                                <label class="text-blue-900 w-8 text-right pr-1 text-xs">CN/T</label>
                                <select v-model="id_service" class="w-24 h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                                    <option value="">--</option>
                                    <option value="">BT</option>
                                    <option value="">SDD độ I </option>
                                    <option value="">SDD độ II </option>
                                    
                                </select>
                            </div>
                            <div class="flex items-center pl-4"> 
                                <label class="text-blue-900 w-8 text-right pr-1 text-xs">CC/T</label>
                                <select v-model="id_service" class="w-24 h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                                    <option value="">--</option>
                                    <option value="">BT</option>
                                    <option value="">Thấp còi độ I </option>
                                    <option value="">Thấp còi độ II </option>
                                    
                                </select>
                            </div>
                            <div class="flex items-center pl-4 w-48"> 
                                <label class="text-blue-900 w-14 text-right pr-1 text-xs">CN/CC</label>
                                <select v-model="id_service" class="w-32 h-7 p-0 px-1 border border-blue-900 rounded-lg text-xs">
                                    <option value="">--</option>
                                    <option value="">BT</option>
                                    <option value="">Thừa cân </option>
                                    <option value="">Béo phì </option>
                                    <option value="">SDD gầy còm độ I </option>
                                    <option value="">SDD gầy còm độ II </option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center border-r-2 border-blue-300"> &nbsp;</div>
                        <div class="flex pl-2">
                            <div class="flex-1 flex items-center">
                                <label class="text-blue-900 w-16 text-right pr-1">Q/H:</label>
                                <select v-model="id_district" class="w-32 h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                                    <option value="">--</option>
                                    <option :value="q.code" v-for="(q,i) in districts" :key="i">{{ q.name }}</option>
                                </select>
                            </div>
                            <div class="flex items-center w-[60%]">
                                <label class="text-blue-900 w-16 text-right pr-1">P/X:</label>
                                <select v-model="id_ward" class="w-48 h-7 p-0 px-2 border border-blue-900 rounded-lg text-xs">
                                    <option value="">--</option>
                                    <option :value="w.code" v-for="(w,i) in wards" :key="i">{{ w.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="flex w-[10%] justify-center">
            <!-- <div class="flex space-x-3">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2" @click="searchData">Filter</span>
                <span class="text-white bg-green-500 rounded-sm cursor-pointer px-2 py-1 items-center flex" @click="Clear()">Clear Filter</span>
            </div> -->
            <div class="flex items-center justify-end">
                <a :href="route('exportReport',{'buoi':buoi,'startDate':startDate,'endDate':endDate,'id_service':id_service,'id_post':id_post,'pay':pay})" target="blank">
                <span class="text-white bg-blue-600 rounded-sm cursor-pointer px-2 py-2 ">Xuất File Excel</span>
                </a>
            </div> 
        </div>
        </div>


    <div class="flex items-center justify-center my-1 px-4">
        <div class="flex items-center px-2" v-show="danhsach">
            <input v-model="termSearch" class="h-7 px-2 rounded-lg border border-blue-900 w-96" placeholder="... nhập mã định danh, tên trẻ">
            <span class="cursor-pointer">Search</span>
        </div>
        <div class="flex -mb-1 space-x-1 justify-center  z-50">
           
            <div class="cursor-pointer" @click="handleDanhSach">
                <div class="w-24 text-center md:text-center h-6" :class="[danhsach?backgroundClass:'bg-gray-400']" >Danh sách</div>  
                <div :class="danhsach?danhsach:''" v-show="danhsach"></div>
             </div>
             <div class="ml-20 cursor-pointer" @click="handleThongKe">
                <div class="w-24 text-center md:text-center h-6" :class="thongke?backgroundClass:'bg-gray-400'" >Thống kê</div>
                <div :class="thongke?classView:''" v-show="thongke"></div>
            </div>
        </div>
        <div class="flex items-center space-x-2" v-show="danhsach">
            <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="propsFill" />
        </div>
    </div>
    <div class="relative h-[85%] overflow-x-auto shadow-md sm:rounded-lg mt-1 ">
       <div v-show="danhsach">
        <Table :classTable="classTable" :classThead="classThead">
              <template #header>
                  <TableHeader :headers="headers" class="bg-blue-600 text-white sticky top-0 z-10 text-xs text-center"/>
              </template>    
               <template #tbody>
                <template v-for="(c,i) in childs.data">
                    <TableRow :classRow="classRow">
                        <Tbody :class="classtBody" class="text-center">{{ i+1 }}</Tbody>
                        <Tbody :class="classtBody">{{ this.perPage }}</Tbody>
                        <Tbody :class="classtBody">{{ c.name }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">{{ formatDate(c.birthday)}}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-if="c.sex == 1">Nam</span>
                            <span v-else>Nữ</span>
                        </Tbody>
                        <Tbody :class="classtBody" class="w-[20%]" >{{ c.address }}</Tbody>
                        <Tbody :class="classtBody" >{{  }}</Tbody>
                        <Tbody :class="classtBody" >{{  }}</Tbody>
                        <Tbody :class="classtBody" >{{ c.parent }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <div v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                              
                                    <p class="w-full bg-red-100" :class="index != c.paraminput.length - 1 ?'border-gray-500 border-b':''">{{d.input_date}}</p>
                                
                            </div>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <div v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                               
                                    <p class="w-full bg-red-100" :class="index != c.paraminput.length - 1?'border-gray-500 border-b':''">{{d.month}}</p>
                               
                            </div>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center px-0">
                            <div v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                               
                                    <p class="w-full bg-red-100" :class="index != c.paraminput.length - 1?'border-gray-500 border-b':''">{{d.weigth}}</p>
                                
                            </div>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <div v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                              
                                    <p class="w-full bg-red-100" :class="index != c.paraminput.length - 1?'border-gray-500 border-b':''">{{d.length}}</p>
                               
                            </div>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                    <p class="w-full" :class="index != c.paraminput.length  - 1?'border-gray-500 border-b':''">{{ d.lengthForAge }}</p>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput " :key="index" class="text-center px-0">
                                    <p class="w-full" :class="index != c.paraminput.length - 1?'border-gray-500 border-b':''">{{ d.weigthForAge }}</p>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                
                                    <p class="w-full" :class="index != c.paraminput.length - 1?'border-gray-500 border-b':''">{{ d.weigthForLength }}</p>
                               
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
       <div v-show="thongke" class="flex justify-center">
        <table class="border-collapse border border-slate-400 w-[60%] h-full" v-if="dataFills">
            <thead>
                <tr class="bg-gray-200 sticky top-0">
                    <th class="border border-slate-300 ">Stt</th>
                    <th class="border border-slate-300 w-[50%]">Nội dung</th>
                    <th class="border border-slate-300 ">Quí I</th>
                    <th class="border border-slate-300 ">Quí II</th>
                    <th class="border border-slate-300 ">Qui III</th>
                    <th class="border border-slate-300 ">Qui IV</th>
                    <th class="border border-slate-300 ">Cả năm</th>
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
                    <td class="border border-slate-300 ..."></td>
                </tr> 
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 font-bold">Số trẻ 0-60 tháng: {{ child_alive_year }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['dataFills'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['child_alive_II'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['child_alive_III'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['child_alive_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Số trẻ gái</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['girl_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['girl_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['girl_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['girl_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ trai</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['boy_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['boy_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['boy_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['boy_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ 25-60 tháng</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_25_60_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_25_60_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_25_60_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_25_60_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['']}}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ 0-24 tháng</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_0_24_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_0_24_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_0_24_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_0_24_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['']}}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ từ dưới 6 tháng</td>
                    <td class="border border-slate-300 text-center">{{dataFills['child_tu_duoi_6_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{dataFills['child_tu_duoi_6_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{dataFills['child_tu_duoi_6_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{dataFills['child_tu_duoi_6_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ dataFills['']}}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Số trẻ sơ sinh sống</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_alive_I'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_alive_II'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_alive_III'] }}</td>
                    <td class="border border-slate-300 text-center">{{ dataFills['child_alive_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                
                <tr>
                    <td class="border border-slate-300 text-center font-bold">II</td>
                    <td class="border border-slate-300 font-bold">KẾT QUẢ THỰC HIỆN</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                
                </tr>
                <tr>
                    <td class="border border-slate-300 font-bold text-right pr-2">1. </td>
                    <td class="border border-slate-300 font-bold">Quản lý trẻ </td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.1</td>
                    <td class="border border-slate-300 ">Số trẻ từ 25-60 tháng tuổi được cân, đo 1 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ dataFills['canDo1Lan_25_60'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Tỉ lệ 1.1(%)</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ dataFills['tiLeCanDo1Lan_25_60'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>              
                <tr>
                    <td class="border border-slate-300 text-right">1.2 </td>
                    <td class="border border-slate-300 ">Số trẻ từ 25-60 tháng tuổi được cân, đo 2 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ dataFills['canDo2Lan_25_60'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"> </td>
                    <td class="border border-slate-300 ">- Tỉ lệ 1.2(%)</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ dataFills['tiLeCanDo2Lan_25_60'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.3 </td>
                    <td class="border border-slate-300 ">Số trẻ từ 0-24 tháng tuổi được cân, đo 3 lần/năm</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center">{{ dataFills['tiLeCanDo3Lan_25_60']}}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 ">- Tại TYT</td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-center"></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tại cơ sở y tế khác</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 1.3(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">1.4 </td>
                    <td class="border border-slate-300 ...">Số trẻ sơ sinh nhẹ cân &lt; 2500gr (KH 2022&lt;8%)</td>
                    <td class="border border-slate-300 ...">{{ dataFills['soSinhDuoi2500_I'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['soSinhDuoi2500_II'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['soSinhDuoi2500_III'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['soSinhDuoi2500_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">-Tỉ lệ 1.4(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">{{ tiLeDuoi2500 }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                <td class="border border-slate-300 font-bold text-right pr-2">2. </td>
                <td class="border border-slate-300 font-bold">Quản lý trẻ suy dinh dưỡng béo phì: {{weigthForAge_0_60  }}</td>
                <td class="border border-slate-300 ..."></td>
                <td class="border border-slate-300 ..."></td>
                <td class="border border-slate-300 ..."></td>
                <td class="border border-slate-300 ..."></td>
                <td class="border border-slate-300 text-right font-bold">{{ }}</td>
               
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.1</td>
                    <td class="border border-slate-300 ...">Số trẻ từ 0-60 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 2.1(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>   
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">-Tổng số trẻ SDD độ I</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoI_I'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoI_II'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoI_III'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoI_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">-Tổng số trẻ SDD độ II</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoII_I'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoII_II'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoII_III'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['suyDDdoII_IV'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.2 </td>
                    <td class="border border-slate-300 ...">Số trẻ từ 0-60 tháng tuổi SDD CC/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ">-Tỉ lệ mục 2.2(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tổng số trẻ thấp còi độ I</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoI_I']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoI_II']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoI_III']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoI_IV']}}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">- Tổng số trẻ thấp còi độ II</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoII_I']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoII_II']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoII_III']}}</td>
                    <td class="border border-slate-300 ...">{{dataFills['thaoCoidoII_IV']}}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.3</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.3(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T được theo dõi hàng tháng</td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddHangThang_CN_T_I'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddHangThang_CN_T_II'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddHangThang_CN_T_III'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddHangThang_CN_T_IV'] }}</td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddHangThang_CN_T'] }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CN/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.4</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.4(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T được theo dõi hàng tháng</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ...">{{ dataFills['sddTheoDoiHangThang'] }}</td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 0-24 tháng tuổi SDD CC/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.5</td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.5(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T được theo dõi 2 tháng /lần</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CN/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                
                <tr>
                    <td class="border border-slate-300 text-right">2.6</td>
                    <td class="border border-slate-300 ...">Số trẻ 25-60 tháng tuổi SĐ CC/T</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">- Tỉ lệ mục 2.6(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CC/T được theo dõi 2 tháng /lần</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ từ 25-60 tháng tuổi SDD CC/T được hồi phục</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right">2.7</td>
                    <td class="border border-slate-300 ...">Số trẻ dưới 5 tuổi tại nhà trẻ mẫu giáo</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"></td>
                    <td class="border border-slate-300 ...">Tổng số cơ sở giáo dục mầm non trên địa bàn</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">Tổng số cơ sở mầm non gửi danh sách kết quả cân đo trẻ định kỳ về TYT</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Số trẻ dưới 5 tuổi được phát hiện béo phì</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-300 text-right"> </td>
                    <td class="border border-slate-300 ...">- Tỉ lệ 2.7(%)</td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 ..."></td>
                    <td class="border border-slate-300 text-right font-bold">{{ }}</td>
                </tr>
            </tbody>
        </table>
       </div>

        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8 sticky -bottom-1"  v-show="danhsach">
            <Pagination :links="childs.links"/>
        </div> 
    </div>
      
    </AdminLayout>
</template>
<script src="./report"></script>

<style>
</style>