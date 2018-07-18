<template>
    <div>
        <div class="cover-photo" :style="'background-image: url(' + coverPhotoSrc + ');'" style="position: relative;">
            <div class="hover-border hover-pointer" v-if="editing == true" v-b-modal.changeCoverPhoto></div>
            <div class="text-white text-shadow text-center hover-pointer" style="text-align: center; position: absolute; bottom: 50px; width: 100%;" v-b-modal.changeCoverPhoto v-if="editing == true">
                <i class="fa fa-image fa-3x mb-3"></i>
                <h5 class="mb-0">Click to change your cover photo</h5>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" v-if="editing == true">
            <b-modal id="changeCoverPhoto" title="Change Your Cover Photo">
                <div class="form-group">
                    <label for="file">Cover Photo (1920x500)</label>
                    <div>
                        <image-upload name="file" @loaded="onLoad"></image-upload>
                    </div>
                </div>
                <div class="form">
                    <p>Cover Photo Preview</p>
                    <img :src="coverPhotoSrc" class="img-fluid">
                </div>
                <div slot="modal-footer" class="w-100">
                    <button type="button" class="btn btn-danger" @click="destroy()">Remove Cover Photo</button>
                </div>
            </b-modal>
        </form>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js';

export default {
    props: ['src', 'changeCoverPhotoUrl', 'user'],

    data() {
        return {
            editing: false,
            image: {},
            coverPhotoSrc: this.src,
        }
    },

    mounted() {
        let self = this;
        
        EventBus.$on('editing-profile', editing => {
            self.editing = editing;
        });
    },

    methods: {
        onLoad(image) {
            console.log(image);


            this.store(image);
        },

        store(image) {
            let data = new FormData();

            data.append('file', image.file);

            axios.post(this.changeCoverPhotoUrl, data)
                .then(response => {
                    // Set the current cover photo url's <img src> equal to the uploaded cover photo url
                    this.coverPhotoSrc = image.src;
                })
                .catch(error => {

                });
        },

        destroy() {
            this.working = true;

            axios.delete('api/u/' + this.user.id + '/cover_photo')
                .then(response => {
                    this.coverPhotoSrc = image.src;
                })
                .finally(response => {
                    this.working = false;
                });
        }
    }
}
</script>
