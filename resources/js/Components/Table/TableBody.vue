<template>
    <td class="py-1 z-40 border-b border-gray-500 " :class="classRow">
        <slot></slot>
    </td>
</template>
<script>
  
    export default{
        props:{
            classRow:''
        },
      
        data(){
            return{
                
            }
        },
    
    }
</script>