<template>
    <b-card-code title="Personal por servicio" no-body>
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
            <template #cell(action)="data">
                <div class="text-nowrap">
                    <feather-icon
                        @click="
                            $router.push({
                                name: 'admin-agrupar-personal',
                                params: { areaId: data.item.id },
                            })
                        "
                        :id="`invoice-row-${data.item.id}-edit-icon`"
                        icon="EyeIcon"
                        class="mx-1 cursor-pointer text-success"
                        size="16"
                    />
                    <feather-icon
                        :id="`invoice-row-${data.item.id}-preview-icon`"
                        icon="TrashIcon"
                        size="16"
                        class="mx-1 cursor-pointer text-danger"
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
            ref="my-modal-area"
            id="modal-change"
            centered
            hide-footer
            title="Registrar nueva area"
            no-close-on-backdrop
        >
            <b-card-body>
                <validation-observer ref="areaForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="registro_area"
                    >
                        <b-col md="12">
                            <label style="font-weight: 700"
                                >Sub Estructura</label
                            >
                            <v-select
                                v-model="supestructura"
                                label="name"
                                item-value="value"
                                item-text="name"
                                :options="supestructuras"
                                placeholder="Seleccione"
                            />
                            <label style="font-weight: 700">Dependencia</label>
                            <v-select
                                v-model="dependencia"
                                label="name"
                                item-value="value"
                                item-text="name"
                                :options="dependencias"
                                placeholder="Seleccione"
                                :disabled="!supestructura ? true : false"
                            />
                            <b-form-group>
                                <label>Area </label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="area"
                                >
                                    <b-form-input
                                        id="example-input"
                                        type="text"
                                        :disabled="!dependencia"
                                        v-model="area"
                                        ref="inputarea"
                                    />
                                    <small class="text-danger">{{
                                        errors[0]
                                    }}</small>
                                </validation-provider>
                            </b-form-group>
                        </b-col>
                        <hr />
                        <b-col cols="12" class="mt-2">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="registro_area"
                            >
                                Registrar
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
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            supestructuras: [],
            dependencia: "",
            supestructura: "",
            dependencias: [],
            items: [],
            area: "",
            perPage: 10,
            pageOptions: [10, 20, 50],
            totalRows: 1,
            currentPage: 1,
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            fields: [
                {
                    key: "index",
                    label: "#",
                    sortable: true,
                },
                {
                    key: "supestructura",
                    label: "Sup-Estructura",
                    sortable: true,
                },
                { key: "dependencia", label: "Dependencia", sortable: true },
                { key: "area", label: "Area", sortable: true },
                { key: "action", label: "Action", sortable: true },
            ],
            /* eslint-disable global-require */
            // codeKitchenSink,
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
        getItems() {
            this.$http.get("/api/auth/personal_area/list").then((res) => {
                this.items = res.data;
                this.totalRows = this.items.length;
            });
        },
        setModalData() {
            this.$refs["my-modal-area"].show();
            this.$http.get("/api/auth/personal_area/").then((res) => {
                this.supestructuras = res.data;
            });
        },
        registro_area() {
            this.$refs.areaForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/personal_area/create_group", {
                            supestructura_id: this.supestructura.value,
                            dependencia_id: this.dependencia.value,
                            area: this.area,
                        })
                        .then((res) => {
                            console.log(res);
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
                            this.$refs["my-modal-area"].hide();
                            this.limpiar();
                        })
                        .catch((error) => {
                            this.$refs.areaForm.setErrors(
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

        getDependencia() {
            this.$http
                .post("/api/auth/personal_area/getDependencia", {
                    cod: this.supestructura.value,
                })
                .then((response) => {
                    this.dependencias = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
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
                        .post("/api/auth/personal_area/delete/" + id)
                        .then((res) => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Personal eliminado del grupo",
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
    watch: {
        supestructura: function (val, oldval) {
            this.getDependencia();
        },
    },
    created() {
        this.getItems();
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
