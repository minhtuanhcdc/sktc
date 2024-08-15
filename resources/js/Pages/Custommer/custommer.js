   import AdminLayout from '../../Layouts/AdminLayout.vue';
   import { Head, Link, useForm } from '@inertiajs/vue3';
   import ActionMessageApp from '../../Components/ActionMessage.vue';
   
   import PerPage from '../../Components/PerPage.vue'
   import Search from '../../Components/Search.vue'
   
    //import { PrinterIcon  } from "@vue-hero-icons/outline"
  import { router } from '@inertiajs/vue3'
  import Pagination from '../../Components/Pagination.vue'
  import * as pdfMake from "pdfmake/build/pdfmake";
  import * as pdfFonts from 'pdfmake/build/vfs_fonts';
  pdfMake.vfs = pdfFonts.pdfMake.vfs;
  
  import { ref } from 'vue'
  import Kiemdich from './Kiemdich.vue'
  import FormKiemdich from './FormKiemdich.vue'
  
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
            tienMat:'',
            chuyenKhoan:'',
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
        ActionMessageApp ,
        Pagination,
        Kiemdich,
        FormKiemdich,
       
      },
      data(){
          return{
            showAdd:false,
            titleBreadcumb:'Nhập thông tin tiêm chủng',
            buoi:'',
            save:false,
            perPage:this.filters.perPage,
            id_pay:'',
            startDate:this.filters.startDate,
            endDate:this.filters.endDate,
            edit:false,
            editEchange:'',
            id_edit:'',
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
            showAdd:false,
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
               sohieu:'' , 
               test:'sadfd' , 
              
            },
              {
                resetOnSuccess: false,
              }
            ),
              
          }
      },
      watch:{
          'form.id_province':function(value){
            if(value){
              this.provinceHandle(value);
            }
            else{
              this.stateDistrict=false;
            }
          },
        
        },
      computed:{
          classSearch(){
              const classSearch = {
                  wrapClass:'w-96 flex items-center space-x-1',
                  labelclass:'w-14',
                  searchClass:"flex-1 ml-2 h-7 border border-blue-900 rounded-lg px-2"
              }
            return classSearch;
          },
          classTiemchung(){
            return 'bg-900 text-white'
          },
          time(){
            return new Date()
          }
      },
      methods:{
        getExchange(){
         
          //const input ='https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx';
          //const input = fetch('https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx');
          //const input = fetch("https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx")
          

          // const regex = /<Exrate CurrencyCode="([A-Z]{3})" .+?Buy="(.+?)" Transfer="(.+?)" Sell="(.+?)"\/>/g; 
          // const rates = [];
          // let match;
          // while ((match = regex.exec(input)) !== null) {
          //   rates.push({
          //     currencyCode: match[1], 
          //     buy: match[2],
          //     transfer: match[3],
          //     sell: match[4]
          //   });
          // }

          //console.log('Hehe: ',input);
        },
        closeFormHandle(){
          this.showAdd=false;
        },
       
        tiemChung(){
          this.tiemChungShow=true;
          this.titleBreadcumb='Nhập thông tin tiêm chủng'
        },
        kiemDich(){
          this.tiemChungShow=false;
          this.titleBreadcumb='Nhập thông tin hàng hóa kiểm dịch'
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
        handleCash(id){
          this.$inertia.put(route('payCash',id))
          
        },
        handleTransfer(id){
          this.$inertia.put(route('payTransfer',id))
        },
        deletePay(id){
          this.form.delete(route('custommers.destroy',id));
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
        
          const date = new Date();
          let day = date.getDate();
          let month = date.getMonth() + 1;
          let year = date.getFullYear();
          let hour = date.getHours();
          let buoi = hour >= 12 ? 'Chiều' : 'Sáng';
          var rows=[];
          const services=''
          data.services.map((item, index) => { 
          rows.push([      
              {text: index+1, lineHeight:1.4, alignment:'center',fontSize:10,style:'test'},
              {text: item.catelogies?item.catelogies.code:'', lineHeight:1.4,fontSize:10, alignment:'justify',style:'test'},
              {text: '-'+item.catelogies.name, lineHeight:1.4,fontSize:10,style:'test'},
              //{text: item.catelogies.donvi_tinh, lineHeight:1.4, alignment:'center',fontSize:10,style:'test'},
              {text: item.don_gia, lineHeight:1.4, alignment:'center',fontSize:10,style:'test'},
              {text: item.sl, lineHeight:1.4, alignment:'center',fontSize:10,style:'test'},
              {text: item.sl*item.don_gia, lineHeight:1.4, alignment:'right',fontSize:10,style:'test'}         
          ],)         
          });
        
        let docDefinition = {

           pageMargins:[40,20,30,10],
            content: [
             
              {text:'Trung Tâm Kiểm Soát Bệnh Tật Thành Phố',bold:true,margin:[0,0,0,5],fontSize:10},
              {text:'Mã QHNS: 1128804',margin:[0,0,0,0],fontSize:10},
              {text:'Mẫu số: C45-BB',margin:[390,-30,0,0], italics:true, fontSize:10},
              {text:'(Ban hành kèm theo Thông tư số 107/2017/TT-BTC ngày 10/10/2017 ca Bộ Tài Chính)',margin:[300,0,0,0],fontSize:9, alignment:'center'},

              {text:'BIÊN LAI THU TIỀN',margin:[0,0,0,0],alignment:'center', color:'blue',fontSize: 15},
              {text:'Ngày '+day+' Tháng ' + month +' Năm '+ year, alignment:'center',margin:[0,0,0,0],italics:true,fontSize: 12 },

              {text:'Số biên lai: '+`${data.seri_bill?data.seri_bill:''}`,margin:[400,-25,0,0], alignment:'left',italics:true,fontSize:10},
              //{text:'Số hiệu: ',margin:[380,-30,0,0], alignment:'left', italics:true,fontSize:12},
              {text:'Số giấy CN: ',margin:[400,0,0,3], alignment:'left',italics:true,fontSize:10,style:['textStyle']},
              {text:'HTTT: '+`${data.pay_cash?'TM':data.pay_transfer?'CK':''}`,margin:[400,0,0,0], alignment:'left',italics:true,fontSize:10},
              {text:'Tỉ giá: '+`${this.formatPrice_1(data.usd_exchange)}`,margin:[400,0,0,5], alignment:'left',italics:true,fontSize:11},

              {text:'MST/SĐT: '+`${data.custommer.mst?data.custommer.mst:''}`,margin:[0,-30,0,0]},
              {text:'Họ tên người nộp tin: ',margin:[0,2,0,5]},
              {text:`${this.handleUppercase(data.custommer.name)}`,margin:[125,-18,0,0]},
              { text: 'Địa chỉ: '  +`${data.custommer.address}`
              +`${data.custommer.ward?', '+data.custommer.ward.name:''}`
              +`${data.custommer.district?', '+data.custommer.district.name:''}`
              +`${data.custommer.province?', '+data.custommer.province.name:''}`
              , margin: [ 0,2, 0, 0 ] },
              { text: '',margin: [ 0, 2, 0, 0 ]},
             
              {
               
                style: 'tableExample',
                table: {
                
                  widths: [15,70,240,50,50,60],
          
                  body: [
                      //[ 'Stt','Mã Vắcxin', 'Dịch vụ', 'ĐVT', 'Đơn giá','Số lượng', 'Thành tiền' ],
                      [ 
                        {text:'Stt',alignment:'center',alignItems:'center',margin:[0,6,0,0]} ,
                        {text:'Mã HH',alignment:'center', margin:[0,6,0,0]} ,
                        {text:'Nội dung thu',alignment:'center',justifyContent:'center',margin:[0,6,0,0]} ,
                        {text:'Đơn giá',alignment:'center',alignItems:'center',margin:[0,4,0,0]} ,
                       //{text:'ĐVT',alignment:'center',alignment:'center',margin:[0,6,0,0]} ,
                       {text:'Số lượng',alignment:'center',alignItems:'center',margin:[0,6,0,0]} ,
                        {text:'Thành tiền',alignment:'center',margin:[0,4,0,0]}
                      ],
                    ...rows,
			 
                    [ 
                      
                      {text:`Tổng(USD): ${data.total_price}` ,alignment:'right',alignItems:'center',margin:[0,4,0,0],colSpan:6,bold:true, fontSize:11},
                      
                    ],
                    
                    [ 
                     
                      {text:`Tổng(VNĐ): ${this.formatPrice_1(data.total_pay)}`,alignment:'right',alignItems:'center',margin:[0,4,0,0],colSpan:6, bold:true, fontSize:11} ,
                    ],
                    
                    [ 
                      
                      {text:`Số tiền bằng chữ: ${data.total_pay?this.DocTienBangChu(data.total_pay):''}`,margin:[0,4,0,0],colSpan:6, fontSize:11,bold:true,alignment:'center'},   
                     
                    ],
                 
                   
                  ]
                }
              },
             
               
            
              {text:'',margin:[0,65,5,0]},
             
              { 
                columns:[
                  {text:'Người nộp tiền',margin:[60,-60,0,0]},
                  {text: 'Người thu tiền',margin:[60,-60,0,0]},
                ]
              },
              {text:`${this.getTime(data.created_at)}`,margin:[420,-5,-10,-10],italics:true,fontSize: 10},
            ],
            defaultStyle: {
             //font: ''
            },
            pageSize: 'A5', 
          pageOrientation: 'landscape',
          pageMargins: [ 30, 20, 20, 20 ],
            // footer: {
            //   columns: [
            //     { text:  `${data.custommer.name}`, alignment: 'right',margin:[0,0,10,2] },// margin: [left, top, right, bottom]
            //   ]
            // },
          
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
                //font:'Roboto',
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
          // pdfMake.createPdf(docDefinition).download('document.pdf');  
          const pdf = pdfMake.createPdf(docDefinition);
          pdf.open();
        },
        districtHandle(e){
          this.save=false;
            this.$inertia.get(route('custommers.index'),
              {
                // bn_code:this.custommer_id,
                // perPage: this.perPage,
                // ousentFill: this.ousentFill,
                // readcodeFill: this.readcodeFill,
                // startDate: this.startDate,
                // endDate: this.endDate,
                //termDistrict: this.form.id_district,
                termDistrict: e,
              },
              {
                preserveState: true,
                replace: true,
              }
            );
          },
        getCreatedAt(data){
          var h = new Date(data).getHours();
          let buoi = h >= 12 ? 'Chiều' : 'Sáng';
          return buoi;
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
        formatPrice(value) {
          let val = (value/1).toFixed(0).replace(',', '.')
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        saveCustommer(e){
          this.form=e[0];
          this.edit
          ? this.form.put(route('custommers.update',{data:data,id:this.id_edit}),data)
          :this.form.post(route('storeLocal',{data:e[1]}));  
          this.save=true;
          //router.post('/storeLocal', e);
        },
        updateCustommer(data){
          // var exChange =''
          // if(this.currentExchangeFix){
          //   exChange=this.currentExchangeFix.exchange_usd
          // }
          // else{
          //   exChange=this.replaceString(this.getTransfer.Transfer.Transfer)
          // }
          // const data = [
          //   this.getServices,
          //  // exChange,
          //   this.formatPrice_1(this.tongtien)

          // ]
          this.form=data[0];
          this.form.post(route('updateBill',{data:data[1],id:this.id_edit}));
          //this.form.post(route('updateBill',{data:data,id:this.id_edit}));

          // router.post('admin/custommers', data, {
          //   forceFormData: true,
          // })
        },      
        handleUppercase(str){
         // var str = 'Sad';
	        var string = str.toUpperCase();
          return string;
        },
        formatPrice_1(value) {
          let val = (value/1).toFixed(0).replace('.', ',')
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      },
      provinceHandle(code){
          
        if(code){
          const fillData = this.districts.filter(function (el) {
            return el.id_province == code
          });
          this.getdistricts = fillData;
        }
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
      }
    }
