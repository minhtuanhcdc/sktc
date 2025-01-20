<template>
    <tr :class="classThead">
        <template v-for="(header, i) in headers" :key="i">
            <th scope="col" :class="header.class" class="py-1 border border-r px-1">
                {{header.name}}
            </th>
        </template>
       
    </tr>
</template>
<script>
    export default{
        props:{
            headers:'',
            classThead:''
        },
        data(){
            return{
            }
        },
    }
</script>