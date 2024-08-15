<template>
    <tr :class="classRow" >
        <slot :class="classTd"></slot>
    </tr>
</template>
<script>
  
    export default{
        props:{
            classRow:'',
            classTd:''
        },
      
        data(){
            return{
                
            }
        },
    
    }
</script>