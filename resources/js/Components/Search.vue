<template>
    <div  :class="classSearch.wrapClass">
        <label :class="classSearch.labelclass">Search </label>
        <input v-model="termSearch" @keyup="search"  :class="classSearch.searchClass"/>
    </div>
</template>
<script>
    export default {
        props:{
            classSearch:''
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