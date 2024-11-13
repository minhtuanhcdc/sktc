<template>
    <div  :class="classSearch.wrapClass">
        <label :class="classSearch.labelclass">{{ labelName?labelName:'' }} </label>
        <input v-model="termSearch" @keyup="search"  :class="classSearch.searchClass" :placeholder="contentSearch">
    </div>
</template>
<script>
    export default {
        props:{
            classSearch:'',
            labelName:'',
            contentSearch:'...Nhap hoten, mã định danh'
        },
        data(){
            return{
                termSearch:''
            }
        },
         methods:{
            search(){
                const data={
                    termSearch:this.termSearch
                }
            
                this.$emit('eventSearch',data);
            }
         }
    }
</script>