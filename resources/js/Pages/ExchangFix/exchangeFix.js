
    import '../../../css/catelogy.css'
    import AdminLayout from '../../Layouts/AdminLayout.vue';
    import Table from '../../Components/Table/Table.vue';
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import Button from '../../Components/Button.vue'
    import { PencilIcon, XCircleIcon,CheckIcon } from '@heroicons/vue/24/solid';

    import ModalApp from '../../Components/Modal.vue'
    import LabelApp from '../../Components/InputLabel.vue'
    import ButtonApp from '../../Components/Button.vue'
    import ActionMessageApp from '../../Components/ActionMessage.vue'
    import Checkbox from '../../Components/Checkbox.vue'
    import TextInputApp from '@/Components/TextInput.vue'
    // import InputErrorApp from '@/Components/InputError'
    import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
    import { router } from '@inertiajs/vue3'
    import moment from 'moment';
    import Pagination from '../../Components/Pagination.vue'


    export default{
        name:"Menu",
        props:{
            exchanges:''
        },
        components:{
            AdminLayout,
            Table,
            Tbody,
            TableRow,
            Tbody,
            TableHeader,
            Button,
            PencilIcon,XCircleIcon,CheckIcon,
            ModalApp,
            LabelApp,
            ButtonApp,
            ActionMessageApp,
            Checkbox,
            TextInputApp,
            // InputErrorApp,
            ConfirmModalApp,
            Pagination
                 
            
        },
        data(){
            return{
                disable_Exchange:false,
                id_exchange:'',
                Question:'',
                checkededit:true,
                id_edit:'',
                openModal:false,
                confirmModel:false,
                maxWidth:'xl',
                closeable:false,
                edit:false,
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    exchange_usd:"",
                    status:'',
            },
            {
                resetOnSuccess:false,
            }),
            }
        },
        computed:{
            headers() {
                return [
                    { name: "#", class:'w-10 text-center'},
                    { name: "Tỉ giá USD", class:'text-center' },
                    { name: "Ngày tạo",class:'text-center' },
                    { name: "User tạo", class:'text-center' },
                    { name: "Action", class: "text-right" },
                ];
            },
            classTable(){
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'
            },
            classRow(){
                return 'py-2 text-left bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-800'
            },
        },
        
        methods:{
            handleClock(id){
                this.confirmModel=true;
                this.Question="Bạn chắc Disable "
                this.id_exchange=id;
            },
            openModalLock(){
                this.confirmModel=true;
                this.disable_Exchange=true;
                this.Question="Bạn chắc khóa tỉ giá không"
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
            openEditMenu(catelory){
                this.edit=true;
                this.id_edit=catelory.id;

                this.form.name=catelory.name
              
                this.openModalAdd();
            },
            openConfirm(menu){
                this.viewMenu=menu;
                this.confirmModel=true;
            },
            openModalAdd(){
             
                this.openModal=true
            },
            closeModal(){
                this.openModal=false
                this.reset();
            },
            closeConfirmModal(){
                this.confirmModel=false 
                this.disable_Exchange=false 
                this.Question=''
            },
            saveExchange(){
                this.edit
                ? this.form.put(route('exchanges.update',this.id_edit))
                : this.form.post(route('exchanges.store'));
                this.closeModal();
            },
            disableExchange(id){
             
                this.form.put(route('exchanges.update',id));
                // this.closeConfirmModal();
            },
        
            clockExchange(){
                alert(123)
                this.$inertia.get(route('clockExchange'));
                // this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                this.form.url='';
                this.form.icon='';
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY (hh:ss)")
            }
        },
          
    }

