
   import AdminLayout from '../Layouts/AdminLayout.vue';
   import { Head, Link, useForm } from '@inertiajs/vue3';
   import Table from '../Components/Table/Table.vue';
   import TableHeader from '../Components/Table/TableHeaders.vue'
   import TableRow from '../Components/Table/TableRow.vue';
   import Tbody from '../Components/Table/TableBody.vue';
   import ActionMessageApp from '../Components/ActionMessage.vue';
   import InputErrorApp from '../Components/InputError.vue'
   import PerPage from '../Components/PerPage.vue'
   import Search from '../Components/Search.vue'
   import ModalApp from '../Components/Modal.vue'
   import QrcodeVue from 'qrcode.vue'
   import moment from 'moment';
   import ConfirmModalApp from '../Components/ConfirmationModal.vue'
  import { PencilIcon, XMarkIcon, PrinterIcon,ChevronDoubleDownIcon,CurrencyDollarIcon, ArrowRightIcon} from '@heroicons/vue/24/solid'
    //import { PrinterIcon  } from "@vue-hero-icons/outline"
  import { router } from '@inertiajs/vue3'
  import Pagination from '../Components/Pagination.vue'
  import * as pdfMake from "pdfmake/build/pdfmake";
  import * as pdfFonts from 'pdfmake/build/vfs_fonts';
  //pdfMake.vfs = pdfFonts.pdfMake.vfs;
  import { ref } from 'vue'
  //const inputqty = ref(null)
  // import jsPDF from 'jspdf' 
  //import domtoimage from "dom-to-image";  
    export default{   
      name:'Custommer',
      props:{
            bills:'',
            currencyWolrd:'',
            currencyVietcomBank:'',
            getTransfer:"",
            catelogies:'',
            provinces:'',
            districts:'',
            wards:'',
            unpaid:'',
            unconfimred:'',
            hcdcconfimred:'',
            total_pay:'',
            currentExchangeFix:'',
            filters:''
        },
      components:{
        Search,
        PerPage,
        AdminLayout,
        Head,
        Table,
        Tbody,
        TableRow,
        Tbody,
        TableHeader,
        ActionMessageApp ,
        ModalApp,
        XMarkIcon,PencilIcon,ChevronDoubleDownIcon,CurrencyDollarIcon,ArrowRightIcon,
        QrcodeVue,
        PrinterIcon,
        InputErrorApp, 
        Pagination,
        ConfirmModalApp
      },
      data(){
          return{ 
            changePay:'',
            cashpay:'', 
            confirm_content:'', 
            save:false,
            perPage:this.filters.perPage,
            confirmDelete:false,
            id_pay:'',
            confirmModel:false,
            startDate:this.filters.startDate,
            endDate:this.filters.endDate,
            changeService:'',
            edit:false,
            editEchange:'',
            id_edit:'',
            test:'',
            rows:'',
            openModal:false,
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
            maxWidth:'64rem',
            showService:false,
              tong:'',
              checkededit:true,
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
                 sohieu:''  
                
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
          "form.services":function(value){
           
            this.getFist=this.catelogies.filter(el => value.includes(el.id))
            this.getServices = this.getFist.map((element1, index) => ({id_service: element1.id, name: element1.name,don_gia:element1.don_gia, sl:this.form.qty[element1.id],thanhtien:+parseFloat(this.form.qty[element1.id]*(+element1.don_gia)).toFixed(2) }))
            this.sumUsd = this.getServices.reduce(
              (accumulator, currentValue) => accumulator + currentValue.thanhtien,0);
              
              let transfer = this.getTransfer.Transfer.Transfer
              const currenUsd = transfer.replace(/\,/g,'');

            const sumUs = parseFloat(this.sumUsd).toFixed(2);
            if(this.currentExchangeFix){
              this.tongtien = +parseFloat(sumUs).toFixed(2)*this.currentExchangeFix.exchange_usd	;
            }
            else{
              this.tongtien = +parseFloat(sumUs).toFixed(2)*currenUsd;
            }
            
          },
          "test":function(value){
          
            this.getFist=this.catelogies.filter(el => value.includes(el.id))
            this.getServices = this.getFist.map((element1, index) => ({id_service: element1.id, name: element1.name,don_gia:element1.don_gia, sl:this.form.qty[element1.id],thanhtien:+parseFloat(this.form.qty[element1.id]*(+element1.don_gia)).toFixed(2) }))
            this.sumUsd = this.getServices.reduce(
              (accumulator, currentValue) => accumulator + currentValue.thanhtien,0);
              
              let transfer = this.getTransfer.Transfer.Transfer
              const currenUsd = transfer.replace(/\,/g,'');

            const sumUs = parseFloat(this.sumUsd).toFixed(2);
            if(this.currentExchangeFix){
              this.tongtien = +parseFloat(sumUs).toFixed(2)*this.currentExchangeFix.exchange_usd	;
            }
            else{
              this.tongtien = +parseFloat(sumUs).toFixed(2)*currenUsd;
            }
            
          },
         
          'input_qty': function (value) {
            console.log(value);
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
                  { name: "#",class:'w-10 px-1' },
                  { name: "Số BN",class:'w-10 text-red-10' },
                  { name: "Ngày",class:'w-10 text-red-10' },
                  { name: "Tên Đơn vị(Khách hàng)", class:'w-32'},
                  { name: "Đia chỉ",class:'w-44' },
                  { name: "DV" , class:'w-56 '},
                  { name: "Tỉ giá " },
                  { name: "Tổng USD" },
                  { name: "Tổng VNĐ" ,class: "text-right w-52"},
                  { name: "Tờ khai" },
                  { name: "Mã BC" },
                  { name: "QR Code" },
                  { name: "Chuyển khoản",class:'border-r' },
                  { name: "tiền mặt" },
                  { name: "Action", class: "text-right" },
              ];
          },
          classTable(){
              return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
          },
          classThead(){
              return 'text-center text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 text-blue-800 border-r border-r-2'
          },
          classRow(){
              return 'py-2 text-center bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
          },
          classSearch(){
              const classSearch = {
                  wrapClass:'w-96 flex items-center space-x-1',
                  labelclass:'w-14',
                  searchClass:"flex-1 ml-2 h-7 border border-blue-900 rounded-lg px-2"
              }
            return classSearch;
          },
          tong(){

          }
      },
      methods:{
        deleteBill(id){
          this.confirmDelete=true;
          this.confirmModel=true;
          this.id_pay = id;
          this.confirm_content='Xóa'
        },
        closeConfirmModal(){
          this.confirmModel=false
          this.changePay=false
          this.cashpay=false
        },
        confirmPay(b){
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
        handlePay(id){
         
          this.$inertia.put(route('payUpdate',id))
          this.confirmModel=false
          this.changePay=false
          this.cashpay=false
        },
        handleCash(id){
          this.$inertia.put(route('payCurrentUpdate',id))
          this.confirmModel=false
          this.changePay=false
          this.cashpay=false
        },
        Clear(){
          this.$inertia.get(route('custommers.index'))
        },
        filterData(){
         
          this.$inertia.get(route('custommers.index'),
          { 
            startDate: this.startDate,
            endDate: this.endDate,
            perPage:this.perPage
          },
          {
            preserveState: true,
            replace: true,
          })
        },
        changeServiceHandle(){
          this.changeService = !this.changeService
       
        },
        reset(){
          this.edit=false
          this.form.sohieu = ''
          this.form.name = ''
          this.form.phone = ''
          this.form.email = ''
          this.form.mst = ''
         // this.showService=false;
          this.form.address = ''
          this.form.id_province = ''
          this.form.services=[]
          this.form.qty=[],
          this.confirmDelete=false;
          this.form.id_province=''
          this.form.id_district=''
          this.form.id_ward=''
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

          this.form.tokhai = b.tokhai
          this.form.email = b.tokhai
          this.form.services = services
          this.form.getServices=this.getServices
          const service = b.services.map((element1, index) => ({[element1.id_service]: element1.sl}));
          service.forEach((inputObject) => {
              const objectKey = Object.keys(inputObject)[0]
              const objectValue = Object.values(inputObject)[0]
             this.form.qty[objectKey] = objectValue
           })
           
          this.editEchange=b.usd_exchange
        },
        openModalCustommer(){
          this.openModal=true
        },
        closeModal(){
            this.openModal=false
            //this.reset();
            this.showService=true;  
        },
        handleSearch(e){
            this.$inertia.get(route('custommers.index'),
            {  
              termSearch:e.termSearch,
            },
            {
              preserveState:true,
              replace:true            }
            )
        },
        handlePerPage(e){
            this.$inertia.get(route('custommers.index'),
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
        prinPdf(data){
          // let currentDate = new Date().toLocaleDateString();
          const date = new Date();
          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          var rows=[];
          const services=''
          data.services.map((item, index) => { 
           rows.push([
                    
                      {text: index+1, lineHeight:1.4, alignment:'center',fontSize:10},
                      {text: item.catelogies?item.catelogies.code:'', lineHeight:1.4,fontSize:10},
                      {text: '-'+item.catelogies.name, lineHeight:1.4,fontSize:9,margin:[0,2,0,0]},
                      {text: item.don_gia, lineHeight:1.4, alignment:'center',fontSize:10},
                      {text: item.sl, lineHeight:1.4, alignment:'center',fontSize:10},
                      {text: ((item.sl*item.don_gia).toFixed(2)), lineHeight:1.4, alignment:'right',fontSize:10}
                     
                   ],)
                   
          });
         
        //   var grid2 = rows[0].map(function(col, i) {
        //     return rows.map(function(row) {
        //         return row[i]
        //     });
            
        // });
       // console.log('hehehe',grid2)
        var grid3 = [];
        
        rows.forEach((element) => {
          grid3.push(
          [
            {text:element[0].text},
            {text:element[1].text},
            {text:element[2].text},
            {text:element[3].text},
            {text:element[4].text},
            {text:element[5].text},
          ]
        )
         })

          let docDefinition = {
            // header: [
              
            //   {text:'TRUNG TAM KIEM SOAT BENH TAT TP.HCM',margin: [ 5, 2, 10, 200 ]}, 
            // ],
            // background: function(currentPage, pageSize) {
            //   return `page ${currentPage} with size ${pageSize.width} x ${pageSize.height}`
            // },
          
            pageMargins:[40,20,30,10],
            content: [
              {text:`${data.posts?data.posts.name:''}`,margin:[0,0,0,0]},
              {text:'Bưu cục gốc:'+`${data.posts?data.posts.code:''}`,bold:true,margin:[0,0,0,10],},
              {text:'BIÊN NHẬN THU TIỀN',margin:[0,-10,0,0],alignment:'center', color:'blue',fontSize: 15},
              {text:'Ngày '+day+' Tháng ' + month +' Năm '+ year, alignment:'center',margin:[0,0,0,0],italics:true,fontSize: 12 },

              {text:'Số biên lai: '+`${data.seri_bill?data.seri_bill:''}`,margin:[350,-30,0,0], alignment:'center', italics:true,fontSize:12},
              {text:'Tờ khai: '+`${data.tokhai?data.tokhai:''}`,margin:[350,5,0,0], alignment:'center',italics:true,fontSize:12},
              {text:'Số hiệu: '+`${data.sohieu?data.sohieu:''}`,margin:[325,5,0,0], alignment:'center',italics:true,fontSize:12},
              {text:'Số giấy CN: ',margin:[340,5,0,5], alignment:'center',italics:true,fontSize:12},

              {text:'MST/SDT: '+`${data.custommer.mst?data.custommer.mst:''}`,margin:[0,-10,0,5]},
              {text:'Đơn vị(Người nộp): '+`${data.custommer.name}`,bold:true},
              { text: 'Địa chỉ: '  +`${data.custommer.address}`
              +`${data.custommer.ward?', '+data.custommer.ward.name:''}`
              +`${data.custommer.district?', '+data.custommer.district.name:''}`
              +`${data.custommer.province?', '+data.custommer.province.name:''}`
              , margin: [ 0, 6, 0, 0 ] },
              
              {text:'Tờ khai số: '+`${data.tokhai?data.tokhai:''}`,margin:[0,5,0,5]},
              {text:'Email: '+`${data.custommer.email?data.custommer.email:''}`,margin:[0,0,0,5]},
              {text:'Hình thức thanh toán: ',margin:[0,0,0,5]},
              {text:'Tên tài khoản: Trung Tâm Kiểm Soát Bệnh Tật TP.HCM',margin:[0,0,0,5]},
              {text:'Số tài khoản: 1036542822 tại Ngân hàng thương mại cổ phần Ngoại thương Việt Nam',margin:[0,0,0,5]},
              { text: '',margin: [ 0, 4, 0, 0 ]},
             
              {
               // layout: 'lightHorizontalLines', // optional
                style: 'tableExample',
                table: {
                  // headers are automatically repeated if the table spans over multiple pages
                  // you can declare how many rows should be treated as headers
                  //headerRows:2 ,
                  widths: [ 20,80,280,30,30,50],
          
                  body: [
                      //[ 'Stt', 'Dịch vụ', 'Đơn giá', 'Số lượng', 'Thành tiền' ],
                      [ 
                        {text:'Stt',alignment:'center',alignItems:'center',margin:[0,6,0,0]} ,
                        {text:'Mã HH',alignment:'center',alignItems:'center', margin:[0,6,0,0]} ,
                        {text:'Tên hàng',alignment:'center',justifyContent:'center',margin:[0,6,0,0]} ,
                        {text:'Đơn giá',alignment:'center',alignItems:'center',margin:[0,4,0,0]} ,
                        {text:'Sl',alignment:'center',alignItems:'center',margin:[0,6,0,0]} ,
                        {text:'Thành tiền',alignment:'center',margin:[0,4,0,0]}
                      ],
                    ...rows,  
                     //grid3 
                             
                  ]
                }
              },
             
              { text:`Tỉ giá: ${this.fixNumber_us(data.usd_exchange)}`,margin: [ 0, -10, 0, 0 ]},
              {text:`Tổng(USD):   ${data.total_price}`,margin:[400,-10,0,0], alignment:'center',italics:true,fontSize:12},
              
              {text:`Tổng: ${this.formatPrice_1(data.total_pay)} Đồng`, alignment:'left',margin:[0,10,10,0],bold:true, },
              {text:`Bằng chữ: ${data.text_total_pay} đồng`, alignment:'left',margin:[0,5,10,0], italics:true,style:['sumprice'] },
              {qr: `${data.custommer.name}`, fit: '80',alignment: 'left',margin:[0,10,0,0] },
              { 
                columns:[
                  {text:'Người nộp tiền',margin:[140,-60,0,0]},
                  {text: 'Người thu tiền',margin:[100,-60,0,0]},
                ]
              }
            ],
          
            footer: {
              columns: [
                { text:  `${data.custommer.name}`, alignment: 'right',margin:[0,0,10,2] },// margin: [left, top, right, bottom]
              ]
            },
          
            styles: {
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
          // pdfMake.createPdf(docDefinition).download('document.pdf');  
          const pdf = pdfMake.createPdf(docDefinition);
          pdf.open();
        },
        districtHandle(id){
          this.save=false;
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
        fillService(value){
            this.getServices=this.catelogies.filter(el => value.includes(el.id))
          },
        replaceString(value){
          return (value).replace(/,/g, ',')
        },
        fixNumber_us(value){
          value += '';
          var x = value.split('.');
          var x1 = x[0];
          var x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
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
        saveCustommer(){
          var exChange =''
          if(this.currentExchangeFix){
            exChange=this.currentExchangeFix.exchange_usd
          }
          else{
            exChange=this.replaceString(this.getTransfer.Transfer.Transfer)
          }
          const data = [
            this.getServices,
            exChange,
            this.formatPrice_1(this.tongtien)
          ]
          this.edit
          ? this.form.put(route('custommers.update',{data:data,id:this.id_edit}),data)
          :this.form.post(route('custommers.store',{data:data}));  
          this.save=true;
        },
        updateCustommer(b){
          var exChange =''
          if(this.currentExchangeFix){
            exChange=this.currentExchangeFix.exchange_usd
          }
          else{
            exChange=this.replaceString(this.getTransfer.Transfer.Transfer)
          }
          const data = [
            this.getServices,
            exChange,
            this.formatPrice_1(this.tongtien)

          ]
          this.form.post(route('updateBill',{data:data,id:this.id_edit}));

          // router.post('admin/custommers', data, {
          //   forceFormData: true,
          // })
        },
        deletePay(id){
          this.form.delete(route('custommers.destroy',id));
          this.closeConfirmModal();

      },
        formattedDate(date) {
          return moment(date).format("DD/MM/YYYY")
        },
      }
    }
