<template>
    <div>
        <div v-if="visible == true">
            <a href="#" @click.prevent="approve()">
                <i class="fa fa-check text-success ml-1 mr-1" rel="tooltip" data-original-title="Approve"></i>
            </a>

            <a href="#" @click.prevent="deny()">
                <i class="fa fa-times text-danger" rel="tooltip" data-original-title="Deny"></i>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['userId', 'currentUserId'],

    data() {
        return {
            visible: true
        }
    },

    methods: {
        approve() {
            this.visible = false;

            axios.post(route('users.follow_requests.approve', this.userId), {
                user_id: this.userId,
                current_user_id: this.currentUserId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error);
                this.visible = true;
            });
        },

        deny() {
            this.visible = false;

            axios.post(route('users.follow_requests.deny', this.userId), {
                user_id: this.userId,
                current_user_id: this.currentUserId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error);
                this.visible = true;
            });
        }
    }
}
</script>
