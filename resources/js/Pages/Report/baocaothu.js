
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
  import * as pdfMake from "pdfmake/build/pdfmake";
  import * as pdfFonts from 'pdfmake/build/vfs_fonts';
 pdfMake.vfs = pdfFonts.pdfMake.vfs;
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
           
            is_admin:'' ,
            sum_price:'' ,
            tong:'' ,
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
          XMarkIcon,PencilIcon,ChevronDoubleDownIcon ,CurrencyDollarIcon,CheckCircleIcon,PrinterIcon,
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
            buoi:this.filters.buoi ,
            startDate:this.filters.startDate,
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
              //tong:'',
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
        'termSearch':function(value){
          this.filterData();
        },
        
        },
      computed:{
          headers() {
              return [
                  { name: "Số Hóa đơn",class:'w-8' },
                  { name: "Ngày",class:'w-8' },
                  { name: "Khách hàng", class:'w-24 text-left'},
                  { name: "Địa chỉ", class:'w-24 text-left'},
                  { name: "STT", class:'w-10 text-left'},
                  { name: "Tên hàng",class:' text-red-10' },
                  { name: "ĐVT",class:'w-24 text-red-10' },
                  { name: "Đơn Giá", class:'w-24 text-left'},
                  { name: "SL", class:' text-center'},
                  { name: "Thành tiền", class:'w-56 text-left'},
                  { name: "Buổi", class:'w-24 text-left'},
                  { name: "HTTT", class:'w-24 text-left'},
                  { name: "MaTraCuu", class:'w-24 text-left'},
                  { name: "Thu ngân", class:'w-24 text-left'},
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
        printBaocaoThu(data){
          var rows=[];
          const services=''
          data.data.map((item, index) => { 
           rows.push([  
            {text: item.bills.seri_bill, lineHeight:1.4,fontSize:11,style:'test'},
            {text: this.formatDate(item.created_at), lineHeight:1.4,fontSize:10,style:'test'},
            {text: item.bills.custommer.name, lineHeight:1.4,fontSize:11,style:'test'},
            {text: `${item.bills.custommer.address?item.bills.custommer.address:''
              //.concat(...item.bills.custommer.addressitem.bills.custommer.ward?', '+item.bills.custommer.ward.name:'')
              //.concat(...item.bills.custommer.district?', '+item.bills.custommer.district.name:'')
             // .concat(...item.bills.custommer.province?', '+item.bills.custommer.province.name+'.':'')
            
            }`,
             lineHeight:1.4,fontSize:11,style:'test'},
            {text: index+1, lineHeight:1.4,fontSize:11,style:'test',alignment:'center'},
            {text: item.catelogies.medicine_name, lineHeight:1.4,fontSize:11,style:'test'},
            {text: item.catelogies.donvi_tinh, lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
            {text: this.formatPrice_1(item.don_gia), lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
            {text: item.sl, lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
            {text: (this.formatPrice_1(item.sl*item.don_gia)), lineHeight:1.4, alignment:'right',fontSize:11,style:'test'},
            {text: item.bills.buoi=='am'?'Sáng':'Chiều', lineHeight:1.4,fontSize:11,style:'test'},
            {text: item.bills.pay_cash==1?'TM':'CK',alignment:'center', lineHeight:1.4,fontSize:11,style:'test'},
            {text: '2K23THA'+item.bills.seri_bill, lineHeight:1.4,fontSize:10,style:'test'},

            ],)        
          });
          console.log(data);
         let docDefinition = {
           content: [
             {text:'Trung Tâm Kiểm Soát Bệnh Tật Thành Phố',margin:[5,0,0,0],fontSize:12},
             {text:'Phòng Tài Chính - Kế Toán',bold:true,margin:[5,0,0,0],fontSize:12},
             {text:'Cơ sở 699 Trần Hưng Đạo, Q5',margin:[5,0,0,5],fontSize:11},
             {text:'BẢNG TỔNG HỢP DOANH THU - VẮCXIN',margin:[0,-10,0,0],alignment:'center',fontSize: 14},
             {text:'Ngày:',margin:[0,0,0,0],alignment:'center', fontSize: 12},
            //  {text:`Thời gian: ${this.timeView}`,margin:[0,0,0,0], fontSize: 12},
             {
              // layout: 'lightHorizontalLines', // optional
               style: 'tableExample',
               table: {
                 widths: [60,55,100,100,25,60,30,40,20,40,30,30,100],
                 body: [
                     //  [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13 ],
                     [ 
                      {text:'Số HĐ',alignment:'center',alignItems:'center',margin:[0,4,0,0], fontSize:11,	fillColor: '#eeeeee',} ,
                      {text:'Ngày',alignment:'center',justifyContent:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',} ,
                      {text:'Khách Hàng',alignment:'center',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',} ,
                      {text:'Địa Chỉ',alignment:'center',alignItems:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',} ,
                      {text:'STT',alignment:'center',alignItems:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',} ,
                      {text:'Tên Hàng',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'ĐVT',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'Đơn Giá',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'SL',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'Thành Tiền',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'Buổi',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'HTTT',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                      {text:'MaTraCuu',alignment:'center',margin:[0,4,0,0],	fillColor: '#eeeeee',},
                     ],
                  ...rows,  
                  //   [ 
                  //    {text:`Tổng cộng: ${this.formatPrice_1(this.sum_price)}`,alignment:'right',alignItems:'center',margin:[0,6,0,0],colSpan:6, bold:true, fontSize:11} ,
                  // ],
                  //  [ {text:`Thành tiền bằng chữ: ${this.DocTienBangChu(this.sum_price)}`,alignment:'right',alignItems:'center',margin:[0,6,0,0],colSpan:6, bold:true, fontSize:11}],       
                 ]
               }
             },
           
             {text:'',margin:[0,65,5,0]},
            
             { 
               columns:[
                 {text:'Người lập bảng',margin:[20,-60,0,0]},
                 {text: 'Kế toán thu',margin:[40,-60,0,0]},
                 {text: 'Tiêm chủng',margin:[60,-60,0,0]},
               ]
             },
             //{text:`${this.getTime(data.created_at)}`,margin:[420,20,5,0],italics:true,fontSize: 10},
           ],
          //  pageSize: {
          //    //148 × 210; 5,83 × 8,27
          //    width: 595.28,
          //    height: 'auto'
          //  },
          
          pageSize: 'A4', 
          pageOrientation: 'landscape',
          pageMargins: [ 15, 30, 20, 6],
           styles: {
             test:{
               margin:[0,6,0,0],
               //verticalAlign:'middle'
             },
             textStyle:{
               uppercase:true
             },
             sumprice:{
              color:'blue'
             },
            
             hcdc:{
               fontSize:12,
               bold: true,
               color:'blue',
               font:'Roboto',
             },
             series:{
               fontSize:12,
               alignment:'right',
               italics: true,
             },
             header: {
               fontSize: 22,
               bold: true
             },
             anotherStyle: {
               italics: true,
               alignment: 'right'
             },
            
             header: {
               fontSize: 18,
               bold: true,
               margin: [0, 0, 0, 10]
             },
             subheader: {
               fontSize: 16,
               bold: true,
               margin: [0, 10, 0, 5]
             },
             tableExample: {
               margin: [0, 5, 0, 15]
             },
             tableHeader: {
               bold: true,
               fontSize: 13,
               color: 'black'
             }
           },
         };  
         //pdfMake.createPdf(docDefinition).download('document.pdf');  
         const pdf = pdfMake.createPdf(docDefinition);
         pdf.open();
        },
        DocTienBangChu(SoTien) {
          var lan = 0;
          var i = 0;
          var so = 0;
          var KetQua = "";
          var tmp = "";
          var soAm = false;
          var ViTri = new Array();
          if (SoTien < 0) soAm = true;//return "Số tiền âm !";
          if (SoTien == 0) return "Không đồng";//"Không đồng !";
          if (SoTien > 0) {
              so = SoTien;
          }
          else {
              so = -SoTien;
          }
          if (SoTien > 8999999999999999) {
              //SoTien = 0;
              return "";//"Số quá lớn!";
          }
          ViTri[5] = Math.floor(so / 1000000000000000);
          if (isNaN(ViTri[5]))
              ViTri[5] = "0";
          so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
          ViTri[4] = Math.floor(so / 1000000000000);
          if (isNaN(ViTri[4]))
              ViTri[4] = "0";
          so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
          ViTri[3] = Math.floor(so / 1000000000);
          if (isNaN(ViTri[3]))
              ViTri[3] = "0";
          so = so - parseFloat(ViTri[3].toString()) * 1000000000;
          ViTri[2] = parseInt(so / 1000000);
          if (isNaN(ViTri[2]))
              ViTri[2] = "0";
          ViTri[1] = parseInt((so % 1000000) / 1000);
          if (isNaN(ViTri[1]))
              ViTri[1] = "0";
          ViTri[0] = parseInt(so % 1000);
          if (isNaN(ViTri[0]))
              ViTri[0] = "0";
          if (ViTri[5] > 0) {
              lan = 5;
          }
          else if (ViTri[4] > 0) {
              lan = 4;
          }
          else if (ViTri[3] > 0) {
              lan = 3;
          }
          else if (ViTri[2] > 0) {
              lan = 2;
          }
          else if (ViTri[1] > 0) {
              lan = 1;
          }
          else {
              lan = 0;
          }
          for (i = lan; i >= 0; i--) {
              tmp = this.docSo3ChuSo(ViTri[i]);
              KetQua += tmp;
              if (ViTri[i] > 0) KetQua += this.Tien[i];
              if ((i > 0) && (tmp.length > 0)) KetQua += '';//',';//&& (!string.IsNullOrEmpty(tmp))
          }
          if (KetQua.substring(KetQua.length - 1) == ',') {
              KetQua = KetQua.substring(0, KetQua.length - 1);
          }
          KetQua = KetQua.substring(1, 2).toUpperCase() + KetQua.substring(2);
          if (soAm) {
              return "Âm " + KetQua + " đồng";//.substring(0, 1);//.toUpperCase();// + KetQua.substring(1);
          }
          else {
              return KetQua + " đồng";//.substring(0, 1);//.toUpperCase();// + KetQua.substring(1);
          }
        },
        docSo3ChuSo(baso) {
          this.ChuSo = new Array(" không ", " một ", " hai ", " ba ", " bốn ", " năm ", " sáu ", " bảy ", " tám ", " chín ");
          this.Tien = new Array("", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ");
            var tram;
            var chuc;
            var donvi;
            var KetQua = "";
            tram = parseInt(baso / 100);
            chuc = parseInt((baso % 100) / 10);
            donvi = baso % 10;
            if (tram == 0 && chuc == 0 && donvi == 0) return "";
            if (tram != 0) {
                KetQua += this.ChuSo[tram] + " trăm ";
                if ((chuc == 0) && (donvi != 0)) KetQua += " linh ";
            }
            if ((chuc != 0) && (chuc != 1)) {
                KetQua += this.ChuSo[chuc] + " mươi";
                if ((chuc == 0) && (donvi != 0)) KetQua = KetQua + " linh ";
            }
            if (chuc == 1) KetQua += " mười ";
            switch (donvi) {
                case 1:
                    if ((chuc != 0) && (chuc != 1)) {
                        KetQua += " mốt ";
                    }
                    else {
                        KetQua += this.ChuSo[donvi];
                    }
                    break;
                case 5:
                    if (chuc == 0) {
                        KetQua += this.ChuSo[donvi];
                    }
                    else {
                        KetQua += " lăm ";
                    }
                    break;
                default:
                    if (donvi != 0) {
                        KetQua += this.ChuSo[donvi];
                    }
                    break;
            }
            return KetQua;
        },
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
          this.$inertia.get(route('generalReport'))
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
          this.$inertia.get('generalReport',
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
