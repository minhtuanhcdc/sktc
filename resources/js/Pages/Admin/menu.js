
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
    // import InputErrorApp from '@/Components/InputError'
    import ConfirmModalApp from '../../Components/ConfirmationModal.vue'
    import { router } from '@inertiajs/vue3'
    import moment from 'moment';
    import { Head, Link, useForm } from '@inertiajs/vue3';


    export default{
        name:"Menu",
        props:{
            menus:''
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
            // InputErrorApp,
            ConfirmModalApp
                 
            
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
                groups:[1,2,3,4,5,6,7,8,9,10],
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    name:"",
                    id_parent:"",
                    url:"",
                    icon:"",
                    menu_group:"",
                    status:"",
                
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
                    { name: "Tên menu" },
                    { name: "Menu cha" },
                    { name: "Route(Url)" },
                    { name: "Hero Icon" },
                    { name: "Group" },
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
        methods:{
            openEditMenu(menu){
                this.edit=true;
                this.viewMenu=menu;
                this.form.name=menu.name
                this.form.id_parent=menu.id_parent
                this.form.url=menu.url
                this.form.icon=menu.icon
              
                this.form.status=this.checkededit
                this.form.menu_group=menu.menu_group
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
            },
            saveMenu(){
            
                this.edit
                ? this.form.put(route('menus.update',this.viewMenu.id,this.viewMenu))
                : this.form.post(route('menus.store'));
                
                this.closeModal();
            },
            deleteMenu(id){
               
                this.form.delete(route('menus.destroy',id));
                this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                this.form.url='';
                this.form.icon='';
                this.form.status='';
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            }
        },
          
    }

