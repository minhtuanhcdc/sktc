    import '../../../css/menu.css'
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
    import InputErrorApp from '../../Components/InputError.vue'
    import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
    import { router } from '@inertiajs/vue3'
    import moment from 'moment';
    import Pagination from '../../Components/Pagination.vue';
    import PerPage from '../../Components/PerPage.vue'
    import Search from '../../Components/Search.vue'
    import { Head, Link, useForm } from '@inertiajs/vue3';
   
    export default{
        name:"Danh Mục",
        props:{
            catelogies:'',
            groups:'',
            fillters:'',
        },
        components:{
            AdminLayout,
            Head,
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
            InputErrorApp,
            ConfirmModalApp,
            Pagination,
            PerPage,
            Search             
        },
        data(){
            return{
                perPage:this.fillters.perPage,
                checkededit:true,
                id_edit:'',
                openModal:false,
                confirmModel:false,
                maxWidth:'xl',
                closeable:false,
                edit:false,
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    name:"",
                    medicine_name:"",
                    code:"",
                    id_group:"",
                    donvi_tinh:"",
                    don_gia:"",
                    origin:"",
                    status:1,
            },
            {
                resetOnSuccess:false,
            }),
            }
        },
        computed:{
          
            headers() {
                return [
                    { name: "#" },
                    { name: "Danh mục",class:'w-44'},
                    { name: "Mã" },
                    { name: "Đơn vị tính" },
                    { name: "Đơn giá" },
                ];
            },
            classTable(){
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return ''
            },
            classHeader(){
                return 'bg-blue-400 text-white text-center'
            },
            classRow(){
                return 'py-2 text-center bg-white border-b  dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-900'
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
        watch:{
            '$page.props.flash.success':function(value){
                if(value){
                    this.closeModal();
                }
            }
        },
        methods:{
            openEditCatelogy(catelogy){
                this.edit=true;
                this.id_edit=catelogy.id;
                this.form.code=catelogy.code
                this.form.name=catelogy.name
                this.form.origin=catelogy.origin
                this.form.medicine_name=catelogy.medicine_name
                this.form.id_group=catelogy.id_group
                this.form.don_gia=catelogy.don_gia
                this.form.donvi_tinh=catelogy.donvi_tinh
            
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
                this.edit=false;
            },
            closeConfirmModal(){
                this.confirmModel=false 
            },
            saveCatelogy(){
            
                this.edit
                ? this.form.put(route('catelogies.update',this.id_edit))
                : this.form.post(route('catelogies.store'));
                
                
            },
            deleteMenu(id){
               alert('Không được xóa');
                //this.form.delete(route('catelogies.destroy',id));
                this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                this.form.donvi_tinh='';
                this.form.don_gia='';
            
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            },
            handleSearch(e){
          
                this.$inertia.get(route('catelogies.index'),
                {  //search:this.search,
                  termSearch:e.termSearch,
    
                },
                {
                  preserveState:true,
                  replace:true            }
                )
            },
            handlePerPage(e){
          
                this.$inertia.get(route('catelogies.index'),
                {  //search:this.search,
                  perPage:e.perPage,
    
                },
                {
                  preserveState:true,
                  replace:true            }
                )
            },
            formatPrice_1(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
        },     
    }

