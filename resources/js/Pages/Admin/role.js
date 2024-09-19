    import '../../../css/menu.css'
    import AdminLayout from '../../Layouts/AdminLayout.vue';
    import Table from '../../Components/Table/Table.vue';
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import Button from '../../Components/Button.vue'
    import { ShieldCheckIcon, XCircleIcon,CheckIcon } from '@heroicons/vue/24/solid';

    import ModalApp from '@/Components/Modal.vue'
    import LabelApp from '@/Components/InputLabel.vue'
    import ButtonApp from '@/Components/Button.vue'
    import ActionMessageApp from '@/Components/ActionMessage.vue'
    import Checkbox from '@/Components/Checkbox.vue'
    import TextInputApp from '@/Components/TextInput.vue'
    import InputErrorApp from '@/Components/InputError.vue'
    import ConfirmModalApp from '@/Components/ConfirmationModal.vue'
    import { router } from '@inertiajs/vue3'
    import moment from 'moment';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    
    export default{
        name:"Role",
        props:{
            roles:'',
            menus:'',
            errors:''
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
            ShieldCheckIcon,XCircleIcon,CheckIcon,
            ModalApp,
            LabelApp,
            ButtonApp,
            ActionMessageApp,
            Checkbox,
            TextInputApp,
            InputErrorApp,
            ConfirmModalApp,                      
        },
        data(){
            return{
                showAdd:false,
                checkededit:true,
                viewMenu:'',
                openModal:false,
                confirmModel:false,
                maxWidth:'4xl',
                closeable:false,
                edit:false,
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    name:"",
                    id:'',
                    id_parent:"",
                    id_menu:[],
                    show_:[],
                    add_:[],
                    edit_:[],
                    delete_:[],
                    status:1,
            },
            {
                resetOnSuccess:false,
            }),
            permissions:[ 
                {id:1, name:'Xem'},
                {id:2, name:'Thêm'},
                {id:3, name:'Sửa'},
                {id:4, name:'Xóa'},
            ]
            }
        },
        computed:{
            headers() {
                return [
                    { name: "#" },
                    { name: "Roles" },
                    { name: "Menus" },
                    { name: "Date update" },
                    { name: "Status" },
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
                return 'py-2 text-center bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'
            },
        },
        watch:{
            '$page.props.flash.success':function(value){
              
                if(value){
                    this.closeModal();
                }
            }
        },
        methods:{
          
            openEditRole(role){
               
                const result = role.permissions.map(a => a.id_menu);
               //console.log('hehehehe',result);
                this.form.id_menu=role.permissions.map(a => a.id_menu);
                this.form.show_=role.permissions.map(a => a.show_);
                this.form.add_=role.permissions.map(a => a.add_);
                this.form.edit_=role.permissions.map(a => a.edit_);
                this.form.delete_=role.permissions.map(a => a.delete_);

                this.viewMenu=role;
                this.form.id=role.id
                this.form.name=role.name
                this.openModalAdd();
            },
            openConfirm(role){
                this.viewMenu=role;
                this.confirmModel=true;
            },
            openModalAdd(){
             
                this.openModal=true
            },
            closeModal(){    
                    this.reset(); 
                    this.openModal=false; 
            },
            closeConfirmModal(){
                this.confirmModel=false 
            },
            saveRole(){
                this.form.post(route('roles.store'));
               
                setTimeout(() => {
                    this.closeModal();   
                }, 1000);
            },
            savePermission(){
               
                const data = 
                {
                    'id_menu':this.form.menu_id,
                    'id_permission':this.form.id_permission,
                }
                this.form.put(route('roles.update',this.viewMenu.id))
            },
            deleteRole(id){
               
                this.form.delete(route('roles.destroy',id));
                this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            }
        },
          
    }