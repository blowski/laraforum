<template>
    <nav v-if="shouldPaginate">
        <ul class="pagination mt-3">
            <li class="page-item" v-show="prevUrl"><a href="#" @click.prevent="page--"><span class="page-link">&laquo; Previous</span></a></li>
            <li class="page-item" v-show="nextUrl"><a href="#" @click.prevent="page++"><span class="page-link">Next &raquo;</span></a></li>
        </ul>
    </nav>
</template>

<script>
    export default {

        props: [ 'dataSet' ],

        data() {

            return {
                page: 1,
                nextUrl: false,
                prevUrl: false,
            }

        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.nextUrl = this.dataSet.next_page_url;
                this.prevUrl = this.dataSet.prev_page_url;
            },

            page() {
                this.broadcast();
                this.updateUrl();
            },
        },

        computed: {

            shouldPaginate() {
                return !! this.nextUrl || !! this.prevUrl;
            },

        },

        methods: {

            broadcast() {
                this.$emit('changedPage', this.page);
            },

            updateUrl() {
                history.pushState(null, null, '?page='+this.page);
            },

        }

    }
</script>

