<template >
    <div class="w-full mt-2 p-2 border-1">
        <div class="relative h-[75%] overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <Table :classTable="classTable">
                <template #header>
                    <TableHeader :headers="headers" :classThead="classThead"/>
                </template>    
                <template #tbody>
                <template >
                    <TableRow :classRow="classRow"  v-for="(b,i) in bills" :key="i">
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
            <!-- <Pagination :links="bills.links"/> -->
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
           
            
        </ModalApp>
       
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