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
                body: "",
            };
        },
        methods: {

            addReply() {
                if(this.body == '') {
                    return;
                }
                axios
                    .post(this.endpoint, { body: this.body })
                    .then(({data}) => {
                        this.body = '';
                        this.$emit('created', data);
                        flash("Your reply has been posted");
                    })
                ;
            }

        }
    }
</script>
