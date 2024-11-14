<template>
    <table :class="classTable"> 
        <thead :class="headers">
            <slot name="header"></slot>
        </thead>
        <tbody class="">
            <slot name="tbody"> </slot>
        </tbody>
    </table>
</template>
<script>
  
    export default{
        props:{
            classTable:'',
            headers:''
        },
      
        data(){
            return{
                
            }
        },
    
    }
</script>