<template>
    <li class="nav-item dropdown" v-if="notifications.length > 0">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-bell"></span>
        </a>
        <ul aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-item" v-for="notification in notifications">
                <a v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return { notifications: false }
        },

        created() {
            axios
                .get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data)
            ;
        },

        methods: {
            markAsRead(notification) {
                axios.delete("/profiles/" + window.App.user.name + "/notifications/" + notification.id);
                window.location = notification.data.link;
            }
        }
    }
</script>
