<template>
    <AdminLayout>
      <Head title="Tỉ giá" />
        <div class=" w-full  object-fix justify-center ">
           <!-- <div class="flex space-x-2">
                <span>Tỉ giá Quốc tế:</span>
               <p class="text-red-700 font-bold">{{ currencyWolrd.quotes }}</p>
           </div>
           <hr> -->
           <div class="mt-1 flex justify-between w-2/3 m-auto">
            <span> Bảng tỉ giá <span class="text-blue-800 font-bold">VietComBank(VCB):</span></span>
            <span> Tỉ giá USD: <span class="text-blue-800 font-bold">{{ transfer_race.Transfer.Transfer }}(VNĐ)</span></span>
           
            <span class="ml-4 text-red-700 text-lg font-bold"> Ngày: {{ currencyVietcomBank.DateTime }}</span>
            </div>
        <div class="flex flex-col">
  <div class="-m-1.5 overflow-x-auto">
    <div class="p-1.5 min-w-full inline-block align-middle">
      <div class="border rounded-lg shadow overflow-hidden dark:border-gray-700 dark:shadow-gray-900 w-2/3 m-auto" >
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 m-auto">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr class="bg-blue-600 text-white" >
             
              <th  class="px-6 py-3 text-center text-xs font-medium  uppercase ">Tên Ngoại tệ</th>
              <th  class="px-6 py-3 text-center text-xs font-medium uppercase ">Mã tiền tệ</th>
              <th  class="py-3 px-1 text-right text-xs font-medium  uppercase ">Mua vào</th>
              <th  class="py-3 px-1 text-right text-xs font-medium  uppercase bg-red-500">Chuyển khoản</th>
              <th  class="py-3 px-1 text-right text-xs font-medium  uppercase ">Bán ra</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
             <tr v-for="(value,i) in currencyVietcomBank.Exrate" :key="i">
                <template v-for="(v,i) in value" :key="i">
                    <td class="py-1"  >
                        <span :class="v.CurrencyCode=='USD'?'font-bold text-blue-800 text-sm':''" >{{ v.CurrencyName }}</span>
                    </td>
                    <td class=" text-sm text-center"  >
                        <span :class="v.CurrencyCode=='USD'?'font-bold text-blue-800 text-sm':''"> 
                        {{ v.CurrencyCode }}
                        </span>
                    </td>
                    <td class="text-right px-1">
                        <span :class="v.CurrencyCode=='USD'?'font-bold text-blue-800 text-sm':''" >{{ v.Buy }}</span>
                        
                    </td>
                    <td class="text-right px-1">
                        <span :class="v.CurrencyCode=='USD'?'font-bold text-blue-800 text-sm':''" >{{ v.Transfer }}</span>
                        
                    </td>
                    <td class="text-right px-1">
                        <span :class="v.CurrencyCode=='USD'?'font-bold text-blue-800 text-sm':''" >{{ v.Sell }}</span>
                    </td>     
                </template>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
        </div>
    </AdminLayout>
</template>

<script>

   import AdminLayout from '../../Layouts/AdminLayout.vue';
   import { Head, Link, useForm } from '@inertiajs/vue3';
    import { BeakerIcon,ChevronRightIcon,ChevronLeftIcon,ListBulletIcon,ShieldCheckIcon,UsersIcon,UserGroupIcon } from '@heroicons/vue/24/solid'
    export default{
      name:'Home',
      props:{
            currencyWolrd:'',
            currencyVietcomBank:'',
            transfer_race:'',
        },
        components:{
            AdminLayout,
            Head  
        },
        data(){
            return{
                tong:''
            }
        },
        computed: {
       
    },
        methods:{
          Tinhtong(){
            const b=2;
            let transfer = this.transfer_race.Transfer.Transfer
            const number = transfer.replace(/\,/g,'');
            //const formatNum = Intl.NumberFormat().format(number,4)
            console.log('Hehehehehheheh',number)
            this.tong = number*4;
          },
          formatNumber (num) {
            return parseFloat(num).toFixed(2)
          },

          formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }
        }
    }
</script>