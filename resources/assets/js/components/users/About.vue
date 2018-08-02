<template>
  <div>
    <div class="profile-about">
      <div
        class="mb-3"
        style="position: relative; margin-top: -100px;">
        <user-photo
          :current-user="currentUser"
          :user="user"/>
      </div>

      <div v-if="editing === true">
        <div class="form-group">
          <input
            type="text"
            v-model="newUser.name"
            class="form-control font-weight-bold"
            placeholder="Name"
            required>
        </div>

        <div class="form-group">
          <input
            type="text"
            v-model="newUser.username"
            class="form-control"
            placeholder="Username"
            required>
        </div>

        <div
          class="alert alert-danger"
          v-if="user.verified">
          <i class="fa fa-exclamation-circle text-danger"/>
          Your account will lose it's Verification status if your Username changes.
        </div>

        <div class="form-group">
          <textarea
            v-model="newUser.bio"
            class="form-control"
            rows="2"
            placeholder="Bio"/>
        </div>

        <div class="form-group">
          <input
            type="checkbox"
            v-model="newUser.private"
            id="private"
            class="mr-1">
          <label for="private">Private Account</label>

          <small
            id="passwordHelpBlock"
            class="form-text text-muted">
            Non-followers
            <strong><span v-if="newUser.private">won't</span><span v-else>will</span></strong>
            be able to see your posts, likes, and shares.
          </small>
        </div>
      </div>
      <div v-else>
        <h5>
          <a
            :href="route('users.show', user.username)"
            style="color: #555;">{{ user.name }}</a>
          <i
            class="fa fa-lock text-dark"
            rel="tooltip"
            data-original-title="Private"
            v-if="user.private"/>
          <i
            class="fa fa-check-circle text-primary"
            rel="tooltip"
            data-original-title="Verified account"
            v-if="user.verified"/>
        </h5>
        <div class="form-group">
          <a
            :href="route('users.show', user.username)"
            class="text-muted">@{{ user.username }}</a>
        </div>
        <p style="overflow-wrap: break-word; word-wrap: break-word;">{{ user.bio }}</p>
      </div>

      <span class="text-muted">
        <i class="fa fa-calendar-o"/> Joined {{ newUser.created_at | moment("MMM DD YYYY") }}
      </span>
    </div>
  </div>
</template>

<script>
import { EventBus } from '../../event-bus';

export default {
  props: {
    currentUser: {
      type: Object,
      required: true,
    },
    user: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      newUser: this.user,
      editing: false,
      working: false,
    };
  },

  mounted() {
    EventBus.$on('editing-profile', (editing) => {
      this.editing = editing;
    });

    EventBus.$on('saved-changes-profile', (editing) => {
      this.update();
    });
  },

  methods: {
    update() {
      this.working = true;

      axios.put(route('users.update', this.user.id), {
        name: this.newUser.name,
        username: this.newUser.username,
        bio: this.newUser.bio,
        private: this.newUser.private,
      })
        .finally((response) => {
          this.editing = false;
          this.working = false;
        });
    },
  },
};
</script>
