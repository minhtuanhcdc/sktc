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
                            <Tbody class="text-center w-24">{{ b.paraminput.input_date }}</Tbody>
                            <Tbody class="text-center w-16">{{ b.paraminput.weigth }}</Tbody>
                            <Tbody class="text-center w-16">{{ b.paraminput.length }}</Tbody>
                            <Tbody class="text-center w-16">{{ b.paraminput.BMI }}</Tbody>
                            <Tbody class="space-x-8 w-[40%]">
                                <span>- Chiều cao theo tuổi: <span class="font-bold"> {{ b.paraminput.lengthForAge }}; </span></span>
                                <span>- Cân nặng theo tuổi: <span class="font-bold"> {{ b.paraminput.weigthForAge }}; </span></span>
                                <span>- Cân nặng theo chiều cao: <span class="font-bold"> {{ b.paraminput.weigthForLength }}; </span></span>
                               
                            </Tbody>
                            <Tbody class="text-center w-24">
                                <PencilIcon class="w-6 text-blue-800 cursor-pointer text-center"  />
                            </Tbody>
                        </TableRow> 
                    </template>
                </Table>
        </div>
        <div class="flex mt-2  items-center py-0 h-8">
             <Pagination :links="info_childs.links"/>
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
                    name:'',
                    email:'',
                    phone:'',
                    address:'',
                    id_province:'',
                    id_district:'',
                    id_ward:'',      
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
                  { name: "#",class:'text-red-800 px-1' },
                  { name: "Tên trẻ", class:''},
                  { name: "NS",class:'' },
                  { name: "GT" },
                  { name: "ĐC",class:'text-red-10' },
                  { name: "Tên mẹ" , class:''},
                  { name: "Cân nặng lúc sinh " },
                  { name: "Ngày cân đo " ,class: ""},
                  { name: " Cân nặng" },
                  { name: "Chiều cao" ,class: ""},
                  { name: "BMI" },
                  { name: "Kênh" },
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