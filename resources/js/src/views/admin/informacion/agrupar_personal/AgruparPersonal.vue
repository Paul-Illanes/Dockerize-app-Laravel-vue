<template>
    <b-card-code
        :title="
            grupo.supestructura + ' - ' + grupo.dependencia + ' - ' + grupo.area
        "
        no-body
    >
        <b-row class="mx-2">
            <b-col cols="12" md="6">
                <b-form-group>
                    <label style="font-weight: 700">Personal</label>
                    <v-select
                        v-model="selectedPersonal"
                        label="name"
                        item-value="dni"
                        item-text="name"
                        :options="personal"
                        placeholder="Seleccione"
                    />
                </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
                <b-button
                    v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                    variant="outline-primary"
                    class="btn-icon mt-2"
                    v-on:click="back()"
                >
                    Regresar
                </b-button>
            </b-col>
        </b-row>
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
            <template #cell(area)="data">
                <b-badge
                    variant="primary"
                    class="text-nowrap"
                    v-on:click="setModalData(data.item)"
                >
                    <feather-icon icon="RefreshCwIcon" />
                    {{ data.item.area }}
                </b-badge>

                <!-- </div> -->
            </template>
            <template #cell(action)="data">
                <div class="text-nowrap">
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
            ref="my-modal-change"
            id="modal-change"
            centered
            hide-footer
            title="Cambiar de area"
            no-close-on-backdrop
        >
            <b-card-body>
                <validation-observer ref="cambioForm">
                    <b-form
                        class="auth-register-form mt-2 ml-2"
                        @submit.prevent="cambio_area"
                    >
                        <b-col md="12">
                            <label>Persona</label>
                            <b-form-input
                                :disabled="true"
                                v-model="modalData.persona"
                                type="text"
                            />
                            <label>Area origen</label>
                            <b-form-input
                                :disabled="true"
                                v-model="modalData.area"
                                type="text"
                            />
                            <b-form-group>
                                <label>Area destino</label>

                                <validation-provider
                                    #default="{ errors }"
                                    rules="required"
                                    name="cambioarea"
                                >
                                    <v-select
                                        label="name"
                                        :options="areas"
                                        v-model="modalData.cambio_area"
                                        name="users"
                                        placeholder="Seleccione"
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
                                @click.prevent="cambio_area"
                            >
                                Cambiar Area
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
            personal: [],
            supestructuras: [],
            dependencia: [],
            selectedEstructura: "",
            selectedPersonal: "",
            selectedDependencia: "",
            grupo: "",
            items: [],
            area: "",
            areas: [],
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
                id: "",
                persona_id: "",
                persona: "",
                area: "",
                cambio_area: "",
                area_servicio: "",
                personal_area_id: "",
            },
            fields: [
                {
                    key: "index",
                    label: "#",
                    sortable: true,
                },
                { key: "dni", label: "DNI", sortable: true },
                { key: "nombres", label: "Nombres", sortable: true },
                { key: "cargo", label: "Cargo", sortable: true },
                {
                    key: "supestructura",
                    label: "Sup-estructura",
                    sortable: false,
                },
                { key: "dependencia", label: "Dependencia", sortable: false },
                { key: "area", label: "Area", sortable: false },
                { key: "action", label: "Action", sortable: false },
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
        back() {
            this.$router.back();
        },
        setModalData(data) {
            this.$http
                .post("/api/auth/personal_area/search_areas", {
                    dependencia: this.grupo.dependencia_id,
                    supestructura: this.grupo.supestructura_id,
                    area: this.area,
                })
                .then((res) => {
                    console.log(data);
                    this.areas = res.data;
                    if (this.areas.length == 0) {
                        this.$toast({
                            component: ToastificationContent,
                            position: "top-right",
                            props: {
                                title: "No se encontro mas areas",
                                icon: "CoffeeIcon",
                                variant: "success",
                                text: `No existe areas con la supestructura y dependencia elegidas`,
                            },
                        });
                    } else {
                        this.$refs["my-modal-change"].show();
                    }
                });
            this.modalData.area = data.area;
            this.modalData.persona = data.nombres;
            this.modalData.persona_id = data.dni;
            this.modalData.area_servicio = data.area_servicio;
            this.modalData.personal_area_id = data.personal_area_id;
            this.modalData.id = data.id;
        },
        cambio_area() {
            this.$refs.cambioForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post("/api/auth/personal_area/cambioArea", {
                            id: this.modalData.id,
                            personal_area_id: this.modalData.cambio_area.id,
                            area_servicio: this.modalData.area_servicio,
                            dni: this.modalData.persona_id,
                        })
                        .then(() => {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Se realizo el cambio correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                    text:
                                        `Nueva area: ` +
                                        this.modalData.cambio_area.name,
                                },
                            });
                            this.$refs["my-modal-change"].hide();
                            this.limpiar_modal();
                            this.getEmpleados();
                        })
                        .catch((error) => {
                            this.$refs.observerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
        limpiar_modal() {
            this.modalData.id = "";
            this.modalData.persona_id = "";
            this.modalData.persona = "";
            this.modalData.area = "";
            this.modalData.cambio_area = "";
            this.modalData.area_servicio = "";
            this.modalData.personal_area_id = "";
        },
        limpiar() {
            this.selectedEstructura = "";
            this.selectedDependencia = "";
            this.selectedPersonal = 0;
            this.area = "";
            this.items = [];
            this.grupo = "";
        },
        getGrupo() {
            this.$http
                .get(
                    "/api/auth/personal_area/search_grupo/" +
                        this.$route.params.areaId
                )
                .then((response) => {
                    this.grupo = response.data;
                    console.log(this.grupo);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getEmpleados() {
            this.$http
                .get(
                    "/api/auth/personal_area/getEmpleados/" +
                        this.$route.params.areaId
                )
                .then((response) => {
                    this.items = response.data;
                    this.totalRows = this.items.length;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getPersonal() {
            this.$http
                .get(
                    "/api/auth/personal_area/get_personal/" +
                        this.$route.params.areaId
                )
                .then((response) => {
                    this.personal = response.data;
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
            console.log(id);
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
                            this.getEmpleados();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
    },
    watch: {
        selectedPersonal: function (val, oldval) {
            if (val != 0) {
                this.$http
                    .post("/api/auth/personal_area/create_personal", {
                        personal_area_id: this.$route.params.areaId,
                        dni: val.dni,
                    })
                    .then((res) => {
                        if (res.status == 202) {
                            this.$toast(
                                {
                                    component: ToastificationContent,
                                    position: "top-right",
                                    props: {
                                        title: res.data.nombres,
                                        icon: "CoffeeIcon",
                                        variant: "success",
                                        text:
                                            `Ya pertenece a supestructura: ` +
                                            res.data.supestructura +
                                            `, dependencia: ` +
                                            res.data.dependencia +
                                            ", Area: " +
                                            res.data.area,
                                    },
                                },
                                {
                                    timeout: 8000,
                                }
                            );
                            this.getPersonal();
                            this.selectedPersonal = 0;
                        } else {
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Personal Agregado",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.getEmpleados();
                            this.getPersonal();
                            this.selectedPersonal = 0;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
    },
    created() {
        this.getGrupo();
        this.getEmpleados();
        this.getPersonal();
    },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
</style>
