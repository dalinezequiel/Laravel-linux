<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

import { getCurrentInstance } from "vue";
const app = getCurrentInstance();
const $page = app.appContext.config.globalProperties.$page;

const form = useForm({
    __method: 'PUT',
    firstname: $page.props.customer.firstname,
    surname: $page.props.customer.surname,
    email: $page.props.customer.email,
    birthday: $page.props.customer.birthday,
    status: $page.props.customer.status,
});

const submitCustomers = () => {
    form.put(route('customers.update', $page.props.customer.id), {
    });
};

</script>
<template>
    <div id="container" class="">
        <div class="customer">

            <div v-if="$page.props.errors.error" class="bg-red-100 text-red-600 px-4 py-8">
                {{ $page.props.errors.error }}
            </div>
            <h2 class="text-[25px] font-bold">Customer</h2>
            <div class="px-400">
                <form method="post" @submit.prevent="submitCustomers">
                    <div class="flex items-center py-4 pb-4">
                        <div class="flex pr-2 flex-col w-full">
                            <label for="">First name</label>
                            <input type="text" v-model="form.firstname" class="rounded border-gray-300">
                            <InputError class="mt-2" :message="form.errors.firstname" />
                        </div>
                        <div class="flex pl-2 flex-col w-full">
                            <label for="">Surname</label>
                            <input type="text" v-model="form.surname" class="rounded border-gray-300">
                            <InputError class="mt-2" :message="form.errors.surname" />
                        </div>
                    </div>
                    <div class="flex flex-col pb-4">
                        <label for="">Email</label>
                        <input type="email" v-model="form.email" class="rounded border-gray-300">
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div class="flex flex-col pb-4">
                        <label for="">Birthday</label>
                        <input type="date" v-model="form.birthday" class="rounded border-gray-300">
                        <InputError class="mt-2" :message="form.errors.birthday" />
                    </div>
                    <div class="flex flex-col pb-6">
                        <label>Estado</label>
                        <select v-model="form.status" class="border-gray-300 rounded">
                            <option>Activo</option>
                            <option>Inactivo</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.status" />
                    </div>
                    <div class="flex">
                        <div>
                            <input type="submit" value="Update" :disabled="form.processing" class="px-8 font-bold py-2 bg-green-400 cursor-pointer rounded">
                        </div>
                        <!-- <div class="px-4">
                            <input type="reset" value="Reset" class="px-8 font-bold py-2 bg-red-500 cursor-pointer rounded">
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>
    .customer{
        padding: 150px 250px;
    }
</style>
