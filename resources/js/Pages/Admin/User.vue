<template>
    <AdminLayout>
        <Head title="Users" />
        <div class="mt-1 w-full object-fix justify-center">
          <div class="flex justify-between py-0 px-4">
            <span>User</span>
            <ButtonApp class="button_add bg-blue-500" @click="openModalAdd">Add+</ButtonApp>
          </div>
           <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
                <Table :classTable="classTable" :classThead="classThead">
                    <template #header>
                        <TableHeader :headers="headers" :classHeader="classHeader"/>
                    </template>    
                    <template #tbody>
                        <TableRow :classRow="classRow" v-for="(user,i) in users.data " :key="i">
                            <Tbody class="text-center">{{ i+1 }}</Tbody>
                            <Tbody>{{ user.name }}</Tbody>
                            <Tbody>{{ user.username }}</Tbody>
                            <Tbody>
                                <!-- <span v-for="(role,i) in user.roles" :key="i" class="ml-2 bg-blue-500 px-1 rounded-lg text-white"> -->
                                <span v-if="user.role">{{user.role.name}}</span>
                                <!-- </span> -->
                            </Tbody>
                            <Tbody>
                                <div class="flex font-bold">
                                    <span v-if="user.ward">{{ user.ward.name }}, </span>
                                    <span v-if="user.district">
                                       &nbsp; {{ user.district.name }}
                                    </span>
                                </div>
                               
                            </Tbody>
                            <Tbody>{{ formattedDate(user.updated_at) }}</Tbody>
                            <Tbody class="flex justify-center">
                                <span v-if="user.status == 1" class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-blue-700"/>
                                </span>
                                <span v-else  class="flex justify-center">
                                    <CheckIcon class="w-6 h-6 text-gray-200"/>
                                </span>
                            </Tbody>
                            <Tbody> 
                                <div class="flex space-x-6 justify-center w-full z-10">
                                    <span class="tooltip_edit123 z-50" data-tip="Sửa" v-if="$page.props.can.edit">
                                        <PencilIcon class="classPencil z-0" @click="openEditUser(user)" />
                                    </span> 
                                    <span title="Xóa" v-if="$page.props.can.delete">
                                        <XCircleIcon class="classXIcon" @click="openConfirm(user)" /> 
                                    </span>
                                </div>
                            </Tbody>
                        </TableRow>
                    </template>
                </Table>
           </div>
           <div class="flex">
              <Pagination :links="users.links"/>
          </div>
        </div>
        <ModalApp :show="openModal" :maxWidth="maxWidth">
            <div class="flex justify-between py-1 px-4">
                <span v-if="edit">Cập nhật user</span>
                <span v-else>Thêm user</span>
                <ButtonApp  @click="closeModal" class="button_close bg-blue-600">Close</ButtonApp>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="saveUser">
                <!--Name--->
                    <div class="">
                         <label for="name" class="classLabel">Tên User</label>
                        <TextInputApp id="name" type="text" class="inputText border border-blue-700" v-model="form.name" autocomplete="name" />
                        <InputErrorApp :message="form.errors.name" class="mt-2" /> 
                    </div> 
                    <div class="">
                         <label for="email" class="classLabel">Email</label>
                        <TextInputApp id="email" type="text" class="inputText border border-blue-700" v-model="form.email" autocomplete="email" />
                        <InputErrorApp :message="form.errors.email" class="mt-2" /> 
                    </div> 
                    <div class="">
                         <label for="username" class="classLabel">Username</label>
                        <TextInputApp id="username" type="text" class="inputText border border-blue-700" v-model="form.username" autocomplete="username" />
                        <InputErrorApp :message="form.errors.username" class="mt-2" />
                    </div> 
                    <div class=" mt-4 w-full">
                         <label for="role" class="classLabel mr-1">Q/H</label>
                       <select v-model="form.id_district" class="px-2 w-96 h-7 p-0 rounded-lg">
                            <option value="">--</option>
                            <span v-for="(d,i) in districts" :key="i">
                                <option :value="d.code" >{{ d.name }}</option>
                            </span>
                       </select>
                    </div> 
                    <div class=" mt-4 w-full">
                         <label for="role" class="classLabel mr-1">P/X</label>
                       <select v-model="form.id_ward" class="px-2 w-96 h-7 p-0 rounded-lg">
                            <option value="">--</option>
                             <span v-for="(w,i) in wards" :key="i">
                                <option :value="w.code" >{{ w.name }}</option>
                            </span> 
                       </select>
                    </div> 
                    <div class=" mt-4 w-1/2">
                         <label for="role" class="classLabel mr-1">Role</label>
                       <select v-model="form.id_role" class="px-2 w-56 h-7 p-0 rounded-xl">
                            <option value="">--</option>
                            <span v-for="(r,i) in roles" :key="i">
                                <option :value="r.id">{{ r.name }}</option>
                            </span>
                       </select>
                    </div> 
                   <div class="mt-4">
                        <Checkbox :checked="checkededit" v-model="form.status" class="border-2 border-blue-600"/><span class="ml-2">Hiển thị</span> 
                    </div>   
                   <!--Action--->
                    <div class="text-center">
                        <ActionMessageApp :on="form.recentlySuccessful" class="mr-3">
                            <span v-if="edit">Updated.</span>
                            <span v-else >Saved.</span>                    
                        </ActionMessageApp>
                        <ButtonApp type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="button_save bg-blue-700">
                            <span v-if="edit">Update</span>
                            <span v-else >Save</span>
                        </ButtonApp>
                    </div>
                </form>   
            </div>  
        </ModalApp>
        <ConfirmModalApp :show="confirmModel">
            <template #title class="w-full flex justify-end">
                <span @click="closeConfirmModal" class="px-4 py-1 cursor-pointer bg-blue-600 text-white rounded-sm">Close</span>
            </template>
            <template #content>
                <div class="flex justify-between w-full">
                    <span>Bạn chắc xóa:
                    <span class="font-bold pl-2 underline text-red-600 pr-1">{{viewMenu.name}}</span> ? </span>
                </div>
            </template>
            <template #footer class="text-center">
                <button class="bg-red-600 text-white px-3 py-1 rounded-lg" @click="deleteUser(viewMenu.id)">Delete</button>
            </template>
        </ConfirmModalApp>
    </AdminLayout>
</template>

<script src="./user"></script>