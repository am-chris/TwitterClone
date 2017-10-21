<template>
    <div>
        <span v-if="shared == true">
            <span class="text-success text-no-underline" @click.prevent="unshare">
                <i class="fa fa-retweet"></i>
            </span>
        </span>
        <span v-else>
            <span class="text-muted text-success-hover text-no-underline" @click.prevent="share">
                <i class="fa fa-retweet"></i>
            </span>
        </span>
        {{ this.count }}
    </div>
</template>

<script>
export default {
    props: ['countOriginal', 'postId', 'sharedOriginal', 'userId'],

    data() {
        return {
            shared: this.sharedOriginal,
            count: this.countOriginal,
        }
    },

    mounted: function() {
        if (isNaN(this.count)) this.count = 0;
    },

    methods: {
        share() {
            var self = this;

            axios.post('/p/' + this.postId + '/share', {
                post_id: this.postId,
                user_id: this.userId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            if (isNaN(this.count)) this.count = 0;

            this.count = this.count + 1;
            this.shared = true;
        },

        unshare() {
            this.count = this.count - 1;
            this.shared = false;
        }
    }
}
</script>
