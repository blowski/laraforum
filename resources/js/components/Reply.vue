<template>
    <div class="card mt-2" :id="'reply-'+id">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a> said <span v-text="ago"></span>
                </h5>
                <div v-if="signedIn">
                    <favourite :reply="data"></favourite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group"><textarea v-model="body" class="form-control"></textarea></div>
                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        <div class="card-footer" v-if="canUpdate">
            <div class="level">
                <button class="btn btn-primary mr-1 btn-sm" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>

    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        name: "Reply",
        props: ["data"],

        data() {
            return {
                editing: false,
                body: this.data.body,
                id: this.data.id,
            }
        },

        components: {
            Favourite
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            ago() {
                return moment(this.data.created_at).fromNow();
            },
            canUpdate() {
                return this.authorise(user => this.data.user_id == user.id);
            }
        },

        methods: {
            update() {
                axios
                    .patch('/replies/'+this.data.id, {
                        body: this.body
                    })
                    .then(
                        response => {
                            this.editing = false;
                            flash('Updated');
                        },
                        error => {
                            flash(error.response.data.message, 'danger');
                        }
                    )
                ;

            },
            destroy() {
                axios.delete('/replies/'+this.data.id);
                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>
