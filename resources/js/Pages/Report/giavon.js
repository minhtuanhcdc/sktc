
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
//import jsPDF from 'jspdf' 
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
       XMarkIcon,PencilIcon,ChevronDoubleDownIcon ,CurrencyDollarIcon,CheckCircleIcon,
       PrinterIcon,
       InputErrorApp, 
       Pagination,
       ConfirmModalApp,
       PerPage
   },
   data(){
       return{
         timeView:'',
         id_pay:'',
         confirmModel:false,
         termSearch:'',
        // perPage:this.filters.perPage,
         id_service:'',
         id_coso:'',
         pay:'',
        // startDate:this.filters.startDate,
        // endDate:this.filters.endDate,
        // qui:this.filters.qui,
        // buoi:this.filters.buoi,
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
     'termSearch':function(value){
       this.filterData();
     },     
     'qui':function(value){
       this.timeView = value
       this.buoi=''
       this.startDate=''
       this.endDate=''
       
     },
     'buoi':function(value){
       this.timeView =  value
       this.qui=''
      
     },
     'startDate':function(value){
       //this.timeView = value
       this.qui=''
      
     },
     
    
     },
   computed:{
    quitest(){
     const date = new Date();
     var month = date.getMonth() + 1;
     const valueQui =(Math.ceil(month / 3));
     if(valueQui == 1){
       return this.quy ='quí I'
     }
     if(valueQui == 2){
       return this.quy ='quí II'
     }
     if(valueQui == 3){
       return this.quy ='quí III'
     }
     if(valueQui == 4){
       return this.quy ='quí IV'
     }
    
    },
     ngay(){
       const date = new Date();
       let day = date.getDate();
       return day
     },
     thang(){
       const date = new Date();
     
       let month = date.getMonth() + 1;
       return month
     },
     nam(){
       const date = new Date();
       
       let year = date.getFullYear();
       return year;
     },
       headers() {
           return [
               { name: "Stt",class:'w-8' },
               { name: "Tên hàng",class:' text-red-700' },
               { name: "ĐVT",class:' text-red-10' },
               { name: "Đơn giá bán",class:'w-44 text-red-100' },
               { name: "Đơn giá mua",class:'w-44 text-red-100' },
               { name: "SL", class:'w-24 text-left'},
               { name: "Doanh thu", class:'w-44 text-left'},   
               { name: "Giá vốn ", class:'w-44 text-left'},   
               { name: "DT-GV", class:'w-44 text-left'},   
              
           ];
       },
       classTable(){
           return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
       },
       classHeader(){
           return 'bg-blue-400 text-center text-black'
       },
       classRow(){
           return ' text-center border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-black'
       },
       classTd(){
         return 'text-black'
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
     Qui() 
     {
       const date = new Date();
       var month = date.getMonth() + 1;
       this.quy=month
       
     },
     getTime(data){
       const date = new Date(data);
       let day = date.getDate();
       let month = date.getMonth() + 1;
       let year = date.getFullYear();

       let hour = date.getHours();
       let minute = date.getMinutes();
       const ampm = hour >= 12 ? 'pm' : 'am';
       
       return day+'/'+month+'/'+year+'_'+hour+':'+minute+' '+ this.handleUppercase(ampm) ;
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
       this.$inertia.get(route('indexVaccine'))
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
       this.$inertia.get('indexVaccine',
       { 
         startDate: this.startDate,
         endDate: this.endDate,
         id_service:this.id_service,
         id_coso:this.id_coso,
         term:this.termSearch,
         perPage:this.perPage,
         buoi:this.buoi,
         qui:this.qui,
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
     printReport(data){
      // const viewDate=''
       if(this.qui){
         this.timeView = 'quý ' + this.qui
       }else{
         if(this.startDate != this.endDate){
           this.timeView = this.formatDate(this.startDate)+' đến '+ this.formatDate(this.endDate)
         }
         else{
           if(this.buoi){
             if(this.startDate == this.startDate){
               this.timeView = this.buoi =='am'? 'Sáng':'Chiều' + ' ('+this.formatDate(this.startDate)+' )'
             }

           }
         }
       }
       //console.log(data.data)
       var rows=[];
       const services=''
       data.data.map((item, index) => { 
        rows.push([  
           {text: index+1, lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
           {text: item.catelogies.medicine_name, lineHeight:1.4,fontSize:11,style:'test'},
           {text: item.catelogies.donvi_tinh, lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
           {text: item.tongSL, lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
           {text: this.formatPrice_1(item.don_gia), lineHeight:1.4, alignment:'center',fontSize:11,style:'test'},
           {text: (this.formatPrice_1(item.tongSL*item.don_gia)), lineHeight:1.4, alignment:'right',fontSize:11,style:'test'}
         ],)        
       });
      //console.log('Test:.....',rows)
      let docDefinition = {
       pageMargins:[40,20,30,10],
        content: [
          {text:'Trung Tâm Kiểm Soát Bệnh Tật Thành Phố',margin:[0,0,0,0],fontSize:12},
          {text:'Phòng Tài Chính - Kế Toán',bold:true,margin:[0,0,0,0],fontSize:12},
          {text:'Cơ sở 699 Trần Hưng Đạo, Q5',margin:[0,0,0,5],fontSize:11},
      
          {text:'BẢNG TỔNG HỢP DOANH THU - VẮCXIN',margin:[0,0,0,0],alignment:'center',fontSize: 14},
          {text:'Ngày:',margin:[0,0,0,0],alignment:'center', fontSize: 12},
          {text:`Thời gian: ${this.timeView}`,margin:[0,0,0,0], fontSize: 12},
       
          {
           // layout: 'lightHorizontalLines', // optional
            style: 'tableExample',
            table: {
             
              widths: [20,200,50,30,60,100],
      
              body: [
                  //[ 'Stt','Mã Vắcxin', 'Dịch vụ', 'ĐVT', 'Đơn giá','Số lượng', 'Thành tiền' ],
                  [ 
                   {text:'Stt',alignment:'center',alignItems:'center',margin:[0,4,0,0]} ,
                   {text:'Tên hàng',alignment:'center',justifyContent:'center',margin:[0,4,0,0]} ,
                   {text:'ĐVT',alignment:'center',alignment:'center',margin:[0,4,0,0]} ,
                   {text:'Sl',alignment:'center',alignItems:'center',margin:[0,4,0,0]} ,
                   {text:'Đơn giá',alignment:'center',alignItems:'center',margin:[0,4,0,0]} ,
                   {text:'Thành tiền',alignment:'center',margin:[0,4,0,0]}
                  ],
                ...rows,  
                 [ 
                  {text:`Tổng cộng: ${this.formatPrice_1(this.sum_price)}`,alignment:'right',alignItems:'center',margin:[0,6,0,0],colSpan:6, bold:true, fontSize:11} ,
               ],
                [ {text:`Thành tiền bằng chữ: ${this.DocTienBangChu(this.sum_price)}`,alignment:'right',alignItems:'center',margin:[0,6,0,0],colSpan:6, bold:true, fontSize:11}],       
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
          pageSize: {
            width: 900,
            height: 700,
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
     }
   }
 }
