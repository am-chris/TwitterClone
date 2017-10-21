<template>
    <div>
        <span v-if="liked == true">
            <span class="text-danger text-no-underline" @click.prevent="unlike">
                <i class="fa fa-heart text-danger"></i>
            </span>
        </span>
        <span v-else>
            <span class="text-muted text-danger-hover text-no-underline" @click.prevent="like">
                <i class="fa fa-heart"></i>
            </span>
        </span>
        {{ this.count }}
    </div>
</template>

<script>
export default {
    props: ['countOriginal', 'likedOriginal', 'postId', 'userId'],

    data() {
        return {
            liked: this.likedOriginal,
            count: this.countOriginal,
        }
    },

    mounted: function() {
        if (isNaN(this.count)) this.count = 0;
    },

    methods: {
        like() {
            var self = this;

            axios.post('/p/' + this.postId + '/like', {
                user_id: this.userId,
                post_id: this.postId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            if (isNaN(this.count)) this.count = 0;

            this.count = this.count + 1;
            this.liked = true;
        },

        unlike() {
            var self = this;

            axios.post('/p/' + this.postId + '/unlike', {
                user_id: this.userId,
                post_id: this.postId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            if (isNaN(this.count)) this.count = 0;

            this.count = this.count - 1;
            this.liked = false;
        }
    }
}
</script>
