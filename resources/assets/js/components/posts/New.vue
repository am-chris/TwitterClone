<template>
    <div>
        <div class="form-group mb-0">
            <textarea 
                v-autosize="content" 
                v-model="content" 
                class="form-control maxlength" 
                placeholder="What's going on?" 
                required 
                rows="1" 
                maxlength="140" 
                v-on:keydown="changesDetected = true" 
                v-on:focus="changesDetected = true"
            ></textarea>
        </div>
        <div v-if="changesDetected == true" class="mt-4">
            <button type="submit" class="btn btn-sm btn-primary" v-on:click="save">Post</button>
            <ul class="list-inline float-right">
                <li class="list-inline-item">
                    <i class="fa fa-image text-muted"></i>
                </li>
                <li class="list-inline-item">
                    <i class="fa fa-play text-muted"></i>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['userId'],

    data() {
        return {
            content: '',
            changesDetected: false,
        }
    },

    methods: {
        save() {
            axios.post(route('posts.store'), {
                user_id: this.userId,
                content: this.content,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            this.content = '';
            this.changesDetected = false;
        }
    }
}
</script>
