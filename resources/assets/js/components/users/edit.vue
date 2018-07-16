<template>
    <span class="ml-auto">
        <button type="button" class="btn btn-outline-secondary" @click="editing = !editing; changedEditingStatus();" v-if="editing == false">Edit profile</button>
        <button type="button" class="btn btn-outline-secondary" @click="editing = false; changedEditingStatus();" v-if="editing == true">Cancel</button>    
        <button type="button" class="btn btn-outline-primary" @click="saveChanges()" v-if="editing == true">Save changes</button>    
    </span>    
</template>

<script>
import { EventBus } from '../../event-bus.js';

export default {
    props: ['canEdit'],

    data() {
        return {
            editing: false,
        }
    },

    methods: {
        saveChanges() {
            let self = this;

            self.editing = false;
            changedEditingStatus();
        },

        changedEditingStatus() {
            let self = this;
            EventBus.$emit('editing-profile', self.editing);
        }
    }
}
</script>
