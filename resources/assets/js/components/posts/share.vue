<template>
    <div>
        <span v-if="loggedIn">
            <span v-if="shared == true">
                <span class="text-success text-no-underline" @click.prevent="unshare">
                    <i class="fa fa-retweet" rel="tooltip" data-original-title="Unshare"></i>
                </span>
            </span>
            <span v-else>
                <span class="text-muted text-success-hover text-no-underline" @click.prevent="share">
                    <i class="fa fa-retweet" rel="tooltip" data-original-title="Share"></i>
                </span>
            </span>
        </span>
        <span v-else>
            <span class="text-no-underline" style="color: #BBB;">
                <i class="fa fa-retweet" rel="tooltip" data-original-title="Login to Share"></i>
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
            loggedIn: $("meta[name=loggedIn]").attr('content')
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
            var self = this;

            axios.post('/p/' + this.postId + '/unshare', {
                post_id: this.postId,
                user_id: this.userId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            if (isNaN(this.count)) this.count = 0;

            this.count = this.count - 1;
            this.shared = false;
        }
    }
}
</script>
