
    import '../../../css/menu.css'
    import AdminLayout from '../../Layouts/AdminLayout.vue';
    import Table from '../../Components/Table/Table.vue';
    import TableHeader from '../../Components/Table/TableHeaders.vue'
    import TableRow from '../../Components/Table/TableRow.vue';
    import Tbody from '../../Components/Table/TableBody.vue';
    import Button from '../../Components/Button.vue'
    import { PencilIcon, XCircleIcon,CheckIcon } from '@heroicons/vue/24/solid';
    import InputErrorApp from '../../Components/InputError.vue'
    import ModalApp from '../../Components/Modal.vue'
    import LabelApp from '../../Components/InputLabel.vue'
    import ButtonApp from '../../Components/Button.vue'
    import ActionMessageApp from '../../Components/ActionMessage.vue'
    import Checkbox from '../../Components/Checkbox.vue'
    import TextInputApp from '@/Components/TextInput.vue'
    
    import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
    import { router } from '@inertiajs/vue3'
    import moment from 'moment';
    import Pagination from '../../Components/Pagination.vue'
    import { Head, Link, useForm } from '@inertiajs/vue3';

    export default{
        name:"User",
        props:{
            users:'',
            role:'',
            roles:'',
            districts:'',
            wards:''
        },
        components:{
            AdminLayout,
            Head,
            Table,
            Tbody,
            TableRow,
            Tbody,
            TableHeader,
            Pagination,
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
        },
        data(){
            return{
                checkededit:true,
                viewMenu:'',
                openModal:false,
                confirmModel:false,
                maxWidth:'xl',
                closeable:false,
                edit:false,
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    name:"",
                    email:"",
                    username:"",
                    id_ward:"",
                    id_district:"",
                    id_role:"",
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
                    { name: "Tên" },
                    { name: "User" },
                    { name: "Role" },
                    { name: "Trung tâm" },
                    { name: "Date update" },
                    { name: "Status" },
                    { name: "Action", class: "text-right" },
                ];
            },
            classTable(){ 
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-gray-700 uppercase bg-blue-600 dark:text-gray-400 bg-blue-900'
            },
            classHeader(){
                return 'text-center text-xs text-white uppercase bg-blue-600'
            },
            classRow(){
                return 'py-2 text-black bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'
            },
        },
        watch:{
            '$page.props.flash.success':function(value){
                if(value){
                    this.closeModal();
                }
            },
            'form.id_district':function(value){
                this.districtHandle(value);
              },
        },
        methods:{
            openEditUser(user){
                //this.form = Object.assign({}, user);
                // const rolesuser = user.roles.map(function(a) {
                //     return a.id;
                //     });
               
                this.edit=true;
                this.viewMenu=user;
                this.form.name=user.name
                this.form.username=user.username
                this.form.email=user.email
                if(user.ward){
                    this.form.id_ward=user.ward.code
                }
                if(user.role){
                    this.form.id_role=user.role.id
                }
            //     this.roles.filter(function (el) {
            //     return el.id == user.id_role;
            // });

          
              
                this.openModalAdd();
            },
            openConfirm(user){
                this.viewMenu=user;
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
            saveUser(){
                this.edit
                ? this.form.put(route('users.update',this.viewMenu.id,this.viewMenu))
                : this.form.post(route('users.store'));
               
            },
            deleteUser(id){
               
                this.form.delete(route('users.destroy',id));
                this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                this.form.username='';
                this.form.email='';
                this.form.id_post='';
                this.form.id_role='';
              
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            },
            districtHandle(value){
                this.save=false;
                  this.$inertia.get(route('users.index'),
                    {
                      // bn_code:this.custommer_id,
                      // perPage: this.perPage,
                      // ousentFill: this.ousentFill,
                      // readcodeFill: this.readcodeFill,
                      // startDate: this.startDate,
                      // endDate: this.endDate,
                      //termDistrict: this.form.id_district,
                      termDistrict: value,
                    },
                    {
                      preserveState: true,
                      replace: true,
                    }
                  );
                },
        },
          
    }

