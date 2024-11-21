
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
        childs:'',
        filters:'',
        is_admin:'' ,
        quan:'',
        phuong:'',
        boy_I:'',
        boy_II:'',
        boy_III:'',
        boy_IV:'',

        girl_I:'',
        girl_II:'',
        girl_III:'',
        girl_IV:'',
    
        child_25_60_I:'',
        child_25_60_II:'',
        child_25_60_III:'',
        child_25_60_IV:'',

        child_0_24_I:'',
        child_0_24_II:'',
        child_0_24_III:'',
        child_0_24_IV:'',

        child_tu_duoi_6_I:'',
        child_tu_duoi_6_II:'',   
        child_tu_duoi_6_III:'',
        child_tu_duoi_6_IV:'',
        
        canDo1Lan_25_60:'',
        canDo2Lan_25_60:'',
        canDo3Lan_25_60:'',
       
        tiLeCanDo1Lan_25_60:'',
        tiLeCanDo2Lan_25_60:'',
        tiLeCanDo3Lan_25_60:'',
        

        soSinhDuoi2500_I:'',
        soSinhDuoi2500_II:'',
        soSinhDuoi2500_III:'',
        soSinhDuoi2500_IV:'',

        child_alive_I:'',
        child_alive_II:'',
        child_alive_III:'',
        child_alive_IV:'',

        tiLeDuoi2500_I:'',
        tiLeDuoi2500_II:'',
        tiLeDuoi2500_III:'',
        tiLeDuoi2500_IV:'',

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
            //activeDefault:true,
            activeThongKe:this.filters.thongke,
            activeDanhSach:this.filters.danhsach,
            id_pay:'',
            confirmModel:false,
            termSearch:'',
            perPage:this.filters.perPage,
            startDate:this.filters.startDate,
            buoi:this.filters.buoi,
            endDate:this.filters.endDate,
            nam:this.filters.nam,
            id_service:'',
            pay:'',
            rows:'',
            
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
                    { name: "Mã ĐD ",class:' text-red-10' },
                    { name: "Tên Trẻ",class:' text-red-10' },
                    { name: "Năm sinh", class:' text-left'},
                    { name: "GT", class:' text-left'},
                    { name: "Đia chỉ",class:' text-left' },
                    { name: "P/X" , class:''},
                    { name: "Q/H",class:'' },
                    { name: "Tên mẹ" ,class: ""},
                    { name: "Ngày cân đo", class:'' },
                    { name: "cân nặng", class:'' },
                    { name: "Chiều cao", class:'' },
                    { name: "C.cao/tuổi", class:'' },
                    { name: "C.nặng/tuổi", class:'' },
                    { name: "C.nặng/C.cao", class:'' },
                    { name: "Khám ĐK", class:'' },
                    { name: "Uống Vitamin", class:'' },

                ];
            },
            backgroundClass() {
              return 'bg-blue-600 text-white';
            },
            classTable(){
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 text-blue-800'
            },
            classRow(){
                return 'border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
            },
            classtBody(){
              return ' border-r'
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
        handleThongKe(){
          
          this.activeThongKe=true
          this.activeDanhSach=false
          this.$inertia.get(route('reports.index'),
          {  //search:this.search,
            perPage:this.perPage,
            startDate: this.startDate,
            endDate: this.endDate,
            thongke:this.activeThongKe,
            nam:this.nam
          },
          {
            preserveState:true,
            replace:true            }
          )
        },
        handleDanhSach(){
          this.activeThongKe=false
          this.activeDanhSach=true
          this.$inertia.get(route('reports.index'),
          {  //search:this.search,
            perPage:this.perPage,
            startDate: this.startDate,
            endDate: this.endDate,
            danhsach:this.activeDanhSach
          },
          {
            preserveState:true,
            replace:true            }
          )
        },
        handlePerPage(e){
          this.$inertia.get(route('reports.index'),
          {  //search:this.search,
            perPage:this.perPage,
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
       
        filterData(){
          this.$inertia.get('reports',
          { 
            startDate: this.startDate,
            endDate: this.endDate,
            danhsach:this.activeDanhSach,
            thongke:this.activeThongKe,
            perPage:this.perPage,
            nam:this.name,
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
