
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
    import { Head, Link, useForm } from '@inertiajs/vue3';

    export default{
        name:"LengthForAge Boy",
        props:{
            lengthforages:''
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
            ConfirmModalApp,
            Pagination
                 
            
        },
        data(){
            return{
                checkededit:true,
                id_edit:'',
                openModal:false,
                confirmModel:false,
                maxWidth:'xl',
                closeable:false,
                edit:false,
                formFile: this.$inertia.form({
                    file:''
                }),
                form: this.$inertia.form({
                    "_method": this.edit ? 'PUT' : "",
                    month:"",
                    L:'',
                    M:'',
                    S:'',
                    SD:'',
                    neg3SD:'',
                    neg2SD:'',
                    neg1SD:'',
                    median:'',
                    mot_SD:'',
                    hai_SD:'',
                    ba_SD:'',
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
                   
                    { name: "Năm: Tháng", class:'w-[8%] text-center border border-r-black border-black'},
                    { name: "Tháng" , class:'w-[3%] text-center border border-r-black border-black'},
                    { name: "L" , class:'w-[3%] text-center border border-r-black border-black'},
                    { name: "M" , class:'w-[5%] text-center border border-r-black border-black'},
                    { name: "S" , class:'w-[5%] text-center border border-r-black border-black'},
                    { name: "SD" , class:'w-[5%] text-center border border-r-black border-black'},
                    { name: "-3 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "-2 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "-1 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "Median", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "1 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "2 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "3 SD", class:'w-[5%] text-center border border-r-black border-black' },
                    { name: "Action", class: "w-[15%] text-right" },
                ];
            },
            classTable(){
                return 'w-full text-sm text-left text-gray-500 dark:text-gray-400'
            },
            classThead(){
                return 'text-center text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'
            },
            classRow(){
                return 'py-2 text-gray-900 text-left bg-white border-b border-r-2 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-800'
            },
        },
        methods:{
            openEditMenu(catelory){
                this.edit=true;
                this.id_edit=catelory.id;

                this.form.name=catelory.name
                this.form.content=catelory.content
              
                this.openModalAdd();
            },
            openConfirm(menu){
                this.viewMenu=menu;
                this.confirmModel=true;
            },
            openForm(){
                this.openModal=true
            },
            closeForm(){
                this.openModal=false
                this.reset();
            },
            closeConfirmModal(){
                this.confirmModel=false 
            },
            save(){
               
                this.edit
                ? this.form.put(route('lenghtageboy.update',this.id_edit))
                : this.form.post(route('lenghtageboy.store'));
                
                this.closeModal();
            },
            deleteMenu(id){
               alert('Không được xóa');
                // this.form.delete(route('catelogy_groups.destroy',id));
                // this.closeConfirmModal();
            },
            reset(){
                this.form.name='';
                this.form.url='';
                this.form.icon='';
            },
            formattedDate(date) {
                return moment(date).format("DD/MM/YYYY")
            },
            changeYear(months){

                return Math.floor(months / 12) + ":" + months % 12;
                
            },
            getMonth(data){
                return (data % 12);
            },
            uploadFile() {
                
                if (this.$refs.fileupload) {
                    this.formFile.file = this.$refs.fileupload.files[0];
                }
                this.formFile.post(route('importLenghtForAgeBoy'));
              
                this.$refs.fileupload.value=null;
               
            },
        },
          
    }

