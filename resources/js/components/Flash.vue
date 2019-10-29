<template>
    <div class="alert alert-flash" :class="'alert-'+level" role="alert" v-show="show">
        <strong>{{ level }}!</strong> {{ body }}
    </div>
</template>

<script>
    export default {

        props: ['message'],

        name: "Flash",
        data() {
            return {
                body: this.message,
                show: false,
                level: 'success',
            };
        },

        created() {
            if(this.message) {
                this.flash(this.message);
            }
            window.events.$on('flash', message => {
                this.flash(message);
            });
        },

        methods: {
            flash(data) {
                this.body = data.message;
                this.level = data.level;
                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        },

    }
</script>

<style scoped>
    .alert-flash {
        position: fixed;
        bottom: 25px;
        right: 25px;
    }
</style>
