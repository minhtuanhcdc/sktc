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
   
    export default{
        name:"Danh Mục",
        props:{
            catelogies:'',
            groups:'',
            filters:'',
            posts:'',
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
            InputErrorApp,
            ConfirmModalApp,
            Pagination,
            PerPage,
            Search             
        },
        data(){
            return{
                perPage:this.filters.perPage,
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
                    address:'',
                    code:1,
                    phone:'',
                    file:'',

            },
            {
                resetOnSuccess:false,
            }),
            }
        },
        computed:{
            currentDate() {
                const current = new Date();
                this.dateNow = `${current.getDate()}/${current.getMonth()+1}/${current.getFullYear()}`;
              },
            headers() {
                return [
                    { name: "#" },
                    { name: "Mã" },
                    { name: "Tên bưu cục(Các Cơ sở" },
                    { name: "Địa chỉ" },
                    { name: "Phone" },
                    { name: "Date update" },
                    { name: "Mã BC tỉnh" },
                    { name: "Status" },
                    { name: "Action", class: "text-right" },
                ];
            },
            classTable(){
                return 'relative w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto'
            },
            classThead(){
                return 'text-center text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 text-blue-800'
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
            uploadFile() {
               // alert('Chưa cho phép!');
              
                if (this.$refs.fileupload) {
                    this.form.file = this.$refs.fileupload.files[0];
                }
                this.form.post(route('importPost'));
                //this.form.post(route('importProvince'));
                //this.form.post(route('importDistrict'));
                //this.form.post(route('importWard'));
                //this.form.post(route('provincePosts'));
                this.$refs.fileupload.value=null;
               
            },
    
            openEditPost(post){
                this.edit=true;
                this.id_edit=post.id;
                this.form.name=post.name
                this.form.code=post.code
                this.form.address=post.address
                this.form.phone=post.phone
            
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
            savePost(){
            
                this.edit
                ? this.form.put(route('posts.update',this.id_edit))
                : this.form.post(route('posts.store'));
                
                
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
          
                this.$inertia.get(route('posts.index'),
                {  //search:this.search,
                  term:e.termSearch,
    
                },
                {
                  preserveState:true,
                  replace:true            }
                )
            },
            handlePerPage(e){
                this.$inertia.get(route('posts.index'),
                {  //search:this.search,
                    perPage:e.perPage,
    
                },
                {
                  preserveState:true,
                  replace:true            }
                )
            },
          
        },     
    }

