<template>
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
                        <!-- <InputErrorApp :message="form.errors.name" class="text-center" />  -->
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
                        <!-- <InputErrorApp :message="form.errors.parent" class="text-center" /> -->
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
                    <div class="w-[20%] flex items-center">
                        <label class=" pr-1 text-blue-900 font-bold">Địa chỉ:</label>
                        <input v-model="form.address" id="address" type="text" class="text-sm border border-blue-700 flex-1 h-7 rounded-md p-1"  autocomplete="address" />
                    </div> 
                    <div class="w-[18%] flex items-center">
                        <label class="text-blue-900 font-bold">Tỉnh/thành</label>
                        <select   v-model="form.id_province" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                            <option value="">-</option>
                            <option v-for="(pce, i) in provinces" :key="i" :value="pce.code">
                                <span>  {{pce.name}}</span>
                            </option>
                        </select>
                    </div>
                    <div class="w-[20%] flex items-center">
                        <label class="text-blue-900 font-bold">Quận/huyện</label>
                        <select v-model="form.id_district" class="h-7 py-0 w-full rounded-lg border border-blue-900 text-sm">
                            <option value=""></option>
                            <option v-for="(d,i) in getdistricts" :key="i" :value="d.code">{{ d.name }}</option>
                        </select>
                    </div>
                    <div class="w-[20%] flex items-center">
                        <label class="text-blue-900 font-bold">Phường/xã</label>
                        <select  v-model="form.id_ward" class="h-7 py-0 w-full rounded-lg border border-blue-900">
                            <option value="">-</option>
                            <option v-for="(w,i) in wards" :key="i" :value="w.code">{{w.name}}</option>
                        </select>
                    </div>   
                    <div class="w-[20%] flex space-x-4">
                        <div class="flex items-center">
                            <label class="font-bold text-blue-900 text-sm">Cân nặng lúc sinh(kg):</label>
                            <input class="h-7 rounded-sm border w-8 p-1" v-model="form.weightbirth"/>
                        </div>
                        <div class="flex space-x-1 items-center">    
                            <span class="text-xs font-bold w-16">Tháng tuổi:</span>
                            <span class="font-bold ml-2 w-8 ">{{month_birth }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex border border-hcdc1 mt-2 bg-gray-100">
                   
                    <div class="w-1/2 p-2 flex">
                            <div class="w-[50%]">
                                <span class="w-[100%] h-7 font-bold">Khám ĐK: </span>
                                <div  v-for="(ng,i) in nDay" :key="i" class="">
                                    <input type="date" class="w-[50%] h-7 rounded-sm " v-model="form.khamDinhKy[i]"/> 
                                </div>
                                <div class="flex justify-between w-[40%] px-2">
                                    <span class=" text-md font-bold w-[50%]" @click ="minusDay">
                                        <span class="cursor-pointer p-2"> - </span> 
                                    </span>
                                    <span class="cursor-pointer text-md font-bold w-[50%] text-right" @click="addDay">+</span>
                                </div>
                            </div>
                            <div v-if="month_birth>=6 &&  month_birth<=36" class="w-[50%]">
                                <span class="w-[100%] h-7 font-bold">Vitamin: </span>
                                <div  v-for="(ngV,i) in vDay" :key="i" class="">
                                    <input type="date" class="w-[50%] h-7 rounded-sm " v-model="form.ngay_uong[i]"/> 
                                </div>
                                <div class="flex justify-between w-[40%] px-2">
                                    <span class=" text-lg font-bold w-[50%]" @click ="vMinusDay">
                                        <span class="cursor-pointer p-2 "> - </span> 
                                    </span>
                                    <span class="cursor-pointer text-lg font-bold w-[50%] text-right" @click="vAddDay">+</span>
                                </div>
                            </div>
                    </div>
                    
                    <div class="mt-2 w-1/2">
                        <div class="flex w-full justify-center font-bold">
                            <span class="w-1/3 ">Ngày cân đo</span>
                            <span class="w-1/3 pl-5">Cân nặng(kg)</span>
                            <span class="w-1/3 pl-5">Chiều cao(cm)</span>
                        </div>
                        <div class="flex justify-between px-0 items-center w-full" v-for="(n,i) in nDayParam" :key="i">
                            <div class="w-[35%]">
                                <input type="date" class="h-7 text-center" v-model="form.input_date[i]"/>
                            </div>
                           
                            <div class="w-[30%]  justify-center">
                                <input type="text" class="w-[40%] h-7 p-0 text-center" v-model="form.weigth[i]"/>
                                <InputErrorApp :message="form.errors.weigth" class="text-center" />
                            </div>
                          
                            <div class="w-[30%]">
                                <input type="text" class="w-[40%] h-7 p-0 text-center" v-model="form.length[i]"/>
                                <InputErrorApp :message="form.errors.length" class="text-center" /> 
                            </div> 
                          
                        </div>
                        <div class="flex justify-between w-[80%] px-2 text-center pl-5">
                            <span class=" text-lg font-bold w-14 cursor-pointer" @click ="minusDayParam">-</span>
                            <span class="cursor-pointer text-lg font-bold w-14" @click="addDayParam">+</span>
                        </div>
                    </div>
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
  
</template>
<script>
import {ChevronDoubleDownIcon,ChevronDoubleRightIcon} from '@heroicons/vue/24/solid'
import InputErrorApp from '../../Components/InputError.vue'
    export default{
        props:{
          showAdd:'',
          provinces:'',
          districts:'',
          wards:'',
        },
        components:{
            InputErrorApp,
            ChevronDoubleDownIcon,ChevronDoubleRightIcon
        },
        data(){
            return{
                nDayParam:1,
                nDay:1,
                vDay:1,
                month_date:this.month_birth,
                bmi:'',
                valueWeight:'',
                valueLength:'',
                month_birth:'',
                getdistricts:'',
                showService:'',
                gt:false,
                form: this.$inertia.form({
                    madinhdanh:'',
                    matiemchung:'',
                    weightbirth:'',
                    weigth:[],
                    length:[],
                    input_date:[],
                    name:'',
                    birthday:'',
                    sex:'',
                    parent:"",
                    email:'',
                    phone:'',
                    address:'',
                    id_province:'',
                    id_district:'',
                    id_ward:'',
                    khamDinhKy:[] ,
                    ngay_uong:[],        
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
                    this.reset();
                }
              
            },
           'form.weigth':function(value){
                this.valueWeight=value
           },
           'form.lenght':function(value){
                    this.valueLength=value
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
          "form.birthday":function(value){
          //alert('change');
            if (!value) return 0;
            const birthDate = new Date(value);
            const today = new Date();
            let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
            months -= birthDate.getMonth() + 1;
            months += today.getMonth() + 1;
            this.month_birth=months <= 0 ? 0 : months; 
          },
         
        },
        computed:{
            classError(){
                return 'text-red-700 text-xl text-center'
            },
            handleBMI(){
             
                 if(this.form.length && this.form.weigth){
                    this.bmi=this.formatPrice_1((this.form.weigth*10000)/(this.form.length*this.form.length));
                     return this.bmi;
                 }
                 return ""
            },
            
        },
        methods:{
            addDayParam() {
            this.nDayParam++;
            this.form.input_date.push(''); 
            this.form.length.push(''); 
            this.form.weigth.push(''); 
            },
            minusDayParam() {
                if (this.form.input_date.length > 0) {
                    this.nDayParam--;
                    this.form.input_date.pop();
                    this.form.length.pop();
                    this.form.weigth.pop();
                    
                }
            },
            addDay() {
            this.nDay++;
            this.form.khamDinhKy.push(''); 
            },
            minusDay() {
                if (this.form.khamDinhKy.length > 0) {
                    this.nDay--;
                    this.form.khamDinhKy.pop(); // Xóa ngày cuối cùng
                    
                }
            },
            updateDates(ngay_kham) {
                this.form.khamDinhKy = ngay_kham; // Cập nhật mảng ngày khám
                this.nDay = ngay_kham.length; // Cập nhật số ngày
            },
            vAddDay(){
                this.vDay++
            },
            vMinusDay(){
                this.vDay--
            },
            reset(){
                this.edit=false
                this.form.name = ''
                this.form.phone = ''
                this.form.email = ''
              
                this.form.address = ''
                this.form.id_province = ''
               
                this.confirmDelete=false;
                this.form.id_province=''
                this.form.id_district=''
                this.form.id_ward=''
            },
            saveParamEmit(e){
                console.log('Current khamDinhKy:', this.form.khamDinhKy);
                var data=[this.form,this.month_birth];
                console.log(data);
                this.$emit('saveParamEmit',data)
            },
            formatPrice_1(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            fillService(value){
                this.getServices=this.catelogies.filter(el => value.includes(el.id))
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
            closeForm(){
                this.$emit('closeFormEvent')
            }
        },
        
    }
</script>