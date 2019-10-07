<script>

    import Favourite from './Favourite.vue';

    export default {
        name: "Reply",
        props: ["attributes"],
        data() {
            return {
                editing: false,
                body: this.attributes.body,
            }
        },

        components: {
            Favourite
        },

        methods: {
            update() {
                axios.patch('/replies/'+this.attributes.id, {
                    body: this.body
                });
                this.editing = false;
                flash('Updated');
            },
            destroy() {
                axios.delete('/replies/'+this.attributes.id);
                $(this.$el).fadeOut(300, () => { flash('Deleted'); });
            }
        }
    }
</script>

<style scoped>

</style>
