<script>
    import Replies from '../components/Replies'
    export default {
        components: { Replies },

        props: ['initialRepliesCount'],

        data() {
            return {
                repliesCount: this.initialRepliesCount,
            };
        }
    }
</script>
