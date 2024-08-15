<template >
    <div class="w-full mt-2 p-2 border-1">
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <Table :classTable="classTable">
                <template #header>
                    <TableHeader :headers="headers" :classThead="classThead"/>
                </template>    
                <template #tbody>
                <template v-for="(b,i) in bills.data" :key="i">
                    <TableRow :classRow="classRow" >
                    <Tbody>{{ i+1 }}</Tbody>
                    <Tbody></Tbody>
                    <Tbody>{{ b.custommer.name }}</Tbody>
                    <Tbody>{{ formattedDate(b.created_at) }}</Tbody>
                    <Tbody class="text-left">123</Tbody>
                    <Tbody class="text-left">
                        {{ b.custommer.address }},
                            <span v-if="b.custommer.ward">{{ b.custommer.ward.name }}, </span>
                            <span v-if="b.custommer.district">{{ b.custommer.district.name }}, </span>
                            <span v-if="b.custommer.province">{{ b.custommer.province.name }}, </span>   
                    </Tbody>
                    <Tbody class="text-left w-44"></Tbody>
                    
                    <Tbody class="center w-10"></Tbody>
                    <Tbody class="center w-10"></Tbody>
                    <Tbody class="text-center"></Tbody>
                    <Tbody></Tbody>
                    <Tbody></Tbody>
                    <Tbody class="border border-r border-gray-800 border-b"></Tbody>
                    <Tbody class="border border-r border-gray-800 border-b w-24"></Tbody>
                    <Tbody class="text-center"></Tbody>
                    <Tbody> 
                        <div class="flex justify-center items-center align-middle h-full space-x-3"> 
                            <span class="tooltip_edit111 z-50 flex items-center cursor-pointer"  @click="editCustommer(b)" title="Cập nhật">
                                <PencilIcon class="w-6 h-6 text-hcdc2"/>
                            </span> 
                            <span title="Xóa" class="cursor-pointer">
                                <XMarkIcon v-if="!b.pay_status" class="w-4 h-4 text-red-600" @click="deleteBill(b.id)" /> 
                                <XMarkIcon v-else class="w-4 h-4 text-gray-600"/> 
                            </span>
                        </div>
                    </Tbody>
                </TableRow> 
                </template> 
                </template>
            </Table> 
        </div>
        <div class="flex mt-2  items-center py-0 h-8">
            <Pagination :links="bills.links"/>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between mt-2 px-8 py-2">
                <div></div>
                <div>
                    <button class="rounded-lg px-6 py-2 bg-blue-900 h-6 flex items-center cursor-pointer text-white" @click="closeModal">
                        <span><XMarkIcon class="w-6 h-6"/></span>
                        <span>Close</span>
                    </button>
                </div>
            </div>
            <div class="px-6 -pt-2 pb-4">
                <form @submit.prevent="updateCustommerEmit()">
                    <div class="w-[100%]">
                    <fieldset class="border border-solid border-blue-900 px-2 py-1 bg-green-200 z-40 w-[100%]">
                        <legend class="text-lg text-blue-800 font-bold -mt-2">Cập nhật thông tin</legend>
                        <div class="w-[99%]"> 
                            <div class="flex space-x-2">
                                <div class=" w-1/4 items-center hidden">
                                    <div class="flex">
                                        <label for="id_bill" class="classLabel">id:</label>
                                        <input v-model="form.id_bill" id="id" type="text" class="inputText border border-blue-700 flex-1 h-7 rounded-md w-full"  autocomplete="id" />
                                    </div>
                                   
                                </div>
                                <div class=" w-1/4 items-center">
                                    <div class="flex">
                                        <label for="name" class="classLabel">KH:</label>
                                        <input v-model="form.name" id="name" type="text" class="inputText border border-blue-700 flex-1 h-7 rounded-md w-full"  autocomplete="name" />
                                    </div>
                                    <InputErrorApp :message="form.errors.name" class="text-center" /> 
                                </div>
                                <div class="flex items-center w-44">
                                    <label class="w-8 text-left">ĐT:</label>
                                    <input v-model="form.phone" id="phone" type="text" class="inputText border border-blue-700 w-full h-7 rounded-md"  autocomplete="phone" />
                                </div> 
                                <div class="flex items-center w-64">
                                    <label class=" pr-1">Email:</label>
                                    <input v-model="form.email" id="phone" type="text" class="inputText border border-blue-700 h-7 rounded-md w-full"  autocomplete="email" />
                                </div> 
                                <div class="flex items-center w-56">
                                    <label class="text-center pr-1 leading-4">Tờ khai:</label>
                                    <input v-model="form.tokhai" id="tokhai" type="text" class="inputText border border-blue-700 w-full h-7 rounded-md"  autocomplete="tokhai" />
                                </div> 
                                <div class="flex items-center w-56">
                                    <label class=" pr-1">MST:</label>
                                    <input v-model="form.mst" id="mst" type="text" class="inputText border border-blue-700 h-7 rounded-md w-full"  autocomplete="mst" />
                                </div> 
                            </div>
                            <div class="flex mt-2 items-center space-x-3">
                                <div class="w-1/4 flex items-center">
                                    <label class=" pr-1">Địa chỉ:</label>
                                    <input v-model="form.address" id="address" type="text" class="inputText border border-blue-700 flex-1 h-7 rounded-md"  autocomplete="address" />
                                </div> 
                                <div class="w-1/4 flex items-center">
                                    <label>Tỉnh/thành</label>
                                    <select   v-model="form.id_province" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                        <option value="">-</option>
                                        <option v-for="(pce, i) in provinces" :key="i" :value="pce.code">
                                            <span>  {{pce.name}}</span>
                                        </option>
                                    </select>
                                </div>
                                <div class="w-1/4 flex items-center">
                                    <label>Quận/huyện</label>
                                    <select v-model="form.id_district" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                        <option value=""></option>
                                        <option v-for="(d,i) in getdistricts" :key="i" :value="d.code">{{ d.name }}</option>
                                    </select>
                                </div>
                                <div class="w-1/4 flex items-center">
                                    <label>Phường/xã</label>
                                    <select  v-model="form.id_ward" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                                        <option value="">-</option>
                                        <option v-for="(w,i) in wards" :key="i" :value="w.code">{{w.name}}</option>
                                    </select>
                                </div>   
                            </div>
                            <div >
                                <span @click="showService = !showService" v-if="!showService" class="cursor-pointer text-bold flex"><span>Chọn dịch vụ</span> <ChevronDoubleDownIcon class="w-6 h-6 text-blue-800"/></span>
                                <span @click="showService = !showService" v-else class="cursor-pointer"> <span><XMarkIcon class="w-6 h-6"/></span></span>
                                <fieldset v-if="showService" class="border border-solid border-blue-900 px-2 py-1 bg-gray-100 h-auto overflow-y-auto">
                                    <legend class="text-sm text-red-800 font-bold">Chọn dịch vụ</legend>
                                    <div class="grid grid-cols-2"> 
                                        <template v-for="(catelogy,index) in catelogies " :key="index" class="flex">
                                                <div class=" flex justify-between space-x-2 items-center pr-4 mb-2">
                                                    <span class="flex w-2/3 items-center z-50">
                                                        <input v-model="form.services" :value="catelogy.id" type="checkbox"/>
                                                        <span class="line-clamp-1 hover:line-clamp-3"> 
                                                        {{ catelogy.name }}({{ catelogy.id }})
                                                        </span>
                                                    </span>
                                                    <span class="text-sm text-right w-14">({{ catelogy.don_gia }})</span>
                                                    <span class="pl-4 flex items-center">
                                                        <label class="pr-1">SL:</label>
                                                        <input type="text" class="h-6 w-14" :id="catelogy.id" :name="catelogy.id" v-model="form.qty[catelogy.id]">
                                                    </span>
                                                </div>
                                            
                                        </template>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-2 px-10 text-sm text-blue-900">
                                <div>
                                    <table>
                                        <thead>
                                            <th class="w-10">Stt</th>
                                            <th class="w-96">DV</th>
                                            <th class="w-24">ĐG</th>
                                            <th class="w-24">SL</th>
                                            <th class="w-24">Thành tiền</th>
                                        </thead>
                                        <tbody>
                                            <template v-if=" getServices">
                                                <tr v-for="(service,i) in getServices" :key="i">
                                                    <td class="text-center">{{ i +1 }}</td>
                                                    <td class="px-2">{{ service.name }}</td>
                                                    <td class="text-center">{{ service.don_gia }}</td>
                                                    <td class="text-center">{{ service.sl }}</td>
                                                    <td class="text-right">{{ service.thanhtien }} (USD)</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex justify-between mt-1 border-b-2 border-red-600">
                                    <div class="w-1/3">
                                    
                                    <div class="w-1/3">
                                        <span class="font-bold">Tổng:</span>
                                    
                                        <span >{{ formatPrice(sumUsd) }}*{{editEchange}}(VNĐ) = <span class="font-bold pl-2">{{ formatPrice_1(tongtien) }}</span></span> 
                                    </div>
                                </div>
                                </div> 
                            </div> 
                            <div class="flex justify-end mt-2 w-1/2"> 
                                <button type="submit"  class="button_save bg-blue-700 px-4 py-1 rounded-lg cursor-pointer">
                                    <span class="text-white">Update</span>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                    </div>
                </form>  
            </div>
            
        </ModalApp>
        <ConfirmModalApp :show="confirmModel">
            <template #title class="w-full flex justify-end">
                <span @click="closeConfirmModal" class="px-4 py-1 cursor-pointer bg-yellow-900 text-white rounded-sm">Close</span>
            </template>
            <template #content>
                <div class="flex space-x-2 w-full text-md">
                    <span class="font-bold underline text-red-600"> Số BN: </span>
                    <span class="text-blue-900 font-bold">{{ id_pay }} </span>
                    <span>{{ confirm_content }} ?</span>
                </div>
            </template>
            <template #footer class="text-center">
                <button v-if="confirmDelete" class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="deleteEmit(id_pay)">Xác Nhận Xóa</button>
                <button v-if="changePay" class="bg-blue-600 text-white px-3 py-1 rounded-lg" @click="confirmTransferEmit(id_pay)">Xác nhận chuyển khoản</button>
                <button v-if="cashpay" class="bg-blue-600 text-white px-3 py-1 rounded-lg" @click="confirmCashEmit(id_pay)">Xác Nhận Đã thanh toán tiền mặt</button>
            </template>
        </ConfirmModalApp> 
    </div>
</template>
<script>
    import Table from '../../Components/Table/Table.vue'
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import { PencilIcon, XMarkIcon, PrinterIcon,ChevronDoubleDownIcon,CurrencyDollarIcon, ArrowRightIcon, CheckCircleIcon, PencilSquareIcon} from '@heroicons/vue/24/solid'
    import moment from 'moment';
    import ModalApp from '../../Components/Modal.vue'
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
           
            bills:'',
            provinces:'',
            districts:'',
            wards:'',
            catelogies:''
        },
        components:{
            Table,
            TableHeader,
            Tbody,
            TableRow,
            Tbody,
            Pagination,
            ModalApp,
            PrinterIcon,
            ConfirmModalApp,
            XMarkIcon,PencilIcon,ChevronDoubleDownIcon,CurrencyDollarIcon,ArrowRightIcon,CheckCircleIcon,PencilSquareIcon,
            
        },

      
        data(){
            return{
              
                getServices:'123',
                maxWidth:'7xl',
                confirmDelete:false,
                changePay:'',
                cashpay:'', 
                changeService:'',
                confirmModel:false,
                confirm_content:'', 
                openModal:false,
                form: this.$inertia.form({
                    id_custommer:'',
                    name:'',
                    services:[],
                    qty:[],
                    email:'',
                    phone:'',
                    mst:'',
                    tokhai:'',
                    address:'',
                    id_province:'',
                    id_district:'',
                    id_ward:'',
                    usd_exchange:'',
                    editChangeService:'',  
                    sohieu:'' , 
                    id_bill:'' , 
                    
                
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
             
            "form.services":function(value){
                this.getFist=this.catelogies.filter(el => value.includes(el.id))
                this.getServices = this.getFist.map((element1, index) => ({id_service: element1.id, name: element1.name,don_gia:element1.don_gia, sl:this.form.qty[element1.id],thanhtien:+parseFloat(this.form.qty[element1.id]*(+element1.don_gia)).toFixed(2) }))
                this.sumUsd = this.getServices.reduce(
                    (accumulator, currentValue) => accumulator + currentValue.thanhtien,0);
                    
                const sumUs = parseFloat(this.sumUsd).toFixed(2);
                this.tongtien = +parseFloat(sumUs).toFixed(2);
            },
        },
        computed:{
            headers() {
              return [
                  { name: "#",class:'text-red-800 px-1' },
                  { name: "ID",class:' text-red-10' },
                  { name: "Tên trẻ", class:''},
                  { name: "NS",class:'' },
                  { name: "GT" },
                  { name: "ĐC",class:'text-red-10' },
                  { name: "Tên mẹ" , class:''},
                  { name: "Cân nặng lúc sinh " },
                  { name: "Ngày cân đo " ,class: "text-right"},
                  { name: " Cân nặng" },
                  { name: "Chiều cao" ,class: "text-right"},
                  { name: "Cân-tuổi" },
                  { name: "Chiều cao-tuôi",class:'border-r' },
                  { name: "Cân nặng - chiều cao" },
                  { name: "Kênh" },
                  { name: "Action", class: "text-right" },
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
            confirmTransfer(b){
                this.confirmModel=true
                this.changePay=true
                this.id_pay = b
                this.confirm_content='Thanh toán chuyển khoản'
                },
            confirmCash(b){
                this.confirmModel=true
                this.cashpay=true
                this.id_pay = b
                this.confirm_content='Thanh toán tiền mặt'
            },
            confirmCashEmit(id){
                //alert(id);
                this.$emit('confirmCashEvent',id)
                this.cashpay=false
                this.confirmModel=false
            },
            confirmTransferEmit(id){
                //alert(id);
                this.$emit('confirmTransferEvent',id)
                this.changePay=false
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