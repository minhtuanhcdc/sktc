<template >
    <div class="h-[85%]">
        <div class=" h-full shadow-md sm:rounded-lg mt-0 overflow-y-auto">
        <Table :classTable="classTable" :classThead="classThead" class="w-full ">
              <template #header>
                  <TableHeader :headers="headers" class="bg-blue-600 text-white sticky top-0 z-10 text-xs text-center"/>
              </template>    
               <template #tbody>
                <template v-for="(c,i) in info_childs.data" >
                    <TableRow :classRow="classRow" class="">
                        <Tbody :class="classtBody" class="text-center">{{ i+1 }}</Tbody>
                        <Tbody :class="classtBody">{{ c.madinhdanh }}</Tbody>
                        <Tbody :class="classtBody">{{ c.name }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">{{ formatDate(c.birthday)}}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-if="c.sex == 1">Nam</span>
                            <span v-else>Nữ</span>
                        </Tbody>
                        <Tbody :class="classtBody" class="w-[20%]">{{ c.address }}</Tbody>
                        <Tbody :class="classtBody" >{{ c.parent }}</Tbody>
                        <Tbody :class="classtBody" class="text-center w-14">{{c.weightbirth }}</Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <div v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column" >
                                    <p class="w-full bg-red-100" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{item.input_date}}</p>
                                </span>
                            </div>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center px-0">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0 h-full">
                                 <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column h-full items-center" >
                                    <p class="w-full h-full" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{ item.weigth }}</p>
                                </span>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="text-center">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                 <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column" >
                                    <p class="w-full" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{ item.length }}</p>
                                </span>
                            </span>
                            
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                 <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column" >
                                    <p class="w-full" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{ item.lengthForAge }}</p>
                                </span>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                 <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column" >
                                    <p class="w-full" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{ item.weigthForAge }}</p>
                                </span>
                            </span>
                        </Tbody>
                        <Tbody :class="classtBody" class="bg-gray-300">
                            <span v-for="(d,index) in c.paraminput" :key="index" class="text-center px-0">
                                 <span v-for="(item, i) in JSON.parse(d.data)" :key="i" class="flex flex-column" >
                                    <p class="w-full" :class="i != JSON.parse(d.data).length - 1?'border-gray-500 border-b':''">{{ item.weigthForLength }}</p>
                                </span>
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
                        <Tbody>
                            <div class="flex items-center justify-between px-3">
                                        <span class="" title="Cập nhật TT">
                                            <IdentificationIcon  class="w-6 text-blue-800 cursor-pointer text-center h-full" @click="infoUpdate(c)"/>
                                        </span>
                                        <span class="" title="Thêm TT">
                                            <PlusIcon class="w-6 text-blue-800 cursor-pointer text-center h-full" title="Thêm thông tin" @click="infoAdd(c)"/>
                                        </span>
                                </div>
                        </Tbody>
                        
                    </TableRow> 
                </template>
              </template> 
              
        </Table>
        <div class="flex mt-2 bg-blue-300  items-center py-0 h-8 sticky bottom-1">
            <Pagination :links="info_childs.links" @click="handlePagination"/>
        </div> 
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
                                    <div class="w-[60%]"> 
                                        <div class="flex mt-2 items-center"  v-for="(ngV,i) in pDay" :key="i" >
                                           
                                           <div class=" item-center flex flex-col pl-4 w-[40%]">
                                                <label class="font-bold">Ngày cân đo: </label>
                                                <input type="date" class="h-7" v-model="form.input_date[i]"/>
                                            </div>
                                            <div class="item-center flex flex-col pl-4 px-2 w-[30%]">
                                                <label class=" font-bold " >Cân nặng(kg): </label>
                                                <input type="text" class="h-7 p-0  text-center" v-model="form.weigth[i]"/>
                                            </div>
                                            <div class=" item-center flex flex-col px-2 w-[30%]">
                                                <label class=" font-bold" >Chiều cao(cm): </label>
                                                <input type="text" class=" h-7 p-0 text-center" v-model="form.length[i]"/>
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
                                    <div class="flex mt-2 items-center"  v-for="(ngV,i) in pDay" :key="i" >
                                           
                                           <div class=" item-center flex flex-col pl-4 w-[40%]">
                                               <label class="font-bold">Ngày cân đo: </label>
                                               <input type="date" class="h-7" v-model="form.input_date[i]"/>
                                           </div>
                                           <div class="item-center flex flex-col pl-4 px-2 w-[30%]">
                                               <label class=" font-bold " >Cân nặng(kg): </label>
                                               <input type="text" class="h-7 p-0  text-center" v-model="form.weigth[i]"/>
                                           </div>
                                           <div class=" item-center flex flex-col px-2 w-[30%]">
                                               <label class=" font-bold" >Chiều cao(cm): </label>
                                               <input type="text" class=" h-7 p-0 text-center" v-model="form.length[i]"/>
                                           </div>
                                           <div>
                                            <span class="cursor-pointer text-md font-bold " @click="addParamDay">+</span>
                                           </div>
                                           
                                       </div>  
                                     <!-- <div class="text-right px-3 flex flex-col  w-[20%]">
                                            <label class="w-[100%] font-bold ">Chỉ số BMI:</label>
                                            <span class="w-[100%] font-bold text-hcdc2">{{ handleBMI }} (kg/m<sup>2</sup>)</span> 
                                    </div>  -->
                                        
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
                pDay:1,
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
                    input_date:[],
                    length:[],
                    weigth:[],
                    khamDinhKy:[],
                    ngay_uong:[],

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
                  { name: "Mã ĐD", class:'border-r'},
                  { name: "Tên trẻ", class:'border-r'},
                  { name: "NS",class:'border-r' },
                  { name: "GT", class:'border-r' },
                  { name: "ĐC",class:'text-red-10 border-r' },
                  { name: "Tên mẹ" , class:'border-r'},
                  { name: "Cân nặng lúc sinh ", class:'border-r' },
                  { name: "Ngày cân đo " ,class: "border-r"},
                  { name: "Cân nặng", class:'border-r' },
                  { name: "Chiều cao" ,class: "border-r"},
                  { name: "C.Cao/Tuổi", class:'border-r' },
                  { name: "C.Nặng/Tuổi", class:'border-r' },
                  { name: "C.Nặng/C.Cao", class:'border-r' },
                  { name: "Khám ĐK", class:'border-r' },
                  { name: "Vitamins", class:'border-r' },
                  { name: "Action", class: "text-right" },
              ];
          },
          classTable(){
                return ' text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 text-blue-800'
            },
            classRow(){
                return 'text-xs border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
            },
            classtBody(){
              return ' border-r'
            },
          classTD(){
                return 'border border-r text-gray-900'
          },
         
        },
        methods:{
            addParamDay() {
            this.pDay++;
            this.form.input_date.push(''); // Thêm ngày trống cho ngày mới
           
            },
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
                var ngayCanDo=[],canNang, chieuCao,danh_sach_ngay_kham
                if (Array.isArray(b.paraminput)) {
                    ngayCanDo =b.paraminput.map(item=>JSON.parse(item.data).map(item2=>item2.input_date))
                    canNang =b.paraminput.map(item=>JSON.parse(item.data).map(item2=>item2.weigth))
                    chieuCao =b.paraminput.map(item=>JSON.parse(item.data).map(item2=>item2.length))
                        } else {
                            console.error('');
                        }
                       
                this.pDay = ngayCanDo[0].length;
                this.form.input_date= ngayCanDo[0]
               
                this.form.weigth = canNang[0]
                this.form.length = chieuCao[0]

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
            formatDate(value) {
                if (value) {
                    return moment(String(value)).format("DD/MM/YYYY");
                }
            },   
        },
        mounted() {
        
        },
    }
   
</script>