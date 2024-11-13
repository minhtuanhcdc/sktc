<template >
    <div class="w-full mt-2 p-2 border-1">
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <Table :classTable="classTable" :classThead="classThead" class="w-[70%]">
                    <template #header>
                        <TableHeader :headers="headers" class="bg-hcdc1 text-center text-white text-xs"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(b,i) in info_childs.data" :key="i">
                            <Tbody :class="classTD" class="px-1 text-center">{{ i+1 }}</Tbody>
                            <Tbody :class="classTD">{{ b.name }}</Tbody>
                            <Tbody :class="classTD" class="text-center">{{ b.birthday }}</Tbody>
                            <Tbody :class="classTD">
                               <span v-if="b.sex ==1">Nam</span>
                               <span v-else>Nữ</span>
                            </Tbody>
                            <Tbody :class="classTD">{{ b.address }}</Tbody>
                            <Tbody :class="classTD">{{ b.parent }}</Tbody>
                            <Tbody :class="classTD">{{ b.weightbirth }}</Tbody>
                            <Tbody :class="classTD">
                                <span v-for="(ngay,i) in b.khamdinhkis" :key="i" class="flex flex-col">- {{ ngay.ngay_kham }}</span>
                             </Tbody>
                            <Tbody :class="classTD">
                                <span v-for="(vt,i) in b.vitamins" :key="i" class="flex flex-col">- {{ vt.ngay_uong}}</span>
                             </Tbody>
                            <Tbody :class="classTD"><span v-if="b.paraminput"> {{ b.paraminput.input_date }}</span></Tbody>
                            <Tbody :class="classTD"><span v-if="b.paraminput">{{ b.paraminput.month }}</span></Tbody>
                            <Tbody :class="classTD">
                                <span v-if="b.paraminput">{{ b.paraminput.weigth }}</span>
                                <span v-else></span>
                            </Tbody>
                            <Tbody :class="classTD"><span v-if="b.paraminput">{{ b.paraminput.length }}</span></Tbody>
                            <Tbody :class="classTD">
                                <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.lengthForAge }} </span>
                            </Tbody>
                            <Tbody :class="classTD">  
                                <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.weigthForAge }} </span>  
                            </Tbody>
                            <Tbody :class="classTD">
                                <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.weigthForLength }} </span>
                            </Tbody>
                            <Tbody :class="classTD">
                               <div class="flex items-center justify-between px-3">
                                    <span class="" title="Cập nhật TT">
                                        <IdentificationIcon  class="w-6 text-blue-800 cursor-pointer text-center h-full" @click="infoUpdate(b)"/>
                                    </span>
                                    <span class="" title="Thêm TT">
                                        <PlusIcon class="w-6 text-blue-800 cursor-pointer text-center h-full" title="Thêm thông tin" @click="infoAdd(b)"/>
                                    </span>
                               </div>
                            </Tbody>
                        </TableRow> 
                    </template>
            </Table>
        </div>
        <div class="flex mt-2  items-center py-0 h-8">
             <Pagination :links="info_childs.links"/>
        </div>
        <ModalUpdate :show="openModalUpdate" :maxWidth ="maxWidth">
            <div class="flex justify-between p-2">
                <span class="w-[80%] font-bold text-hcdc2 text-center">Cập nhật thông tin</span>
                <span @click="closeInfoUpdate()" class="py-1 px-3 bg-yellow-300 cursor-pointer hover:bg-yellow-400">Close</span>
            </div>
            <div>
                <form >
                    <fieldset class="px-2 py-0  z-40 mb-2 bg-slate-200">
                        <!-- <legend class="text-sm text-white font-bold pt-4">Nhập thông tin</legend> -->
                        <div class="flex justify-end mb-1 mt-1">
                            <div>
                                <button v-if="showAdd" class="rounded-lg px-6 py-2 bg-hcdc2 h-6 flex items-center cursor-pointer hover:bg-blue-400 text-white">
                                    <span><XMarkIcon class="w-6 h-6"/></span>
                                    <span @click="closeForm">Đóng</span>
                                </button>
                            </div>
                        </div>
                        <div class=""> 
                            <div class="border border-2 border-hcdc2 px-1">
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center w-[25%]">
                                        <label class="w-[20%] text-left text-blue-900 font-bold">Tên trẻ:</label>
                                        <input v-model="form.name" id="name" type="text" class="w-[80%] border border-blue-700 h-7 rounded-md"  autocomplete="name" />
                                        <!-- <InputErrorApp :message="form.errors.name" class="text-center" />  -->
                                    </div> 
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-8 text-left text-blue-900 font-bold">NS:</label>
                                        <input v-model="form.birthday" id="phone" type="date" class="inputText border border-blue-700 w-full h-7 rounded-md"  autocomplete="birth" />
                                    </div> 
                                    <div class="flex items-center w-[10%] space-x-2">
                                        <span class=" text-blue-900 font-bold w-[15%]">GT:</span>
                                        <div class="space-x-1 w-[90%]">
                                            <input type="radio" v-model="form.sex" value="1"/><span class="text-xs">Nam</span>
                                            <input type="radio" v-model="form.sex" value="0"/><span class="text-xs">Nữ</span>
                                        </div>
                                    </div>  
                                    <div class="flex items-center w-[20%]">
                                        <label class="w-[20%] text-blue-900 font-bold text-sm leading-4 text-right pr-1">Tên mẹ:</label>
                                        <input v-model="form.parent" id="parent" type="text" class="w-[80%] border border-blue-700 flex-1 h-7 rounded-md"  autocomplete="parent" />
                                        <!-- <InputErrorApp :message="form.errors.parent" class="text-center" /> -->
                                    </div> 
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-[30%] leading-4 text-blue-900 font-bold text-sm flex justify-end">Mã Đd:</label>
                                        <input v-model="form.madinhdanh" id="madinhdanh" type="text" class="w-[70%] border border-blue-700 h-7 rounded-md"  autocomplete="madinhdanh" />
                                    </div>  
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-[25%] leading-4 text-blue-900 font-bold text-sm text-right pr-1">Mã TC:</label>
                                        <input v-model="form.matiemchung" id="matiemchung" type="text" class="w-[75%] border border-blue-700 h-7 rounded-md" autocomplete="matiemchung" />
                                    </div>  
                                </div>
                                <div class="flex mt-2 items-center space-x-3">
                                    <div class="w-1/4 flex items-center">
                                        <label class=" pr-1 text-blue-900 font-bold text-right pr-1 leading-4">Địa chỉ:</label>
                                        <input v-model="form.address" id="address" type="text" class="inputText border border-blue-700 flex-1 h-7 rounded-md"  autocomplete="address" />
                                    </div> 
                                    <div class="w-1/4 flex items-center">
                                        <label class="text-blue-900 font-bold">Tỉnh/thành</label>
                                        <select   v-model="form.id_province" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                            <option value="">-</option>
                                            <option v-for="(pce, i) in provinces" :key="i" :value="pce.code">
                                                <span>  {{pce.name}}</span>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="w-1/4 flex items-center">
                                        <label class="text-blue-900 font-bold">Q/H</label>
                                        <select v-model="form.id_district" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                            <option value=""></option>
                                            <option v-for="(d,i) in getdistricts" :key="i" :value="d.code">{{ d.name }}</option>
                                        </select>
                                    </div>
                                    <div class="w-1/4 flex items-center">
                                        <label class="text-blue-900 font-bold">P/X</label>
                                        <select  v-model="form.id_ward" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                            <option value="">-</option>
                                            <option v-for="(w,i) in wards" :key="i" :value="w.code">{{w.name}}</option>
                                        </select>
                                    </div>   
                                    <div class="flex space-x-4">
                                        <div class="flex items-center">
                                            <label class="font-bold text-blue-900 text-sm text-right leading-4">CN lúc sinh:</label>
                                            <input class="h-7 rounded-sm border w-14" v-model="form.weightbirth"/> (kg)
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="my-4 text-center">
                                    <span class="px-2 py-3 bg-hcdc2 cursor-pointer hover:bg-yellow-100 rounded-lg" @click="updateInfoEmit()">Cập nhật thông tin</span>
                                </div>
                            </div>
                            <div class=" mt-4 border border-2 border-hcdc1 py-2 flex">
                               
                                        <div class="w-[30%] px-2 border border-r-red-600 border-r-2">
                                            <span class="w-[100%] h-7 font-bold">Khám ĐK: </span>
                                            <div  v-for="(ng,i) in nDay" :key="i" class="flex items-center">
                                                <span class=" text-md font-bold border border-red-700 rounded" @click ="minusDay">
                                                   <span class="cursor-pointer p-2 text-red-600 font-bold border"> - </span> 
                                                </span>
                                                <input type="date" class="w-[100%] h-7 rounded-sm " v-model="form.khamDinhKy[i]"/> 
                                            </div>
                                            <div class="text-center">
                                                <span class="cursor-pointer text-md font-bold " @click="addDay">+</span>
                                            </div>
                                            <div class="flex justify-center self-center"> 
                                                <span class="text-white cursor-pointer bg-blue-400 px-3 py-1 rounded hover:bg-blue-300" @click ="updateNgayKhamEmit()">Cập nhật NK</span>
                                            </div>
                                        </div>
                                        <div class="w-[30%] px-2 border border-r-red-600 border-r-2">
                                            <span class="w-[100%] h-7 font-bold">Vitamin: </span>
                                            <div  v-for="(ngV,i) in vDay" :key="i" class="flex items-center">
                                                <span class="text-md font-bold border border-red-700 rounded" @click ="vMinusDay">
                                                   <span class="cursor-pointer p-2 "> - </span> 
                                                </span>
                                                <input type="date" class="w-[100%] h-7 rounded-sm " v-model="form.ngay_uong[i]"/> 
                                            </div>
                                            <div class="flex justify-between w-[100%] px-2">
                                                
                                                <span class="cursor-pointer text-lg font-bold w-[50%] text-right" @click="vAddDay">+</span>
                                            </div>
                                            <div class="flex justify-center mt-2 mb-2"> 
                                                <span class="text-white cursor-pointer bg-blue-400 px-3 py-2 rounded hover:bg-blue-300" @click ="updateVitaminEmit()">Cập nhật NU</span>
                                            </div>
                                        </div>                
                                    <div class="w-[60%] flex flex-col"> 
                                        <div class="flex items-center">
                                            <div class=" item-center flex flex-col pl-4 w-[50%]">
                                                <label class="font-bold">Ngày cân đo: </label>
                                                <input type="date" class="h-7" v-model="form.input_date"/>
                                            </div>
                                            <div class="flex flex-col items-center w-[50%]">    
                                                <div class="font-bold text-sm">Tháng tuổi:</div>
                                                <div class="font-bold ml-2">{{month_birth }} </div>
                                            </div>
                                        </div>  
                                        <div class="flex mt-2 items-center">
                                            <div class="item-center flex flex-col pl-4 px-2 w-[40%]">
                                                <label class=" font-bold " >Cân nặng(kg): </label>
                                                <input type="text" class="h-7 p-0  text-center" v-model="form.weigth"/>
                                            </div>
                                        
                                            <div class=" item-center flex flex-col px-2 w-[30%]">
                                                <label class=" font-bold" >Chiều cao(cm): </label>
                                                <input type="text" class=" h-7 p-0 text-center" v-model="form.length"/>
                                            </div>
                                            <div class="flex w-[30%]">
                                                <label class="w-[30%] font-bold text-right">BMI:</label>
                                                <span class="w-[70%] font-bold text-hcdc2">{{ handleBMI }} (kg/m<sup>2</sup>)</span> 
                                            </div> 

                                        </div>
                                        <div class="flex justify-center mt-2 mb-2"> 
                                            <span class="text-white cursor-pointer bg-blue-400 px-3 py-2 rounded hover:bg-blue-300" @click ="updateParamEmit()">Cập nhật thông số</span>
                                        </div>
                                    </div>   
                                <!-- <InputErrorApp :message="form.errors.qty" :classError="classError" />  --> 
                            </div>
                        </div>
                    </fieldset>
                </form> 
            </div>

        </ModalUpdate>
        <ModalAdd :show="openModalAdd" :maxWidth ="maxWidth">
            <div class="flex justify-between p-2">
                <span class="w-[80%] font-bold text-hcdc2 text-center">Nhập Thông Tin</span>
                <span @click="closeInfoAdd()" class="py-1 px-3 bg-yellow-300 cursor-pointer hover:bg-yellow-400">Close</span>
            </div>
            <div>
                <form @submit.prevent="addParamEmit()">
                    <fieldset class="px-2 py-0  z-40 mb-2 bg-slate-200">
                        <!-- <legend class="text-sm text-white font-bold pt-4">Nhập thông tin</legend> -->
                        <div class="flex justify-end mb-1 mt-1">
                            <div>
                                <button v-if="showAdd" class="rounded-lg px-6 py-2 bg-hcdc2 h-6 flex items-center cursor-pointer hover:bg-blue-400 text-white">
                                    <span><XMarkIcon class="w-6 h-6"/></span>
                                    <span @click="closeForm">Đóng</span>
                                </button>
                            </div>
                        </div>
                        <div class=""> 
                            <div class="border border-2 border-hcdc2 px-1 py-4">
                                <div class="flex items-center space-x-2">
                                   
                                    <div class="flex items-center w-[25%]">
                                        <label class="w-[20%] text-left text-blue-900 font-bold">Tên trẻ:</label>
                                        <input v-model="form.name" id="name" type="text" class="w-[80%] border border-blue-700 h-7 rounded-md " disabled  autocomplete="name" />
                                        <!-- <InputErrorApp :message="form.errors.name" class="text-center" />  -->
                                    </div> 
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-8 text-left text-blue-900 font-bold">NS:</label>
                                        <input v-model="form.birthday" id="phone" type="date" class="inputText border border-blue-700 w-full h-7 rounded-md" disabled  autocomplete="birth" />
                                    </div> 
                                    <div class="flex items-center w-[10%] space-x-2">
                                        <span class=" text-blue-900 font-bold w-[15%]">GT:</span>
                                        <div class="space-x-1 w-[90%]">
                                            <input type="radio" v-model="form.sex" value="1" disabled/><span class="text-xs">Nam</span>
                                            <input type="radio" v-model="form.sex" value="0" disabled/><span class="text-xs">Nữ</span>
                                        </div>
                                    </div>  
                                    <div class="flex items-center w-[20%]">
                                        <label class="w-[20%] text-blue-900 font-bold text-sm leading-4 text-right pr-1">Tên mẹ:</label>
                                        <input v-model="form.parent" id="parent" type="text" class="w-[80%] border border-blue-700 flex-1 h-7 rounded-md" disabled  autocomplete="parent" />
                                        <!-- <InputErrorApp :message="form.errors.parent" class="text-center" /> -->
                                    </div> 
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-[30%] leading-4 text-blue-900 font-bold text-sm flex justify-end">Mã Đd:</label>
                                        <input v-model="form.madinhdanh" id="madinhdanh" type="text" class="w-[70%] border border-blue-700 h-7 rounded-md" disabled  autocomplete="madinhdanh" />
                                    </div>  
                                    <div class="flex items-center w-[15%]">
                                        <label class="w-[25%] leading-4 text-blue-900 font-bold text-sm text-right pr-1">Mã TC:</label>
                                        <input v-model="form.matiemchung" id="matiemchung" type="text" class="w-[75%] border border-blue-700 h-7 rounded-md" disabled autocomplete="matiemchung" />
                                    </div>  
                                </div>
                               
                               
                            </div>
                            <div class=" mt-4 border border-2 border-hcdc1 py-4">
                            
                                <div class="flex">
                                    <div class="flex w-[80%]">
                                        <div class="w-[20%] item-center flex flex-col pl-4">
                                            <label class="font-bold">Ngày cân đo: </label>
                                            <input type="date" class="h-7" v-model="form.input_date"/>
                                        </div>
                                        <div class=" items-center flex flex-col pl-4 w-[20%]">    
                                            <span class="font-bold text-sm">Tháng tuổi:</span>
                                            <span class="font-bold ml-2">{{month_birth }} </span>
                                        </div>
                                        <div class="w-[20%] item-center flex flex-col pl-4 px-2">
                                            <label class="w-[100%] font-bold " >Cân nặng(kg): </label>
                                            <input type="text" class="w-[100%] h-7 p-0  text-center" v-model="form.weigth"/>
                                        </div>
                                      
                                        <div class="w-[20%] item-center flex flex-col px-2">
                                            <label class="w-[100%] font-bold" >Chiều cao(cm): </label>
                                            <input type="text" class="w-[100%] h-7 p-0 text-center" v-model="form.length"/>
                                        </div>
                                       
                                     </div>   
                                     <div class="text-right px-3 flex flex-col  w-[20%]">
                                            <label class="w-[100%] font-bold ">Chỉ số BMI:</label>
                                            <span class="w-[100%] font-bold text-hcdc2">{{ handleBMI }} (kg/m<sup>2</sup>)</span> 
                                    </div> 
                                        
                                </div>
                                   
                                <div class="flex justify-center mt-2 mb-2"> 
                                    <button type="submit"  class="button_save bg-blue-900 hover:bg-blue-700 px-4 py-1 rounded-lg cursor-pointer">
                                        <span class="text-white">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form> 
            </div>

        </ModalAdd>
    </div>
</template>
<script>
    import Table from '../../Components/Table/Table.vue'
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import { PencilIcon, XMarkIcon, PrinterIcon,ChevronDoubleDownIcon,CurrencyDollarIcon, ArrowRightIcon, CheckCircleIcon, PencilSquareIcon,PlusIcon,IdentificationIcon   } from '@heroicons/vue/24/solid'
    import moment from 'moment';
    import ModalUpdate from '../../Components/Modal.vue'
    import ModalAdd from '../../Components/Modal.vue'
    import QrcodeVue from 'qrcode.vue' 
    import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
    import Pagination from '../../Components/Pagination.vue'
    const defaultForm = {
        first_name: '',
        last_name: '',
        email: '',
    }  
    export default{
        props:{
            duplicates:'view',
            info_childs:'',
            provinces:'',
            districts:'',
            wards:'',
        },
        components:{
            Table,
            TableHeader,
            Tbody,
            TableRow,
            Tbody,
            Pagination,
            ModalUpdate,
            ModalAdd,
            PrinterIcon,
            ConfirmModalApp,
            XMarkIcon,PencilIcon,ChevronDoubleDownIcon,CurrencyDollarIcon,ArrowRightIcon,CheckCircleIcon,PencilSquareIcon,PlusIcon ,IdentificationIcon ,
            
        },
      
        data(){
            return{
                month_birth:'',
                updateInfo:false,
                nDay:1,
                vDay:1,
                openModalAdd:false,
                openModalUpdate:false,
                maxWidth:'7xl',
                confirmDelete:false,
                changePay:'',
                cashpay:'', 
                changeService:'',
                confirmModel:false,
                confirm_content:'', 
                openModal:false,
                id_child:"",
                form: this.$inertia.form({
                    name:'',
                    email:'',
                    phone:'',
                    address:'',
                    id_province:'',
                    id_district:'',
                    id_ward:'', 
                    khamDinhKy:[],
                    ngay_uong:[]     
                },
              {
                resetOnSuccess: false,
              }
            ),
            }
        },
        watch:{
            '$page.props.flash.success':function(value){
                if(value && this.edit){
                    this.closeModal();
                    //this.confirmModel=false;
                }
                if(value){
                    this.reset();
                    this.confirmModel=false
                }
            },
            'form.id_province':function(value){
            if(value){
              this.provinceHandle(value);
                }
                else{
                this.stateDistrict=false;
                }
            },
            'form.id_district':function(value){
                    this.districtEmit(value);
                },
            
        },
        computed:{
            headers() {
              return [
                  { name: "#",class:'text-red-800 px-1 border-r py-2' },
                  { name: "Tên trẻ", class:'border-r'},
                  { name: "NS",class:'border-r' },
                  { name: "GT", class:'border-r' },
                  { name: "ĐC",class:'text-red-10 border-r' },
                  { name: "Tên mẹ" , class:'border-r'},
                  { name: "Cân nặng lúc sinh ", class:'border-r' },
                  { name: "Khám ĐK", class:'border-r' },
                  { name: "Vitamins", class:'border-r' },
                  { name: "Ngày cân đo " ,class: "border-r"},
                  { name: "Tháng tuổi", class:'border-r' },
                  { name: " Cân nặng", class:'border-r' },
                  { name: "Chiều cao" ,class: "border-r"},
                  { name: "C.Cao/Tuổi", class:'border-r' },
                  { name: "C.Nặng/Tuổi", class:'border-r' },
                  { name: "C.Nặng/C.Cao", class:'border-r' },
                  { name: "Action", class: "text-right" },
              ];
          },
          classTD(){
                return 'border border-r text-gray-900'
          },
          classTable(){
              return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
          },
          classThead(){
              return ''
          },
          classRow(){
              return 'py-2 bg-white border-b border-r-2  hover:bg-gray-200'
          },
        },
        methods:{
            addDay() {
                
            this.nDay++;
            this.form.khamDinhKy.push(''); // Thêm ngày trống cho ngày mới
           
            },
            minusDay() {
                if (this.form.khamDinhKy.length > 0) {
                    this.nDay--;
                    this.form.khamDinhKy.pop(); // Xóa ngày cuối cùng
                    
                }
            },
            updateDates(ngay_kham) {
                this.form.khamDinhKy = ngay_kham; // Cập nhật mảng ngày khám
                this.nDay = ngay_kham.length; // Cập nhật số ngày
            },
          
            vAddDay(){
                this.vDay++
                this.form.ngay_uong.push('');
            },
          
            vMinusDay(){
                this.vDay--
                this.form.ngay_uong.pop()
            },
            infoUpdate(b){
                this.openModalUpdate = true;
                this.id_child=b.id
                this.form.name = b.name
                this.form.birthday = b.birthday
                this.form.sex = b.sex
                this.form.weightbirth = b.weightbirth
                this.form.parent = b.parent
                this.form.matiemchung = b.matiemchung
                this.form.madinhdanh = b.madinhdanh
                this.form.address = b.address
                this.form.id_district = b.id_district
                this.form.id_ward = b.id_ward
                this.form.id_province = b.id_province
                this.form.weigth = b.paraminput?b.paraminput.weigth:''
                this.form.length =b.paraminput? b.paraminput.length: ''
                this.form.input_date =b.paraminput? b.paraminput.input_date:''
                this.handleBMI =b.paraminput? b.paraminput.BMI:''
                this.month_birth=b.paraminput?b.paraminput.month:''
                var danh_sach_ngay_kham
                if (Array.isArray(b.khamdinhkis)) {
                    danh_sach_ngay_kham = b.khamdinhkis.map(item => item.ngay_kham);
                } else {
                    console.error('');
                }
                this.nDay = danh_sach_ngay_kham.length;
                this.form.khamDinhKy = danh_sach_ngay_kham

                var vitaMins
                if (Array.isArray(b.vitamins)) {
                    vitaMins = b.vitamins.map(item => item.ngay_uong);
                    console.log(vitaMins);
                } else {
                    console.error('');
                }
                this.vDay = vitaMins.length;
                this.form.ngay_uong = vitaMins
                
            },
            infoAdd(b){
                this.openModalAdd = true;
                this.id_child=b.id
                this.form.name = b.name,
                this.form.birthday = b.birthday
                this.form.sex = b.sex
                this.form.weightbirth = b.weightbirth
                this.form.parent = b.parent
            },
            closeInfoUpdate(){
                this.openModalUpdate = false;

                this.form.name = ""
                this.form.birthday = ""
                this.form.sex =""
                this.form.weightbirth = ""
                this.form.parent = ""
                this.form.matiemchung = ""
                this.form.madinhdanh =""
                this.form.address = ""
                this.form.id_district = ""
                this.form.id_ward = ""
                this.form.id_province = ""

            },
            closeInfoAdd(){
                this.openModalAdd = false;
                this.form.name = ""
                this.form.birthday = ""
                this.form.sex = ""
                this.form.weightbirth = ""
                this.form.parent =""
            },
            closeConfirmModal(){
                this.confirmModel=false
                this.changePay=false
                this.cashpay=false
                this.confirmDelete=false
                this.reset()
            },
            fillService(value){
                this.getServices=this.catelogies.filter(el => value.includes(el.id))
            },
            editCustommer(b){
                this.openModalCustommer();
                this.showService=true;
                this.edit=true;
                this.id_edit=b.id;
                this.form.editChangeService=this.changeService;
                this.form.usd_exchange=b.usd_exchange;
                const services = b.services.map((element1, index) => (element1.id_service ));
                const qty_sl = b.services.map((element1, index) => ({index:element1.id_service, sl:element1.sl}));
                this.form.name = b.custommer.name
                this.form.id_custommer = b.custommer.id
                this.form.phone = b.custommer.phone
                this.form.email = b.custommer.email
                this.form.mst = b.custommer.mst
                this.form.address = b.custommer.address
                this.form.id_province = b.custommer.id_province
                this.form.id_bill=b.id
                this.form.tokhai = b.tokhai
                this.form.email = b.tokhai
                this.form.services = services
                this.getServices
                const service = b.services.map((element1, index) => ({[element1.id_service]: element1.sl}));
                service.forEach((inputObject) => {
                    const objectKey = Object.keys(inputObject)[0]
                    const objectValue = Object.values(inputObject)[0]
                    this.form.qty[objectKey] = objectValue
                })
          //this.editEchange=b.usd_exchange
            },
            updateInfoEmit(){ 
                const formInfo = {
                                name: this.form.name,
                                birthday: this.form.birthday,
                                sex: this.form.sex,
                                weightbirth: this.form.weightbirth,
                                parent: this.form.parent,
                                matiemchung: this.form.matiemchung,
                                madinhdanh: this.form.madinhdanh,
                                address: this.form.address,
                                id_district: this.form.id_district,
                                id_ward: this.form.id_ward,
                                id_province: this.form.id_province,
                               
                            };
                const id = this.id_child
                this.updateInfo = "infobase"
                const data = {formInfo,id,updateInfo:this.updateInfo}
                this.$emit('updateInfoEmitEvent',data)
            },
            updateNgayKhamEmit(){
                const formInfo = {
                      
                        'ngay_kham': this.form.khamDinhKy,
                            };
                const id = this.id_child
                this.updateInfo = 'ngaykham'
                const data = {formInfo,id,updateInfo:this.updateInfo}
                this.$emit('updateInfoEmitEvent',data)
            },
            updateVitaminEmit(){
                const formInfo = {'ngay_uong': this.form.ngay_uong};
                const id = this.id_child
                this.updateInfo = 'vitamin'
                const data = {formInfo,id,updateInfo:this.updateInfo}
                this.$emit('updateInfoEmitEvent',data)
            },
            updateParamEmit(){
                const formInfo = {
                        'sex':this.form.sex,
                        'birthday':this.form.birthday,
                        'weigth':this.form.weigth,
                        'length':this.form.length, 
                        'input_date':this.form.input_date, 
                        'handleBMI':this.handleBMI,
                        'month': this.month_birth,
                            };
                const id = this.id_child
                this.updateInfo = 'param'
                const data = {formInfo,id,updateInfo:this.updateInfo}
                this.$emit('updateInfoEmitEvent',data)
            },
            updateCustommerEmit(){
                var data=[this.form,this.getServices];
                this.$emit('updateCustommerEvent',data)
            },
            addParamEmit(){
                const formInfo = {
                        'sex':this.form.sex,
                        'birthday':this.form.birthday,
                        'weigth':this.form.weigth,
                        'length':this.form.length, 
                        'input_date':this.form.input_date, 
                        'handleBMI':this.handleBMI,
                        'month': this.month_birth,
                            };
                const id = this.id_child
                this.updateInfo = 'addParam'
                const data = {formInfo,id,updateInfo:this.updateInfo}
                this.$emit('updateInfoEmitEvent',data)
            },
            reset(){
                this.id_child=''
                this.form.name = ''
                this.form.birthday = ''
                this.form.sex = ""
                this.form.weightbirth = ""
                this.form.parent = ""
                this.form.matiemchung = ""
                this.form.madinhdanh = ""
                this.form.address = ""
                this.form.id_district = ""
                this.form.id_ward = ""
                this.form.id_province = ""
                this.form.weigth = ""
                this.form.length =""
                this.form.input_date = ""
                this.handleBMI = ""
                this.month_birth=""       
                this.form.khamDinhKy=""
                this.form.ngay_uong=""
            },
            openModalCustommer(){
            this.openModal=true
            },
            closeModal(){
                this.openModal=false
                this.reset();
                this.showService=true;  
            },
            deleteBill(id){
                this.confirmDelete=true;
                this.confirmModel=true;
                this.id_pay = id;
                this.confirm_content='Xóa'
            },
            deleteEmit(id){
                this.$emit('deleteEvent',id)
                this.confirmDelete=false
                this.confirmModel=false
              
            },
            provinceHandle(code){   
                if(code){
                    const fillData = this.districts.filter(function (el) {
                    return el.id_province == code
                    });
                    this.getdistricts = fillData;
                }
            },
            districtEmit(value){
                this.$emit('districtEvent',value)
            },
            prinPdfEmit(b){
                this.$emit('prinPdfEvent',b)
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            },
            formatPrice_1(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            getTimeEdit(timeCreated){
                var date1 = new Date();
                var date2 = new Date(timeCreated);
                var diff = date1 - date2;
                var durationTime = diff/3600000 ;
                return durationTime;
            },
            formatPrice(value) {
                let val = (value/1).toFixed(0).replace(',', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },     
        }
    }
</script>