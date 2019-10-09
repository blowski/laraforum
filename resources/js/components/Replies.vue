<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changedPage="fetch"></paginator>
        <NewReply @created="add"></NewReply>
    </div>
</template>

<script>
    import Reply from './Reply';
    import NewReply from './NewReply';
    import collection from '../mixins/collection';

    export default {

        components: {
            Reply,
            NewReply,
        },

        mixins: [
            collection,
        ],

        data() {
            return {
                dataSet: false,
            }
        },

        created() {
            const urlParams = new URLSearchParams(window.location.search);
            const pageNumber = urlParams.get('page');
            this.fetch(pageNumber || 1);
        },

        methods: {

            fetch(pageNumber) {
                axios
                    .get(this.url(pageNumber))
                    .then(response => this.refresh(response))
                ;
            },

            refresh({data}) {
                this.items = data.data;
                this.dataSet = data;

                window.scrollTo(0, 0);
            },

            url(pageNumber) {
                return `${location.pathname}/replies/?page=${pageNumber}`;
            },

        }
    }

</script>

