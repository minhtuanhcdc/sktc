<template>
    <tr :class="classThead">
        <template v-for="(header, i) in headers" :key="i">
            <th scope="col" :class="header.class">
                {{header.name}}
            </th>
        </template>
       
    </tr>
</template>
<script>
    export default{
        props:{
            headers:'',
            classThead:'w-[100%]'
        },
        data(){
            return{
            }
        },
    }
</script>