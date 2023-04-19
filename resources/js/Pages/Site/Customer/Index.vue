<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { Link } from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";

const form = useForm({
    firstname:'',
    surname:'',
    email:'',
    birthday:''
});

const submitCustomers = () => {
    form.post(route('customers.store'), {
        onFinish: () => { }
    });
};

const remove = (customer) => {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger mr-2",
    },
    buttonsStyling: false,
  });
  swalWithBootstrapButtons
    .fire({
      title: "Tem certeza?",
      text: "Você não será capaz de reverter isso!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, remover!",
      cancelButtonText: "Não, cancelar!",
      showLoaderOnConfirm: true,
      reverseButtons: true,
      preConfirm: () => {
        return axios

          .delete("/customers/" + customer)
          .then(({ data }) => {
            if (data.success) {
              return data;
            } else {
              if (data.errors) {
                Swal.showValidationMessage(
                  `${data.errors.toString().replace(".", "")} , Tente Novamente`
                );
              } else {
                Swal.showValidationMessage(`${data.message} , Tente Novamente`);
              }
            }
          })
          .catch((error) => {
            Swal.showValidationMessage(`O pedido falhou: ${error}`);
          });
      },
      allowOutsideClick: () => !Swal.isLoading(),
    })
    .then((result) => {
      if (result.value) {
        swalWithBootstrapButtons.fire(
          "Removido!",
          "Removido com sucesso.",
          "success"
        );
        $inertia.reload();
      } else if (
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          "Cancelado",
          "Processo cancelado :)",
          "error"
        );
      }
    });
};
</script>
<template>
    <div id="container">
        <div class="customer">
            <div v-if="$page.props.success" class="bg-green-100 text-red-600 px-4 py-8">
                {{ $page.props.success }}
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[25px] font-bold">Customer List</h2>
                </div>
                <div>
                    <Link :href="route('customers.create')" class="py-2 px-4 bg-green-400 cursor-pointer rounded">
                        Add New
                    </Link>
                    <!-- <button :href="route('customers.create')" class="py-2 px-4 bg-green-400 cursor-pointer rounded"></button> -->
                </div>
            </div>
            <div class="py-4">
                <table class="w-full shadow rounded">
                    <thead class="text-[14px] uppercase bg-gray-200 ">
                        <tr>
                            <td class="p-2 pl-6">First Name</td>
                            <td class="p-2">Surname</td>
                            <td class="p-2">Email</td>
                            <td class="p-2">Birthday</td>
                            <td class="p-2">Status</td>
                            <td class="flex justify-center p-2">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="customer in $page.props.customers.data" :key="customer.id">
                            <td class="p-2 pl-6">{{ customer.firstname }}</td>
                            <td class="p-2">{{ customer.surname }}</td>
                            <td class="p-2">{{ customer.email }}</td>
                            <td class="p-2">{{ customer.birthday }}</td>
                            <td class="p-2">{{ customer.status }}</td>
                            <td class="flex justify-center p-2">
                                <div class="flex gap-1">
                                    <Link
                                      :href="route('customers.edit', customer.id)"
                                      class="font-medium text-blue-600 dark:text-blue-500 hover:underline p-2"
                                    >
                                      <!-- <i class="ri-pencil-line ri-xl"></i> -->
                                      <img src="/images/edit.png">
                                    </Link>

                                    <button
                                      @click="remove(customer.id)"
                                      class="font-medium text-red-600 dark:text-red-500 hover:underline p-2"
                                    >
                                      <!-- <i class="ri-delete-bin-2-line ri-xl"></i> -->
                                      <img src="/images/delete.png">
                                    </button>
                                  </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="w-full py-10 text-center">
                    <nav class="flex items-center p-4 justify-between pt-4" aria-label="Table navigation">
                      <span class="text-sm font-normal ">Showing
                        <span class="font-semibold text-gray-900 dark:text-black">{{
                          $page.props.customers.current_page }}-{{ $page.props.customers.last_page }}</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-black">{{
                          $page.props.customers.total
                        }}</span></span>
                      <div class="inline-flex items-center -space-x-px">
                        <pagination :links="$page.props.customers.links" />
                      </div>
                    </nav>
                  </div>
            </div>
        </div>
    </div>
</template>
<style>
.customer{
    padding: 150px 250px;
}
</style>
