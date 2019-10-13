<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {

        props: ['initially_active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-secondary'];
            },
        },

        data() {
            return {
                active: this.initially_active,
            }
        },

        methods: {
            subscribe() {

                axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions/');
                this.active = ! this.active;

            }
        }
    }
</script>

