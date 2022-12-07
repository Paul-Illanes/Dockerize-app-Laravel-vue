<template>
    <b-card-code title="Crear Papeleta" no-body>
        <!-- form -->
        <b-row>
            <b-col md="12">
                <validation-observer ref="registerForm" #default="{ invalid }">
                    <b-form
                        class="auth-register-form mt-2 ml-2 mr-2"
                        @submit.prevent="register"
                    >
                        <b-row>
                            <b-col md="6">
                                <b-form-group>
                                    <label>Persona</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Persona"
                                    >
                                        <v-select
                                            label="name"
                                            :options="users"
                                            v-model="selectedPersona"
                                            name="users"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Periodo</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Periodo"
                                    >
                                        <v-select
                                            label="name"
                                            :options="periodos"
                                            v-model="selectedPeriodo"
                                            name="permisos"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Motivo de baja</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="motivo de baja"
                                    >
                                        <v-select
                                            label="name"
                                            :options="motivo"
                                            v-model="selectedMotivoBaja"
                                            name="permisos"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>NIT</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="NIT"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="nit"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Fecha ultimo dia</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Fecha ultimo dia"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_ultimo_dia"
                                            type="date"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Fecha cese</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="fecha cese"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_cese"
                                            type="date"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="4">
                                <b-form-group>
                                    <label>Tipo documento</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Tipo documento"
                                    >
                                        <v-select
                                            label="name"
                                            :options="documentos"
                                            v-model="selectedTipoDocumento"
                                            name="permisos"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="5">
                                <b-form-group>
                                    <label>Origen documento</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="Dependencia"
                                    >
                                        <v-select
                                            label="name"
                                            :options="dependencia"
                                            v-model="selectedOrigenDocumento"
                                            name="permisos"
                                            placeholder="Seleccione"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Numero documento</label>

                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="numero documento"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="numero_documento"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Fecha documento sustento</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="documento sustento"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="fecha_documento_sustento"
                                            type="date"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>posicion</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="selectedType"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="posicion"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="3">
                                <b-form-group>
                                    <label>Plaza</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        rules="required"
                                        name="selectedType"
                                    >
                                        <b-form-input
                                            id="example-input"
                                            v-model="plaza"
                                            type="text"
                                        />
                                        <small class="text-danger">{{
                                            errors[0]
                                        }}</small>
                                    </validation-provider>
                                </b-form-group>
                            </b-col>
                            <b-col md="6">
                                <label>Motivo</label>
                                <b-form-textarea
                                    id="textarea-default"
                                    placeholder="......"
                                    rows="4"
                                    v-model="observacion"
                                />
                            </b-col>
                            <b-col md="6">
                                <label>Archivo adjunto</label>
                                <VueFileAgent
                                    ref="vueFileAgent"
                                    :theme="'grid'"
                                    :multiple="true"
                                    :deletable="true"
                                    :meta="true"
                                    :accept="'.pdf'"
                                    :maxSize="'10MB'"
                                    :maxFiles="14"
                                    :helpText="'Arrastre y suelte los archivos'"
                                    :errorText="{
                                        type: 'formato invalido. Solo archivos pdf',
                                        size: 'el archivo supera los 10MB',
                                    }"
                                    @upload="uploadResponse('success', $event)"
                                    @select="filesSelected($event)"
                                    @beforedelete="onBeforeDelete($event)"
                                    @delete="fileDeleted($event)"
                                    v-model="fileRecords"
                                ></VueFileAgent>
                            </b-col>

                            <b-col cols="12" class="mt-2 mb-5">
                                <b-button
                                    variant="primary"
                                    @click.prevent="register"
                                >
                                    Actualizar
                                </b-button>
                                <b-button variant="danger" @click="back()">
                                    Volver
                                </b-button>
                            </b-col>
                        </b-row>
                    </b-form>
                </validation-observer>
            </b-col>
        </b-row>
    </b-card-code>
</template>

<script>
/* eslint-disable global-require */
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import flatPickr from "vue-flatpickr-component";
import {
    BCard,
    BRow,
    BCol,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BInputGroup,
    BInputGroupAppend,
    BCardTitle,
    BListGroup,
    BCardText,
    BCardBody,
    BCardSubTitle,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormTextarea,
    BTable,
    BFormRadioGroup,
    BFormRadio,
} from "bootstrap-vue";
import { required } from "@validations";
import useJwt from "@/auth/jwt/useJwt";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import moment from "moment";
import "animate.css";

export default {
    components: {
        BCard,
        BRow,
        BCol,
        BButton,
        BForm,
        BCardText,
        BCardTitle,
        BListGroup,
        BFormGroup,
        BFormInput,
        BInputGroup,
        BInputGroupAppend,
        BCardBody,
        BCardSubTitle,
        BCardCode,
        // validatons
        ValidationProvider,
        ValidationObserver,
        vSelect,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        flatPickr,
        BFormTextarea,
        BTable,
        BFormRadioGroup,
        BFormRadio,
    },

    data() {
        return {
            fileRecords: [],
            uploadUrl: "/api/archivos/save",
            uploadHeaders: {
                "X-CSRF-TOKEN": document.head.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            fileRecordsForUpload: [], // maintain an upload queue
            selectedPersona: "",
            selectedPeriodo: "",
            selectedMotivoBaja: "",
            nit: "",
            fecha_ultimo_dia: "",
            fecha_cese: "",
            selectedTipoDocumento: "",
            selectedOrigenDocumento: "",
            numero_documento: "",
            fecha_documento_sustento: "",
            posicion: "",
            plaza: "",
            observacion: "",
            users: [],
            periodos: [],
            dependencia: [],
            motivo: [],
            documentos: [],

            fields: [
                "periodo",
                "dias_pendientes",
                "dias_usados",
                "pend",
                {
                    key: "pend",
                    label: "pend",
                },
                {
                    key: "action",
                    label: "action",
                },
            ],
        };
    },
    created() {
        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/users/pluck")
            .then((response) => {
                console.log(response);
                this.users = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        this.$http
            .get("/api/auth/personal_bajas/select")
            .then((response) => {
                this.periodos = response.data.periodos;
                this.dependencia = response.data.dependencia_origen;
                this.motivo = response.data.motivo_baja;
                this.documentos = response.data.tipo_documento;
                this.getDetail();
            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
        deleteUploadedFile: function (fileRecord) {
            // Using the default uploader. You may use another uploader instead.
            this.$refs.vueFileAgent.deleteUpload(
                this.uploadUrl,
                this.uploadHeaders,
                fileRecord
            );
        },
        filesSelected: function (fileRecordsNewlySelected) {
            var validFileRecords = fileRecordsNewlySelected.filter(
                (fileRecord) => !fileRecord.error
            );
            this.fileRecordsForUpload =
                this.fileRecordsForUpload.concat(validFileRecords);
        },
        onBeforeDelete: function (fileRecord) {
            var i = this.fileRecordsForUpload.indexOf(fileRecord);
            if (i !== -1) {
                // queued file, not yet uploaded. Just remove from the arrays
                this.fileRecordsForUpload.splice(i, 1);
                var k = this.fileRecords.indexOf(fileRecord);
                if (k !== -1) this.fileRecords.splice(k, 1);
            } else {
                if (confirm("Are you sure you want to delete?")) {
                    this.$refs.vueFileAgent.deleteFileRecord(fileRecord); // will trigger 'delete' event
                }
            }
        },
        fileDeleted: function (fileRecord) {
            var i = this.fileRecordsForUpload.indexOf(fileRecord);
            if (i !== -1) {
                this.fileRecordsForUpload.splice(i, 1);
            } else {
                this.deleteUploadedFile(fileRecord);
            }
        },
        fileAdded: function (file) {
            console.log(file);
            console.log("data url: " + file.dataURL);
        },
        getNameDocumentType(data) {
            var dato = this.documentos.find((item) => item.id == data);
            return {
                name: dato.name,
                id: dato.id,
            };
        },
        getNameDependency(data) {
            var dato = this.depedencia.find((item) => item.id == data);
            return {
                name: dato.name,
                id: dato.id,
            };
        },
        getNameMotivoBaja(data) {
            var dato = this.motivo.find((item) => item.id == data);
            return {
                name: dato.name,
                id: dato.id,
            };
        },
        getNamePeriodo(data) {
            var dato = this.periodos.find((item) => item.id == data);
            return {
                name: dato.name,
                id: dato.id,
            };
        },
        getDetail() {
            this.$http
                .get(
                    "/api/auth/personal_bajas/detail/" +
                        this.$route.params.bajaId
                )
                .then((response) => {
                    console.log(response);
                    this.selectedPersona = {
                        name: response.data.nombres,
                        id: response.data.dni,
                    };
                    this.selectedPeriodo = this.getNamePeriodo(
                        response.data.periodo
                    );
                    this.selectedMotivoBaja = this.getNameMotivoBaja(
                        response.data.motivo_baja_id
                    );
                    this.nit = response.data.nit;
                    this.fecha_ultimo_dia = response.data.fecha_ultimo_dia;
                    this.fecha_cese = response.data.fecha_cese;
                    this.selectedTipoDocumento = this.getNameDocumentType(
                        response.data.tipo_documento_id
                    );
                    this.selectedOrigenDocumento = this.getNameDocumentType(
                        response.data.origen_dependencia_id
                    );
                    this.numero_documento = response.data.numero_documento;
                    this.fecha_documento_sustento =
                        response.data.fecha_documento_sustento;
                    this.posicion = response.data.posicion;
                    this.plaza = response.data.plaza;
                    this.observacion = response.data.observacion;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        back() {
            this.$router.back();
        },
        register() {
            this.$refs.registerForm.validate().then((success) => {
                if (success) {
                    this.$http
                        .post(
                            "/api/auth/personal_bajas/update/" +
                                this.$route.params.bajaId,
                            {
                                dni: this.selectedPersona.id,
                                periodo: this.selectedPeriodo.id,
                                motivo_baja_id: this.selectedMotivoBaja.id,
                                nit: this.nit,
                                fecha_ultimo_dia: this.fecha_ultimo_dia,
                                fecha_cese: this.fecha_cese,
                                tipo_documento_id:
                                    this.selectedTipoDocumento.id,
                                origen_dependencia_id:
                                    this.selectedOrigenDocumento.id,
                                numero_documento: this.numero_documento,
                                fecha_documento_sustento:
                                    this.fecha_documento_sustento,
                                posicion: this.posicion,
                                plaza: this.plaza,
                                observacion: this.observacion,
                            }
                        )
                        .then((res) => {
                            if (this.fileRecords) {
                                this.fileRecords.forEach((file, index) => {
                                    //create a form data
                                    let formData = new FormData();
                                    formData.append("file", file.file);
                                    formData.append(
                                        "id",
                                        this.$route.params.bajaId
                                    );
                                    formData.append("detalle", "adjunto");
                                    formData.append("modulo", "personal_bajas");
                                    formData.append("status", "0");
                                    this.$http
                                        .post(
                                            "/api/archivos/save",
                                            formData,
                                            this.uploadHeaders
                                        )
                                        .then(() => {
                                            console.log("archivos subidos");
                                        })
                                        .catch((error) => {
                                            console.log(error);
                                        });
                                });
                            }
                            this.$toast({
                                component: ToastificationContent,
                                position: "top-right",
                                props: {
                                    title: "Registrado Correctamente",
                                    icon: "CoffeeIcon",
                                    variant: "success",
                                },
                            });
                            this.$router.back();
                        })
                        .catch((error) => {
                            this.$refs.registerForm.setErrors(
                                error.response.data.errors
                            );
                        });
                }
            });
        },
    },
};
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>
