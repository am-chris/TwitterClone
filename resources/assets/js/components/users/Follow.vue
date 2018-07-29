<template>
  <div>
    <div v-if="requested == true">
      <button
        type="submit"
        class="btn btn-sm btn-warning"
        @click.prevent="cancelFollowRequest()"
        style="min-width: 85px;">Requested</button>
    </div>
    <div v-else>
      <div v-if="following == true">
        <button
          type="submit"
          class="btn btn-sm btn-primary bg-danger-on-hover"
          @click.prevent="unfollow()"
          style="min-width: 85px;">Unfollow</button>
      </div>
      <div v-else>
        <button
          type="submit"
          class="btn btn-sm btn-outline-primary"
          @click.prevent="follow()"
          style="min-width: 85px;">Follow</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    oFollowing: {
      type: Boolean,
      required: true,
    },
    oRequested: {
      type: Boolean,
      required: true,
    },
    private: {
      type: Number,
      required: true,
    },
    userId: {
      type: Number,
      required: true,
    },
    currentUserId: {
      type: Number,
      required: false,
    },
  },

  data() {
    return {
      following: this.oFollowing,
      requested: this.oRequested,
    };
  },

  methods: {
    follow() {
      this.following = true;

      axios.post(route('users.follows.store', this.userId), {
        user_id: this.userId,
        current_user_id: this.currentUserId,
      })
        .catch((error) => {
          this.following = false;
        });

      if (this.private === 1) {
        this.requested = true;
      }
    },

    unfollow() {
      this.following = false;

      axios.post(route('users.follows.destroy', this.userId), {
        user_id: this.userId,
        current_user_id: this.currentUserId,
      })
        .catch((error) => {
          this.following = true;
        });
    },

    cancelFollowRequest() {
      this.requested = false;

      axios.post(route('users.follows.deny_follow_request', this.userId), {
        user_id: this.userId,
        current_user_id: this.currentUserId,
      })
        .catch((error) => {
          this.requested = true;
        });
    },
  },
};
</script>
