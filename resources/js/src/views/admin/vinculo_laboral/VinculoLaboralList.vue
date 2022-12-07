<template>
    <b-card-code title="Vinculo laboral de personal" no-body>
        <div class="m-2">
            <!-- Table Top -->
            <b-row>
                <!-- Per Page -->
                <b-col
                    cols="12"
                    md="6"
                    class="d-flex align-items-center justify-content-start mb-md-0"
                >
                    <b-form-group
                        label="Mostrar"
                        label-cols="6"
                        label-align="left"
                        label-size="sm"
                        label-for="sortBySelect"
                        class="text-nowrap mb-md-0 mr-1"
                    >
                        <b-form-select
                            id="perPageSelect"
                            v-model="perPage"
                            size="sm"
                            inline
                            :options="pageOptions"
                            class="mr-1"
                        />
                    </b-form-group>
                    <b-button
                        v-if="$can('vinculo_laboral-create', 'ACL')"
                        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                        variant="outline-primary"
                        class="btn-icon"
                        v-on:click="setModalData()"
                    >
                        Registrar
                    </b-button>
                </b-col>

                <!-- Search -->
                <b-col cols="12" md="6">
                    <div class="d-flex align-items-center justify-content-end">
                        <b-form-group
                            label="Filtrar"
                            label-cols-sm="2"
                            label-align-sm="left"
                            label-size="sm"
                            label-for="filterInput"
                            class="mb-0"
                        >
                            <b-input-group size="sm">
                                <b-form-input
                                    id="filterInput"
                                    v-model="filter"
                                    type="search"
                                    placeholder="Escriba para buscar..."
                                />
                                <b-input-group-append>
                                    <b-button
                                        :disabled="!filter"
                                        @click="filter = ''"
                                    >
                                        Limpiar
                                    </b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </div>
                </b-col>
            </b-row>
        </div>
        <b-table
            striped
            hover
            responsive
            class="position-relative"
            :per-page="perPage"
            :current-page="currentPage"
            :items="items"
            :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            :filter="filter"
            :filter-included-fields="filterOn"
            @filtered="onFiltered"
        >
            <template #cell(index)="data">
                <div class="text-center">
                    {{ data.index + 1 }}
                </div>
            </template>
            <template #cell(status)="data">
                <b-badge variant="success" v-if="data.item.status == 1">
                    Activo
                </b-badge>
                <b-badge variant="primary" v-else> Concluido </b-badge>
            </template>
            <template #cell(action)="data">
                <div class="text-nowrap">
                    <!-- <feather-icon
                        @click="setModalAudit(data.item)"
                        icon="EyeIcon"
                        class="cursor-pointer text-warning"
                        size="16"
                    /> -->
                    <feather-icon
                        v-if="$can('vinculo_laboral-edit', 'ACL')"
                        @click="setModalEdit(data.item)"
                        icon="EditIcon"
                        class="cursor-pointer text-success"
                        size="16"
                    />
                    <feather-icon
                        v-if="$can('vinculo_laboral-delete', 'ACL')"
                        icon="TrashIcon"
                        size="16"
                        class="cursor-pointer text-danger"
                        @click="confirmDelete(data.item.id)"
                    />
                </div>
            </template>
        </b-table>

        <div class="mx-2 mb-2">
            <b-row>
                <b-col
                    cols="12"
                    sm="6"
                    class="d-flex align-items-center justify-content-center justify-content-sm-start"
                >
                </b-col>
                <b-col
                    cols="12"
                    sm="6"
                    class="d-flex align-items-center justify-content-center justify-content-sm-end"
                >
                    <!-- pagination -->
                    <div>
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            first-number
                            last-number
                            prev-class="prev-item"
                            next-class="next-item"
                            class="mb-0"
                        >
                            <template #prev-text>
                                <feather-icon
                                    icon="ChevronLeftIcon"
                                    size="18"
                                />
                            </template>
                            <template #next-text>
                                <feather-icon
                                    icon="ChevronRightIcon"
                                    size="18"
                                />
                            </template>
                        </b-pagination>
                    </div>
                </b-col>
            </b-row>
        </div>
        <b-modal
            ref="my-modal-add"
            id="modal-add"
            centered
            hide-footer
            title="Registrar nuevo vinculo laboral"
            no-close-on-backdrop
            @hide="doSometing"
        >
            <b-card-body>
                <validation-observer ref="addForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="registrar"
                    >
                        <b-col md="12">
                            <validation-provider
                                #default="{ errors }"
                                rules="required"
                                name="modalData.contrato"
                                :custom-messages="customMessages"
                            >
                                <label style="font-weight: 700">Contrato</label>
                                <v-select
                                    v-model="modalData.contrato"
                                    label="name"
                                    item-value="value"
                                    item-text="name"
                                    :options="contratos"
                                    placeholder="Seleccione"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                            <b-form-group>
                                <label>Estado</label>

                                <b-form-checkbox
                                    class="mt-1"
                                    v-model="modalData.status"
                                    v-bind:true-value="1"
                                    v-bind:false-value="0"
                                >
                                    Activo
                                </b-form-checkbox>
                            </b-form-group>
                        </b-col>
                        <hr />
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="registrar"
                            >
                                Registrar
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-card-body>
        </b-modal>
        <b-modal
            ref="my-modal-edit"
            id="modal-edit"
            centered
            hide-footer
            title="Editar Vinculo Laboral"
            no-close-on-backdrop
            @hide="doSometing"
        >
            <b-card-body>
                <validation-observer ref="editForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="edit_area"
                    >
                        <b-col md="12">
                            <label style="font-weight: 700">Contrato</label>
                            <v-select
                                v-model="modalData.contrato"
                                label="name"
                                item-value="value"
                                item-text="name"
                                :options="contratos"
                                placeholder="Seleccione"
                            />
                            <b-form-group>
                                <label>Estado</label>
                                <b-form-checkbox
                                    class="mt-1"
                                    v-model="modalData.status"
                                    v-bind:true-value="1"
                                    v-bind:false-value="0"
                                >
                                    Activo
                                </b-form-checkbox>
                            </b-form-group>
                        </b-col>
                        <hr />
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="edit"
                            >
                                Actualizar
                            </b-button>
                        </b-col>
                    </b-form>
                </validation-observer>
            </b-card-body>
        </b-modal>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import Ripple from "vue-ripple-directive";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BForm,
    BRow,
    BCol,
    BOverlay,
    BCardText,
    BTable,
    BAvatar,
    BBadge,
    BFormGroup,
    BFormSelect,
    BPagination,
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BButton,
    BCardBody,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import { required } from "@validations";

export default {
    components: {
        BForm,
        BRow,
        BCol,
        BCardText,
        BOverlay,
        BCardCode,
        BTable,
        BAvatar,
        BBadge,
        BFormGroup,
        BFormSelect,
        BPagination,
        BInputGroup,
        BFormInput,
        BInputGroupAppend,
        BButton,
        BCardBody,
        vSelect,
        BDropdown,
        BDropdownItem,
        BFormCheckbox,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            customMessages: {
                required: "El campo es requerido",
            },
            contratos: [],
            items: [],
            perPage: 10,
            pageOptions: [10, 20, 50],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            modalData: {
                contrato: "",
                status: "",
                id: "",
            },
            fields: [
                {
                    key: "index",
                    label: "#",
                    sortable: true,
                },
                {
                    key: "nombres",
                    label: "Personal",
                    sortable: true,
                },
                { key: "dni", label: "DNI", sortable: true },
                { key: "c_l", label: "C. laboral", sortable: true },
                { key: "tipo_contrato", label: "Contrato", sortable: true },
                { key: "fecha_inicio", label: "F. Inicio", sortable: true },
                { key: "fecha_termino", label: "F. termino", sortable: true },
                { key: "status", label: "status", sortable: true },
                { key: "action", label: "Action", sortable: false },
            ],
        };
    },
    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter((f) => f.sortable)
                .map((f) => ({ text: f.label, value: f.key }));
        },
    },
    mounted() {
        // Set the initial number of items
        this.totalRows = this.items.length;
    },
    methods: {
        doSometing() {
            this.modalData.status = "";
            this.modalData.contrato = "";
            this.modalData.id = "";
        },
        getItems() {
            this.$http.get("/api/auth/vinculo_laboral/").then((res) => {
                console.log(res.data);
                this.items = res.data;
                this.totalRows = this.items.length;
            });
        },
        getContratos() {
            this.$http
                .get("/api/auth/vinculo_laboral/getContratos")
                .then((res) => {
                    this.contratos = res.data;
                });
        },
        setModalData() {
            this.$refs["my-modal-add"].show();
            this.getContratos();
        },
        setModalEdit(data) {
            console.log(data);
            this.getContratos();
            this.$refs["my-modal-edit"].show();
            this.modalData.contrato = {
                name: data.nombres + " - " + data.dni,
                id: data.id,
            };
            this.modalData.id = data.id;
            this.modalData.status = data.status == 1 ? true : false;
        },
        registrar() {
            this.$refs.addForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/vinculo_laboral/create", {
                            contrato_id: this.modalData.contrato.id,
                            status: this.modalData.status,
                        })
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Se registro correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.getItems();
                            this.$refs["my-modal-add"].hide();
                            this.limpiar();
                        })
                        .catch((error) => {
                            this.$refs.addForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
        edit() {
            console.log(this.modalData);
            this.$refs.editForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .put(
                            "/api/auth/vinculo_laboral/edit/" +
                                this.modalData.id,
                            {
                                contrato_id: this.modalData.contrato.id,
                                status: this.modalData.status,
                            }
                        )
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Se actualizo correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.getItems();
                            this.$refs["my-modal-edit"].hide();
                            this.limpiar();
                        })
                        .catch((error) => {
                            this.$refs.editForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
        limpiar() {
            this.dependencia = "";
            this.supestructura = "";
            this.area = "";
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        confirmDelete(id) {
            this.$swal({
                title: "Estas seguro?",
                text: "No podras revertir esta accion!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-outline-danger ml-1",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    this.$http
                        .post("/api/auth/vinculo_laboral/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Vinculo Laboral eliminado",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.getItems();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
    },
    created() {
        this.getItems();
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
.tth {
    font-size: 5px;
}
</style>
