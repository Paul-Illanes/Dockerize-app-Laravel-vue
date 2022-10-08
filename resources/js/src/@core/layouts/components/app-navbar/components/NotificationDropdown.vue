<template>
    <b-nav-item-dropdown
        class="dropdown-notification mr-25"
        menu-class="dropdown-menu-media"
        right
    >
        <template #button-content>
            <feather-icon
                :badge="unreadNotifications.length"
                badge-classes="bg-danger"
                class="text-body"
                icon="BellIcon"
                size="21"
            />
        </template>

        <!-- Header -->
        <li class="dropdown-menu-header">
            <div class="dropdown-header d-flex">
                <h4 class="notification-title mb-0 mr-auto">Notificacion</h4>
                <b-badge pill variant="light-primary">
                    {{ unreadNotifications.length }} Nuevos
                </b-badge>
            </div>
        </li>
        <!-- <li v-for="item in unreadNotifications" :key="item.id">
            <b-media>
                <template #aside>
                    <b-avatar size="32" variant="primary">
                        <feather-icon icon="CheckIcon" />
                    </b-avatar>
                </template>
                <p class="media-heading">
                    <span class="font-weight-bolder">
                        {{ item.data.accion }}
                    </span>
                </p>
                <small class="notification-text">{{ item.created_at }}</small>
            </b-media>
        </li> -->
        <vue-perfect-scrollbar
            :settings="settings"
            class="scrollable-container media-list scroll-area"
            @ps-scroll-y="scrollHandle"
        >
            <!-- System Notifications -->
            <b-link
                v-for="notification in unreadNotifications"
                :key="notification.id"
            >
                <b-media>
                    <template #aside>
                        <b-avatar size="32" variant="primary">
                            <feather-icon icon="CheckIcon" />
                        </b-avatar>
                    </template>
                    <p class="media-heading">
                        <span class="font-weight-bolder">
                            {{ notification.data.accion }}
                        </span>
                    </p>
                    <small class="notification-text">{{
                        notification.created_at
                            | moment("DD, MMMM, YYYY, h:mm:ss a")
                    }}</small>
                </b-media>
            </b-link>
        </vue-perfect-scrollbar>
        <!-- Cart Footer -->
        <li class="dropdown-menu-footer">
            <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                block
                >marcar todas como leidas</b-button
            >
        </li>
    </b-nav-item-dropdown>
</template>

<script>
import {
    BNavItemDropdown,
    BBadge,
    BMedia,
    BLink,
    BAvatar,
    BButton,
    BFormCheckbox,
} from "bootstrap-vue";
import axios from "@axios";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import Ripple from "vue-ripple-directive";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    props: ["user"],
    components: {
        BNavItemDropdown,
        BBadge,
        BMedia,
        BLink,
        BAvatar,
        VuePerfectScrollbar,
        BButton,
        BFormCheckbox,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            unreadNotifications: [],
            settings: {
                maxScrollbarLength: 60,
                wheelPropagation: false,
            },
        };
    },
    watch: {
        allNotifications(val) {
            this.unreadNotifications = this.allNotifications.filter(
                (notification) => {
                    return notification.read_at == null;
                }
            );
        },
    },
    mounted() {
        Echo.channel("events").listen("RealTime", (e) => {
            console.log("RealTime: " + e.message);
            axios.get("/api/auth/notifications/").then((response) => {
                this.unreadNotifications = response.data.unread_notifications;
                this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                        title: "Tienes una nueva notification",
                        icon: "CoffeeIcon",
                        variant: "success",
                        text: this.unreadNotifications[0].data.accion,
                    },
                });
            });
            // this.unreadNotifications[this.lineData.length - 1].a
        });
    },
    created() {
        this.getNotifications();
    },
    methods: {
        changeDate(dato) {
            return moment(String(dato)).format("DD, MMMM, YYYY, h:mm:ss a");
        },
        scrollHandle(evt) {},
        getNotifications() {
            axios.get("/api/auth/notifications/").then((response) => {
                this.unreadNotifications = response.data.unread_notifications;
            });
        },
        markAsRead() {
            this.$http.get("/mark-all-read/" + "1").then((response) => {
                this.unreadNotifications = [];
            });
        },
    },
    // setup() {
    //     const notifications = this.unreadNotifications;
    //     /* eslint-disable global-require */
    //     const perfectScrollbarSettings = {
    //         maxScrollbarLength: 60,
    //         wheelPropagation: false,
    //     };

    //     return {
    //         perfectScrollbarSettings,
    //         notifications,
    //     };
    // },
};
</script>

<style></style>
