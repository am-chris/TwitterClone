<template>
    <div>
        <div class="profile-photo d-flex align-self-start rounded-circle mr-3 mx-auto"
            :style="'background-image: url(' + photoSrc + ');'" 
            rel="tooltip" :data-original-title="user.username" alt="Profile photo">
            <div class="h-100 w-100 hover-pointer" v-if="editing == true" v-b-modal.changePhoto></div>
            <div 
                class="d-flex align-items-center justify-content-center h-100 hover-pointer text-white text-shadow text-center" 
                style="background-color: rgba(0,0,0,0.4); z-index: 5; min-width: 200px; min-height: 200px;" 
                v-if="editing == true"
                v-b-modal.changePhoto
            >
                <div class="align-self-center">
                    <i class="fa fa-image"></i>
                    <br>
                    <span class="font-weight-bold">Change your photo</span>
                </div>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" v-if="editing == true">
            <b-modal id="changePhoto" title="Change Your Photo">
                <div class="form-group">
                    <label for="file">Photo (Max size: 400x400, recommended to use a 1:1 aspect ratio)</label>
                    <div>
                        <image-upload name="file" @loaded="onLoad"></image-upload>
                    </div>
                </div>
                <div slot="modal-footer" class="w-100">
                    <button type="button" class="btn btn-danger" @click="destroy()">Remove Photo</button>
                </div>
            </b-modal>
        </form>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js';

export default {
    props: ['changePhotoUrl', 'currentUser', 'user'],

    data() {
        return {
            editing: false,
            image: {},
            photoSrc: this.user.photo_url,
        }
    },

    mounted() {
        let self = this;

        EventBus.$on('editing-profile', editing => {
            self.editing = editing;
        });

        EventBus.$on('saved-changes-profile', editing => {

        });
    },

    methods: {
        onLoad(image) {
            this.store(image);
        },

        store(image) {
            let data = new FormData();

            data.append('file', image.file);

            axios.post(this.changePhotoUrl, data)
                .then(response => {
                    // Set the current cover photo url's <img src> equal to the uploaded cover photo url
                    this.photoSrc = image.src;
                })
                .catch(error => {

                });
        },

        destroy() {
            this.working = true;

            axios.delete('api/u/' + this.user.id + '/photo')
                .then(response => {
                    this.photoSrc = image.src;
                })
                .finally(response => {
                    this.working = false;
                });
        }
    }
}
</script>

