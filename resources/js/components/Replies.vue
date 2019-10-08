<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
        <NewReply @created="add"></NewReply>
    </div>
</template>

<script>
    import Reply from './Reply';
    import NewReply from './NewReply';

    export default {
        props: ['data'],

        components: {
            Reply,
            NewReply,
        },

        data() {
            return {
                items: this.data,
            }
        },

        methods: {

            add(reply) {
                this.items.push(reply);
                this.$emit('added', reply);
            },

            remove(index) {
                this.items.splice(index, 1);
                this.$emit('removed');
                flash('Reply removed');
            },

        }
    }

</script>

