<template>
    <div class="navbar-container d-flex content align-items-center">
        <!-- Nav Menu Toggler -->
        <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item">
                <b-link class="nav-link" @click="toggleVerticalMenuActive">
                    <feather-icon icon="MenuIcon" size="21" />
                </b-link>
            </li>
        </ul>

        <!-- Left Col -->
        <div
            class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex"
        >
            <dark-Toggler class="d-none d-lg-block" />
        </div>

        <b-navbar-nav class="nav align-items-center ml-auto">
            <notification-dropdown />
            <b-nav-item-dropdown
                right
                toggle-class="d-flex align-items-center dropdown-user-link"
                class="dropdown-user"
            >
                <template #button-content>
                    <div class="d-sm-flex d-none user-nav">
                        <p class="user-name font-weight-bolder mb-0">
                            {{ userData.name }} {{ userData.lastname }}
                        </p>
                        <span class="user-status">{{ userData.role }}</span>
                    </div>
                    <b-avatar
                        size="40"
                        variant="light-primary"
                        badge
                        :src="
                            require('@/assets/images/avatars/avatar_logo.png')
                        "
                        class="badge-minimal"
                        badge-variant="success"
                    />
                </template>

                <b-dropdown-divider />

                <b-dropdown-item
                    @click="logout()"
                    link-class="d-flex align-items-center"
                >
                    <feather-icon size="16" icon="LogOutIcon" class="mr-50" />
                    <span>Cerrar Session</span>
                </b-dropdown-item>
            </b-nav-item-dropdown>
        </b-navbar-nav>
    </div>
</template>

<script>
import {
    BLink,
    BNavbarNav,
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
} from "bootstrap-vue";
import { getUserData } from "@/auth/utils";
import DarkToggler from "@core/layouts/components/app-navbar/components/DarkToggler.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import NotificationDropdown from "@core/layouts//components/app-navbar/components/NotificationDropdown.vue";

export default {
    components: {
        BLink,
        BNavbarNav,
        BNavItemDropdown,
        BDropdownItem,
        BDropdownDivider,
        BAvatar,
        NotificationDropdown,
        // Navbar Components
        DarkToggler,
    },
    data() {
        return {
            userData: [],
        };
    },
    props: {
        toggleVerticalMenuActive: {
            type: Function,
            default: () => {},
        },
    },

    mounted() {
        // Set the initial number of items
        this.userData = getUserData();
    },
    methods: {
        logout() {
            this.$http
                .get("/auth/logout")
                .then((response) => {
                    // if (!localStorage.getItem(`userData`)) {
                    window.localStorage.clear();
                    window.sessionStorage.clear();
                    this.$router.push("/login").catch(() => {});
                    // }
                    // this.$router.push("/login").catch(() => {});
                    this.$toast({
                        component: ToastificationContent,
                        position: "top-right",
                        props: {
                            title: `Hasta Luego ${
                                this.userData.name || this.userData.email
                            }`,
                            icon: "CoffeeIcon",
                            variant: "success",
                            text: `Cerraste sesion!`,
                        },
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
            this.$router.push("/login").catch(() => {});
        },
    },
};
</script>
