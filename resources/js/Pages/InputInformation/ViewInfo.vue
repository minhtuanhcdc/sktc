<template >
    <div class="w-full mt-2 p-2 border-1">
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <Table :classTable="classTable" :classThead="classThead" class="w-[70%]">
                    <template #header>
                        <TableHeader :headers="headers" class="bg-blue-500 text-center text-white"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(b,i) in info_childs.data" :key="i">
                            <Tbody class="text-center w-6">1</Tbody>
                            <Tbody class="text-center w-48">{{ b.name }}</Tbody>
                            <Tbody class="text-center w-24">{{ b.birthday }}</Tbody>
                            <Tbody class="text-center w-16">
                               <span v-if="b.sex ==1">Nam</span>
                               <span v-else>Nữ</span>
                            </Tbody>
                            <Tbody class="text-center w-48">{{ b.address }}</Tbody>
                            <Tbody class="text-center w-32">{{ b.parent }}</Tbody>
                            <Tbody class="text-center w-24">{{ b.weightbirth }}</Tbody>
                            <Tbody class="text-center w-24"><span v-if="b.paraminput"> {{ b.paraminput.input_date }}</span></Tbody>
                            <Tbody class="text-center w-16"><span v-if="b.paraminput">{{ b.paraminput.month }}</span></Tbody>
                            <Tbody class="text-center w-16"><span v-if="b.paraminput">{{ b.paraminput.weigth }}</span></Tbody>
                            <Tbody class="text-center w-16"><span v-if="b.paraminput">{{ b.paraminput.length }}</span></Tbody>
                            <Tbody class="w-[45%]">
                                <div class="flex text-left">
                                    <span class="w-1/3 border-r">- Chiều cao theo tuổi: <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.lengthForAge }}; </span></span>
                                    <span class="w-1/3 border-r">- Cân nặng theo tuổi: <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.weigthForAge }}; </span></span>
                                    <span class="w-1/3">- Cân nặng theo chiều cao: <span class="font-bold" v-if="b.paraminput"> {{ b.paraminput.weigthForLength }}; </span></span>
                                </div>  
                            </Tbody>
                            <Tbody class="text-center w-16">
                                <PencilIcon class="w-6 text-blue-800 cursor-pointer text-center" @click="infoUpdate()" />
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
                <form @submit.prevent="saveParamEmit()">
                    <fieldset class="px-2 py-0 border-2 border-hcdc2 z-40 mb-2 bg-slate-200">
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
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center w-[25%]">
                                    <label class="w-[20%] text-left text-blue-900 font-bold">Tên trẻ:</label>
                                    <input v-model="form.name" id="name" type="text" class="w-[80%] border border-blue-700 h-7 rounded-md"  autocomplete="name" />
                                    <InputErrorApp :message="form.errors.name" class="text-center" /> 
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
                                    <label class="w-[20%] text-blue-900 font-bold text-sm">Tên mẹ:</label>
                                    <input v-model="form.parent" id="parent" type="text" class="w-[80%] border border-blue-700 flex-1 h-7 rounded-md"  autocomplete="parent" />
                                    <InputErrorApp :message="form.errors.parent" class="text-center" />
                                </div> 
                                <div class="flex items-center w-[15%]">
                                    <label class="w-[30%] leading-4 text-blue-900 font-bold text-sm flex justify-end">Mã Đd:</label>
                                    <input v-model="form.madinhdanh" id="madinhdanh" type="text" class="w-[70%] border border-blue-700 h-7 rounded-md"  autocomplete="madinhdanh" />
                                </div>  
                                <div class="flex items-center w-[15%]">
                                    <label class="w-[25%] leading-4 text-blue-900 font-bold text-sm">Mã TC:</label>
                                    <input v-model="form.matiemchung" id="matiemchung" type="text" class="w-[75%] border border-blue-700 h-7 rounded-md" autocomplete="matiemchung" />
                                </div>  
                            </div>
                            <div class="flex mt-2 items-center space-x-3">
                                <div class="w-1/4 flex items-center">
                                    <label class=" pr-1 text-blue-900 font-bold">Địa chỉ:</label>
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
                                    <label class="text-blue-900 font-bold">Quận/huyện</label>
                                    <select v-model="form.id_district" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                        <option value=""></option>
                                        <option v-for="(d,i) in getdistricts" :key="i" :value="d.code">{{ d.name }}</option>
                                    </select>
                                </div>
                                <div class="w-1/4 flex items-center">
                                    <label class="text-blue-900 font-bold">Phường/xã</label>
                                    <select  v-model="form.id_ward" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                        <option value="">-</option>
                                        <option v-for="(w,i) in wards" :key="i" :value="w.code">{{w.name}}</option>
                                    </select>
                                </div>   
                            </div>
                            <div class="flex mt-4 space-x-4">
                                <div class="w-1/2 flex space-x-4">
                                    <div class="flex items-center">
                                        <label class="font-bold text-blue-900 text-sm">Cân nặng lúc sinh(kg):</label>
                                        <input class="h-7 rounded-sm border w-14" v-model="form.weightbirth"/>
                                    </div>
                                    <div class="flex space-x-4 items-center leading-4">    
                                        <span class="text-hcdc2 font-bold text-sm">Tháng tuổi:</span>
                                        <span class="font-bold ml-2">{{month_birth }} (tháng)</span>
                                    </div>
                                </div>
                                <div class="w-1/2 border border-hcdc1 p-2 flex">
                                        <div class="w-[50%] px-2">
                                            <span class="w-[100%] h-7 font-bold">Khám ĐK: </span>
                                            <div  v-for="(ng,i) in nDay" :key="i" class="">
                                                <input type="date" class="w-[100%] h-7 rounded-sm " v-model="form.khamDinhKy[i]"/> 
                                            </div>
                                            <div class="flex justify-between w-[100%] px-2">
                                                <span class=" text-md font-bold w-[50%]" @click ="minusDay">
                                                   <span class="cursor-pointer p-2"> - </span> 
                                                </span>
                                                <span class="cursor-pointer text-md font-bold w-[50%] text-right" @click="addDay">+</span>
                                            </div>
                                            
                                        </div>
                                        <div class="w-[50%] px-2">
                                            <span class="w-[100%] h-7 font-bold">Vitamin: </span>
                                            <div  v-for="(ngV,i) in vDay" :key="i" class="">
                                                <input type="date" class="w-[100%] h-7 rounded-sm " v-model="form.ngay_uong[i]"/> 
                                            </div>
                                            <div class="flex justify-between w-[100%] px-2">
                                                <span class=" text-lg font-bold w-[50%]" @click ="vMinusDay">
                                                   <span class="cursor-pointer p-2 "> - </span> 
                                                </span>
                                                <span class="cursor-pointer text-lg font-bold w-[50%] text-right" @click="vAddDay">+</span>
                                            </div>
                                            
                                        </div>


                                        <!-- <div v-if="month_birth>=6 &&  month_birth<=36">
                                            <span class="h-7 font-bold">Vitamin: </span>
                                            <input class="h-7 rounded-sm" type="date" v-model="form.ngay_uong"/>
                                            
                                        </div> -->
                                </div>
                            </div>
                            <div class=" mt-4">
                                <fieldset class="border border-solid border-blue-900 px-2 bg-gray-100 h-auto overflow-y-auto">
                                    
                                    <div class="flex space-x-2 px-0 items-center">
                                        <span class="w-[10%] font-bold  -ml-2 bg-slate-200 text-hcdc2 h-10">Chỉ số cân đo</span>
                                        <div class="w-[20%]">
                                            <label class="font-bold">Ngày cân đo: </label>
                                            <input type="date" class="h-7" v-model="form.input_date"/>
                                        </div>
                                        <div class="w-[10%] flex items-center ">
                                            <label class="w-[60%] font-bold leading-3" >Cân nặng(kg): </label>
                                            <input type="text" class="w-[40%] h-7 p-0  text-center" v-model="form.weigth"/>
                                        </div>
                                        <div class="w-[10%] flex items-center ">
                                            <label class="w-[60%] font-bold leading-3 text-right" >Chiều cao(cm): </label>
                                            <input type="text" class="w-[40%] h-7 p-0 text-center" v-model="form.length"/>
                                        </div>
                                        <div>
                                            <label class="w-[60%] font-bold leading-3">Chỉ số BMI:</label>
                                            <span class="font-bold text-hcdc2">{{ handleBMI }} (kg/m<sup>2</sup>)</span>
                                            
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        
                            <InputErrorApp :message="form.errors.qty" :classError="classError" /> 
                            <div class="flex justify-end mt-2 w-1/2 mb-2"> 
                                <button type="submit"  class="button_save bg-blue-900 hover:bg-blue-700 px-4 py-1 rounded-lg cursor-pointer">
                                    <span class="text-white">Save</span>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form> 
            </div>

        </ModalUpdate>
       
    </div>
</template>
<script>
    import Table from '../../Components/Table/Table.vue'
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import { PencilIcon, XMarkIcon, PrinterIcon,ChevronDoubleDownIcon,CurrencyDollarIcon, ArrowRightIcon, CheckCircleIcon, PencilSquareIcon} from '@heroicons/vue/24/solid'
    import moment from 'moment';
    import ModalUpdate from '../../Components/Modal.vue'
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
            PrinterIcon,
            ConfirmModalApp,
            XMarkIcon,PencilIcon,ChevronDoubleDownIcon,CurrencyDollarIcon,ArrowRightIcon,CheckCircleIcon,PencilSquareIcon,
            
        },
      
        data(){
            return{
                nDay:1,
                vDay:1,
                openModalUpdate:false,
                maxWidth:'7xl',
                confirmDelete:false,
                changePay:'',
                cashpay:'', 
                changeService:'',
                confirmModel:false,
                confirm_content:'', 
                openModal:false,
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
                  { name: "#",class:'text-red-800 px-1 border-r' },
                  { name: "Tên trẻ", class:'border-r'},
                  { name: "NS",class:'border-r' },
                  { name: "GT" },
                  { name: "ĐC",class:'text-red-10 border-r' },
                  { name: "Tên mẹ" , class:'border-r'},
                  { name: "Cân nặng lúc sinh ", class:'border-r' },
                  { name: "Ngày cân đo " ,class: "border-r"},
                  { name: "Tháng tuổi", class:'border-r' },
                  { name: " Cân nặng", class:'border-r' },
                  { name: "Chiều cao" ,class: "border-r"},
                  { name: "Phân loại dinh dưỡng", class:'border-r' },
                  { name: "Action", class: "text-right pr-2" },
              ];
          },
          classTable(){
              return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
          },
          classThead(){
              return 'text-center border border-r-2 border-red-500'
          },
          classRow(){
              return 'py-2 text-center bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
          },
        },
        methods:{
            addDay(){
                this.nDay++
            },
            vAddDay(){
                this.vDay++
            },
            minusDay(){
                this.nDay--
            },
            vMinusDay(){
                this.vDay--
            },
            infoUpdate(){
                this.openModalUpdate = true;
            },
            closeInfoUpdate(){
                this.openModalUpdate = false;
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
            updateCustommerEmit(){
                var data=[this.form,this.getServices];
                this.$emit('updateCustommerEvent',data)
            },
            reset(){
                this.form.services=[]
                this.form.qty=[]
                this.confirm_content=''
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
                //alert(id);
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