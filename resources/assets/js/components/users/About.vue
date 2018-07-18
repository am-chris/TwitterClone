<template>
    <div>
        <div class="mb-3" style="position: relative; margin-top: -100px;">
            <a :href="'/' + user.username">
                <img class="d-flex align-self-start rounded-circle mr-3 mx-auto" :src="userPhotoUrl" style="max-width: 200px; max-height: 200px; border: 4px solid #FFF;" 
                rel="tooltip" :data-original-title="user.username" alt="Profile photo">
            </a>
        </div>
        
        <div v-if="editing === true">
            <div class="form-group">
                <input type="text" v-model="newUser.name" class="form-control font-weight-bold" placeholder="Name" required>
            </div>

            <div class="form-group">
                <input type="text" v-model="newUser.username" class="form-control" placeholder="Username" required>
            </div>

            <div class="alert alert-danger" v-if="user.verified">
                <i class="fa fa-exclamation-circle text-danger"></i> Your account will lose it's Verification status if your Username changes.
            </div>

            <div class="form-group">
                <textarea v-model="newUser.bio" class="form-control" rows="2" placeholder="Bio"></textarea>
            </div>
        </div>
        <div v-else>
            <h5>
                <a :href="'/' + user.username" style="color: #555;">{{ user.name }}</a>
                <i class="fa fa-lock text-dark" rel="tooltip" data-original-title="Private" v-if="user.private"></i>
                <i class="fa fa-check-circle text-primary" rel="tooltip" data-original-title="Verified account" v-if="user.verified"></i>
            </h5>
            <div class="form-group">
                <a :href="'/' + user.username" class="text-muted">@{{ user.username }}</a>
            </div>
            <p style="overflow-wrap: break-word; word-wrap: break-word;">{{ user.bio }}</p>
        </div>

        <span class="text-muted">
            <i class="fa fa-calendar-o"></i> Joined {{ newUser.created_at | moment("MMM DD YYYY") }}
        </span>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js';

export default {
    props: ['user', 'userPhotoUrl', 'updateUserRoute'],

    data() {
        return {
            newUser: this.user,
            editing: false,
            working: false,
        }    
    },

    mounted() {
        let self = this;
        
        EventBus.$on('editing-profile', editing => {
            self.editing = editing;
        });

        EventBus.$on('saved-changes-profile', editing => {
            self.update();
        });

        console.log(this.updateUserRoute);
    },

    methods: {
        update() {
            this.working = true;

            axios.put(this.updateUserRoute, {
                name: this.newUser.name,
                username: this.newUser.username,
                bio: this.newUser.bio,
            })
                .then(response => {

                })
                .finally(response => {
                    this.editing = false;
                    this.working = false;
                });
        }
    }
}
</script>
