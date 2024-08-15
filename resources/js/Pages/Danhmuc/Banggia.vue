<template>
    <AdminLayout>
        <Head title="Bang giá" />
        <div class="mt-1 w-full object-fix justify-center">
            <div class="flex justify-between mt-2 mx-8">
                <span class="text-blue-900 font-bold">Bảng giá</span>
                <PerPage v-on:handlePageEvent="handlePerPage" :filtePerpage="perPage" />
            </div>
            <div class=" h-[95%] overflow-x-auto shadow-md sm:rounded-lg mt-2 flex justify-center">
                <div class="flex  h-screen justify-center w-[80%]"> 
                    <div class="flex-grow overflow-auto px-10 justify-center">
                        <Table :classTable="classTable" :classThead="classThead">
                            <template #header>
                                <TableHeader :headers="headers" :classHeader="classHeader"/>
                            </template>    
                            <template #tbody>
                                 <TableRow :classRow="classRow" v-for="(catelogy,i) in catelogies.data">
                                    <Tbody>{{ i+1 }}</Tbody>
                                    <Tbody class="text-left w-1/3">{{ catelogy.name }}</Tbody>
                                    <Tbody class="text-left pl-4">{{ catelogy.code }}</Tbody>
                                    <Tbody class="text-left pl-4" >{{ catelogy.donvi_tinh }}</Tbody>
                                    <Tbody class="text-center">{{ formatPrice_1(catelogy.don_gia) }}</Tbody>
                                    
                                </TableRow> 
                            
                            </template>
                        </Table>
                        <div class="flex mt-2 bg-blue-500 items-center py-0 h-8">
                            <!-- <Pagination :links="catelogies.links"/> -->
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
       
    </AdminLayout>
</template>

<script src="./banggia"></script>