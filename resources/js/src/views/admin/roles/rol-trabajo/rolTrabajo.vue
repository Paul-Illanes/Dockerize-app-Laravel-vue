<template>
    <b-card title="Programacion Vacacional">
        <b-card-actions title="Filtros" action-collapse>
            <b-row>
                <b-col md="4">
                    <b-form-group
                        label="Area:"
                        label-for="h-first-name"
                        label-cols-md="2"
                    >
                        <v-select
                            label="name"
                            :options="areas"
                            v-model="area"
                            name="superstructura"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group
                        label="Periodo:"
                        label-for="h-first-name"
                        label-cols-md="2"
                    >
                        <v-select
                            label="name"
                            :options="anios"
                            v-model="anio"
                            name="superstructura"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group
                        label="Mes:"
                        label-for="h-first-name"
                        label-cols-md="2"
                    >
                        <v-select
                            label="name"
                            :options="meses"
                            v-model="mes"
                            name="superstructura"
                        />
                        <p v-if="rol">Fecha cierre: {{ rol.fecha_cierre }}</p>
                    </b-form-group>
                </b-col>
            </b-row>
        </b-card-actions>
        <div id="exc">
            <vue-excel-editor
                :cell-style="myMethod"
                v-model="personal"
                @select="onSelect"
                no-footer
            >
                <vue-excel-column
                    field="nombres"
                    label="Personal"
                    type="string"
                    width="250px"
                />
                <vue-excel-column
                    field="horas"
                    label="Horas"
                    type="string"
                    width="50px"
                    value="10"
                />
                <vue-excel-column
                    v-for="item in dias"
                    :field="item.num"
                    :label="item.dia"
                    type="map"
                    width="40px"
                    key-field
                    :init-style="onColor(item)"
                    :change="onBeforeChange"
                    :options="acti"
                    :readonly="item.feriado == '1' ? true : false"
                />
                <!-- <vue-excel-column
                    field="obj"
                    label="array"
                    type="string"
                    width="150px"
                /> -->
                <!-- item.dia.substr(-1) == 'D' ? feriadoStyle : normalStyle -->
                <!-- <vue-excel-column
                    field="phone"
                    label="Contact"
                    type="string"
                    width="130px"
                    :init-style="columnStyle"
                    :style="columnStyle"
                />
                <vue-excel-column
                    field="gender"
                    label="Gender"
                    type="select"
                    width="50px"
                    :options="['F', 'M', 'U']"
                />
                <vue-excel-column
                    field="age"
                    label="Age"
                    type="number"
                    width="70px"
                />
                <vue-excel-column
                    field="birth"
                    label="Date Of Birth"
                    type="date"
                    width="80px"
                /> -->
            </vue-excel-editor>
        </div>
    </b-card>
</template>
<script>
import BCardActions from "@core/components/b-card-actions/BCardActions.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import {
    BImg,
    BForm,
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    BCardText,
    BCardBody,
    BListGroup,
    BFormGroup,
    BListGroupItem,
    BFormCheckboxGroup,
    BFormCheckbox,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { avatarText } from "@core/utils/filter";
import vSelect from "vue-select";
import { onUnmounted } from "@vue/composition-api";
import store from "@/store";
import moment from "moment";
import Ripple from "vue-ripple-directive";
import axios from "axios";
export default {
    components: {
        BImg,
        BForm,
        BListGroup,
        BListGroupItem,
        BCardText,
        BCardBody,
        BCard,
        BRow,
        BCol,
        BFormInput,
        BButton,
        BTable,
        BMedia,
        BAvatar,
        BLink,
        BBadge,
        BDropdown,
        BDropdownItem,
        BPagination,
        BTooltip,
        BFormGroup,
        vSelect,
        BCardActions,
        BFormCheckboxGroup,
        BFormCheckbox,
        ValidationProvider,
        ValidationObserver,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            feriadoStyle: {
                "background-color": "blue",
            },
            domingoStyle: {
                "background-color": "#CD5C5C",
            },
            normalStyle: {},
            abelStyle: { "background-color": "orange" },
            rodriStyle: { "background-color": "green" },
            acti: [],
            dias: [],
            personal: [],
            meses: [],
            mes: "",
            areas: [],
            area: "",
            anios: [],
            anio: "",
            rol: "",
        };
    },
    methods: {
        myMethod(cell, col) {
            let nom = "";
            let obj = "";
            if (col.name || (col.label != "Personal" && col.label != "Horas"))
                nom = col.name;
            let dia = nom.replace("D", "A");

            if (cell[dia]) {
                obj = JSON.parse(JSON.stringify(cell[dia]));
            }
            if (obj.name == "N")
                return obj.cambio_turno == 1
                    ? {
                          color: "red",
                          "background-color": "rgb(43, 189, 243)",
                          "font-weight": "700",
                      }
                    : { color: "red", "font-weight": "700" };
            if (obj.name == "D")
                return obj.cambio_turno == 1
                    ? {
                          color: "black",
                          "background-color": "rgb(43, 189, 243)",
                          "font-weight": "700",
                      }
                    : { color: "black", "font-weight": "700" };
            return { color: "black" };
        },
        getAnio() {
            const fecha = new Date();
            const añoActual = fecha.getFullYear();
            // this.anios.push(añoActual);
            for (let i = 0; i < 3; i++) {
                this.anios.push(añoActual + i);
            }
            console.log(this.anios);
        },
        onColor(item) {
            if (item.dia.substr(-1) == "D") {
                return this.domingoStyle;
            } else if (item.feriado == 1) {
                return this.feriadoStyle;
            } else {
                return this.normalStyle;
            }

            console.log(selectedRows);
        },
        onSelect(selectedRows) {
            console.log(selectedRows);
        },
        onBeforeChange(newVal, oldVal, row, cell) {
            console.log(newVal, oldVal, row, cell);
            if (newVal == null) {
                console.log("no existe el valor");
                this.$http
                    .post("/api/auth/rol-detalle/delete", {
                        area_id: this.area.id,
                        dia: cell.name,
                        mes: this.mes.id,
                        anio: this.anio,
                        persona_dni: row.dni,
                        personal_rol_id: this.rol.id,
                    })
                    .then((response) => {
                        console.log(response);
                        this.getTable();
                        this.getRoles();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                if (row.horas <= 150) {
                    this.$http
                        .post("/api/auth/rol-detalle/create", {
                            area_id: this.area.id,
                            actividad_id: newVal,
                            dia: cell.name,
                            mes: this.mes.id,
                            anio: this.anio,
                            persona_dni: row.dni,
                            personal_rol_id: this.rol.id,
                        })
                        .then((response) => {
                            console.log(response.data);
                            this.getTable();
                            this.getRoles();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: "Ocurrio un inconveniente",
                            icon: "CoffeeIcon",
                            variant: "danger",
                            text: `ya supero sus horas de trabajo: `,
                        },
                    });
                    this.getTable();
                }
            }
        },
        // onBeforeChange(newVal, oldVal, row) {
        //     console.log(newVal, oldVal, row); // show all the arguments: newVal, oldVal, oldRow, field
        // },
        getTable() {
            this.$http
                .post("/api/auth/rol-detalle/index", {
                    area_id: this.area.id,
                    anio: this.anio,
                    mes: this.mes.id,
                })
                .then((response) => {
                    this.personal = response.data.personal;
                    this.dias = response.data.diasxmes;
                    this.rol = response.data.rol;
                    console.log(this.personal);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getActividades() {
            this.$http
                .post("/api/auth/rol-detalle/getActividades", {
                    area_id: this.area.id,
                })
                .then((response) => {
                    this.acti = response.data;

                    this.getTable();
                    this.getRoles();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getRoles() {
            this.$http
                .post("/api/auth/rol-detalle/getRoles", {
                    area_id: this.area.id,
                    anio: this.anio,
                    mes: this.mes.id,
                })
                .then((response) => {
                    this.rol = response.data;
                    console.log(this.rol);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    watch: {
        area: function (val, oldval) {
            if (this.mes.id && this.anio) {
                console.log("area");
            }
            // this.getActividades();
            // this.getRoles();
        },
        anio: function (val, oldval) {
            if (this.mes.id && this.area.id) {
                console.log("anio");
            }
            // this.getActividades();
            // this.getRoles();
        },
        mes: function (val, oldval) {
            if (this.area.id && this.anio) {
                console.log("periodo");
                this.getActividades();
            }
            // this.getActividades();
            // this.getRoles();
        },
    },
    created() {
        this.getAnio();
        // this.$http
        //     .post("/api/auth/rol-detalle/getActividades", {
        //         area_id: 64,
        //     })
        //     .then((response) => {
        //         this.acti = response.data;
        //         console.log(response);
        //     })
        //     .catch((error) => {
        //         console.log(error);
        //     });

        // await axios.get('/sanctum/csrf-cookie')
        this.$http
            .get("/api/auth/rol-detalle/inicio")
            .then((response) => {
                this.meses = response.data.meses;
                this.areas = response.data.servicios;
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>

<style lang="scss">
#exc {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 5px;
}
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-sweetalert.scss";
</style>
