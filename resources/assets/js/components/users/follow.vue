<template>
    <div>
        <div v-if="requested == true">
            <button type="submit" class="btn btn-sm btn-warning" @click.prevent="cancelFollowRequest()" style="min-width: 85px;">Requested</button>
        </div>
        <div v-else>
            <div v-if="following == true">
                <button type="submit" class="btn btn-sm btn-primary bg-danger-on-hover" @click.prevent="unfollow()" style="min-width: 85px;">Unfollow</button>
            </div>
            <div v-else>
                <button type="submit" class="btn btn-sm btn-outline-primary" @click.prevent="follow()" style="min-width: 85px;">Follow</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['oFollowing', 'oRequested', 'private', 'userId', 'currentUserId'],

    data() {
        return {
            following: this.oFollowing,
            requested: this.oRequested,
        }
    },

    methods: {
        follow() {
            var self = this;
            this.following = true;

            axios.post('/u/' + this.userId + '/follow', {
                user_id: this.userId,
                current_user_id: this.currentUserId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error);
                this.following = false;
            });

            if (this.private == 1) {
                this.requested = true;
            }
        },

        unfollow() {
            this.following = false;

            axios.post('/u/' + this.userId + '/unfollow', {
                user_id: this.userId,
                current_user_id: this.currentUserId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error);
                this.following = true;
            });
        },

        cancelFollowRequest() {
            this.requested = false;

            axios.post('/u/' + this.userId + '/cancel_follow_request', {
                user_id: this.userId,
                current_user_id: this.currentUserId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error);
                this.requested = true;
            });
        }
    }
}
</script>
