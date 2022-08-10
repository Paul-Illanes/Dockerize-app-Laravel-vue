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
                                <vue-dropzone
                                    ref="myVueDropzone"
                                    id="dropzone"
                                    :useCustomSlot="true"
                                    :options="dropzoneOptions"
                                    @vdropzone-sending-multiple="sendMessage"
                                    v-on:vdropzone-success="uploadSuccess"
                                >
                                    <div class="dropzone-custom-content">
                                        <h3 class="dropzone-custom-title">
                                            Adjunte archivo
                                        </h3>
                                        <div class="subtitle">
                                            Suelte o arrastre
                                        </div>
                                    </div>
                                </vue-dropzone>
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

export default {
    components: {
        vueDropzone: vue2Dropzone,
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
            id: "",
            token: sessionStorage.getItem("accessToken"),
            // url: this.$http.post("/api/auth/personal_bajas/sendmessage"),
            dropzoneOptions: {
                // url: this.$http.post("/api/auth/personal_bajas/sendmessage"),
                url: "/api/auth/sendfile",
                thumbnailWidth: 150,
                acceptedFiles: ".pdf",
                maxFilesize: 0.5,
                maxFilesize: 1,
                maxFiles: 1,
                init: function () {
                    this.on("maxfilesexceeded", function (file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    });
                },
                dictRemoveFile: "Eliminar archivo",
                addRemoveLinks: true,
                parallelUploads: 3,
                autoProcessQueue: false,
                withCredentials: true,
                uploadMultiple: true,
                // headers: { Authorization: "Bearer " + this.token },
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            },
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
        console.log(this.token);
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
                    console.log(selectedTipoDocumento);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        uploadSuccess: async function (file, response) {
            this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                    title: "Archivo subido correctamente",
                    icon: "CoffeeIcon",
                    variant: "info",
                },
            });
        },
        shootMessage: async function () {
            this.$refs.myVueDropzone.processQueue();
        },
        sendMessage: async function (files, xhr, formData) {
            formData.append("id", this.id);
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
                            this.id = res.data.id;
                            this.shootMessage();
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
