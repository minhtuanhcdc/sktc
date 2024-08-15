<template>
     <div class="h-7 w-44 flex">
        <label>perPage</label>
        <select
            name="perPage"
            id="perPage"
            class="block py-0 w-24 form-input h-8 rounded-lg"
            v-model="perPage" @change="handlePage">
                <template v-for="i in pagesFill" :key="i.index">
                    <option :value="i">{{ i }}</option>
                </template>
                
        </select> 
    </div>
</template>
<script>
    export default{
        props:{
            filtePerpage:'',
        },
        data(){
            return{
                pagesFill:[1,2,5,10,20,30,50,100,200,300,400,500],
                perPage:this.filtePerpage
            }
        },
        methods:{
            handlePage(){
                const data={
                    perPage:this.perPage,
                    
                }
                
                this.$emit('handlePageEvent',data)
            }
        }
    }
</script>