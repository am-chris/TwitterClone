<template>
    <div>
        <div v-if="following == true">
            <button type="submit" class="btn btn-sm btn-primary" @click.prevent="unfollow()" style="min-width: 75px;">Unfollow</button>
        </div>
        <div v-else>
            <button type="submit" class="btn btn-sm btn-outline-primary" @click.prevent="follow()" style="min-width: 75px;">Follow</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['originalFollowing', 'userId', 'currentUserId'],

    data() {
        return {
            following: this.originalFollowing,
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
        }
    }
}
</script>
