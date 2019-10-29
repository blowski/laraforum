<template>
    <div class="mt-2">
        <div class="form-group">
            <textarea
                v-model="body"
                name="body"
                id="body"
                rows="5"
                class="form-control"
                placeholder="Have something to say?"
            ></textarea>
        </div>
        <button type="button" class="btn btn-primary" @click="addReply">Post</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            };
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                    .then(
                        ({data}) => {
                            this.body = '';
                            flash('Your reply has been posted.');
                            this.$emit('created', data);
                        },
                        error => {
                            flash(error.response.data, 'danger');
                        }
                    )
            }
        }
    }
</script>
