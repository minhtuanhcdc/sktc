
   import AdminLayout from '../../Layouts/AdminLayout.vue';
   import { Head, Link, useForm } from '@inertiajs/vue3';
   import Table from '../../Components/Table/Table.vue';
   import TableHeader from '../../Components/Table/TableHeaders.vue'
   import TableRow from '../../Components/Table/TableRow.vue';
   import Tbody from '../../Components/Table/TableBody.vue';
   import ActionMessageApp from '../../Components/ActionMessage.vue';
   import InputErrorApp from '../../Components/InputError.vue'
   import QrcodeVue from 'qrcode.vue'
  import { PencilIcon, XMarkIcon, PrinterIcon,ChevronDoubleDownIcon,CurrencyDollarIcon, CheckCircleIcon  } from '@heroicons/vue/24/solid'
    //import { PrinterIcon  } from "@vue-hero-icons/outline"
  import { router } from '@inertiajs/vue3'
  import Pagination from '../../Components/Pagination.vue'
  import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
  import PerPage from '../../Components/PerPage.vue'
  //pdfMake.vfs = pdfFonts.pdfMake.vfs;
  import moment from 'moment'
 // import jsPDF from 'jspdf' 
//import domtoimage from "dom-to-image";
    export default{
      name:'Home',
      props:{
            bills:'',
            posts:'',
            services:'',
            sum_pay:"",
            filters:'',
            text_price:'',
            unpaid:'',
            total_pay:'',
            unconfimred:'',
            hcdcconfimred:'', 
           chuyenKhoan:'', 
           tienMat:'', 
            sumTienMat:'', 
            sumChuyenKhoan:'',
            cosos:'' ,
            is_admin:'' ,
        },
      components:{
          AdminLayout,
          Head,
          Table,
          Tbody,
          TableRow,
          Tbody,
          TableHeader,
          ActionMessageApp ,
          XMarkIcon,PencilIcon,ChevronDoubleDownIcon ,CurrencyDollarIcon,CheckCircleIcon,
          PrinterIcon,
          InputErrorApp, 
          Pagination,
          ConfirmModalApp,
          PerPage
      },
      data(){
          return{
            id_pay:'',
            confirmModel:false,
            termSearch:'',
            perPage:this.filters.perPage,
            startDate:this.filters.startDate,
            buoi:this.filters.buoi,
            endDate:this.filters.endDate,
            id_service:'',
            pay:'',
            rows:'',
            animals:[
              ['columm1','column2','column3']
            ],
            termProvince:'',
            stateDistrict:false,
            termProvince:'',
            getdistricts:'',
            tongtien:'',
            sumUsd:'',
            getFist:'',
            getServices:'',
            getqty:'',
            value: ['https://example.com','123'],
            showAdd:true,
            showService:false,
              tong:'',
              checkededit:true,
              form: this.$inertia.form({
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
                
              },
                {
                  resetOnSuccess: false,
                }
              ),
          }
      },
      watch:{
        '$page.props.flash.success':function(value){
          if(value){
              //this.closeModal();
              this.confirmModel=false;
          }
      },
        'termSearch':function(value){
          this.filterData();
        },
        // 'perPage':function(value){
        //   this.filterData();
        // },
          "form.services":function(value){
              this.getFist=this.catelogies.filter(el => value.includes(el.id))
            
            this.getServices = this.getFist.map((element1, index) => ({id_service: element1.id, name: element1.name,don_gia:element1.don_gia, sl:this.form.qty[element1.id],thanhtien:+parseFloat(this.form.qty[element1.id]*(+element1.don_gia)).toFixed(2) }))
            this.sumUsd = this.getServices.reduce(
              (accumulator, currentValue) => accumulator + currentValue.thanhtien,0);
              let transfer = this.getTransfer.Transfer.Transfer
              const currenUsd = transfer.replace(/\,/g,'');

            const sumUs = parseFloat(this.sumUsd).toFixed(2);
            this.tongtien = +parseFloat(sumUs).toFixed(2)*currenUsd;
            //console.log('hehehehe',currenUsd)
          },
          // "termProvince":function(value){
          //   if(value){
          //     this.provinceHandle(value)
          //   }
          // },
          'form.id_province':function(value){
            if(value){
              this.provinceHandle(value);
            }
            else{
              this.stateDistrict=false;
            }
          },
          'form.id_district':function(value){
            this.districtHandle(value);
          }
        },
      computed:{
            headers() {
                return [
                    { name: "#",class:'w-8' },
                    { name: "Số BL",class:'w-10 text-red-10' },
                    { name: "Ngày",class:'w-24 text-red-10' },
                    { name: "Tên Đơn vị (Khách hàng)", class:'w-56 text-left'},
                    { name: "Đia chỉ",class:'w-56 text-left' },
                    { name: "Vắc xin(VT)" , class:'w-44 '},
                    { name: "SL",class:'w-10' },
                    { name: "Tổng" ,class: "text-right w-44"},
                    { name: "Bằng chữ", class:'w-56' },
                    { name: "Tiền mặt", class:'w-10' },
                    { name: "Chuyển khoản", class:'w-10' },
                    { name: "Buổi", class:'w-10' },

                    { name: "Thu ngân", class:'w-24' },
                ];
            },
            classTable(){
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 text-blue-800'
            },
            classRow(){
                return ' text-center border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
            },
          
            classSearch(){
                const classSearch = {
                    wrapClass:'w-96 flex items-center space-x-1',
                    labelclass:'w-14',
                    searchClass:"flex-1 ml-2 h-7 border border-blue-900 rounded-lg px-2"
                }
              return classSearch;
            }
        },
      methods:{
        handlePerPage(e){
          this.$inertia.get(route('reports.index'),
          {  //search:this.search,
            perPage:e.perPage,
            startDate: this.startDate,
            endDate: this.endDate,
          },
          {
            preserveState:true,
            replace:true            }
          )
        },
        Clear(){
          this.$inertia.get(route('reports.index'))
        },
        confirmPay(id){
          this.confirmModel=true
          this.id_pay=id
        },
        closeConfirmModal(){
          this.confirmModel=false
        },
        handlePayConfirm(id){
          this.$inertia.put(route('confirmPay',id));
        },
        districtHandle(id){
            this.$inertia.get(route('custommers.index'),
              {
                // bn_code:this.custommer_id,
                // perPage: this.perPage,
                // ousentFill: this.ousentFill,
                // readcodeFill: this.readcodeFill,
                // startDate: this.startDate,
                // endDate: this.endDate,
                termDistrict: this.form.id_district,
              },
              {
                preserveState: true,
                replace: true,
              }
            );
          },
        provinceHandle(code){
          
          if(code){
            const fillData = this.districts.filter(function (el) {
              return el.id_province == code
            });
            this.getdistricts = fillData;
          }
        }, 
        replaceString(value){
          return (value).replace(/,/g, ',')
        },
        fixNumber(value){
          return value.toFixed(3);
        },
        changeToNumber(value){
          return parseFloat(value).toFixed(3);
        },
        formatPrice_1(value) {
          let val = (value/1).toFixed(0).replace('.', ',')
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        formatPrice(value) {
          let val = (value/1).toFixed(3).replace(',', '.')
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        filterData(){
          this.$inertia.get('reports',
          { 
            startDate: this.startDate,
            endDate: this.endDate,
            id_service:this.id_service,
            buoi:this.buoi,
            term:this.termSearch,
            perPage:this.perPage,
            //pay:this.pay,
          },
          {
            preserveState: true,
            replace: true,
          })
        },
        formatDate(value) {
          if (value) {
            return moment(String(value)).format("DD/MM/YYYY");
          }
        },
      }
    }
