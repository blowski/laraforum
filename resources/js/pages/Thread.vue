<script>
    import Replies from '../components/Replies'
    import SubscribeButton from '../components/SubscribeButton'

    export default {
        components: { Replies, SubscribeButton },

        props: ['initialRepliesCount'],

        data() {
            return {
                repliesCount: this.initialRepliesCount,
            };
        }
    }
</script>
