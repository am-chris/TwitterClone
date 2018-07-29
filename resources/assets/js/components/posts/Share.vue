<template>
  <div>
    <span v-if="loggedIn">
      <span v-if="this.private == 0">
        <span v-if="shared == true">
          <span
            class="text-success text-no-underline"
            @click.prevent="unshare">
            <i
              class="fa fa-retweet"
              rel="tooltip"
              data-original-title="Unshare"/>
          </span>
        </span>
        <span v-else>
          <span
            class="text-muted text-success-hover text-no-underline"
            @click.prevent="share">
            <i
              class="fa fa-retweet"
              rel="tooltip"
              data-original-title="Share"/>
          </span>
        </span>
      </span>
      <span v-else>
        <span
          class="text-no-underline"
          style="color: #BBB;">
          <i class="fa fa-retweet"/>
        </span>
      </span>
    </span>
    <span v-else>
      <span
        class="text-no-underline"
        style="color: #BBB;">
        <i
          class="fa fa-retweet"
          rel="tooltip"
          data-original-title="Login to Share"/>
      </span>
    </span>
    {{ this.count }}
  </div>
</template>

<script>
export default {
  props: {
    countOriginal: {
      type: Number,
      required: true,
    },
    postId: {
      type: Number,
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
  },

  data() {
    return {
      shared: false,
      count: this.countOriginal,
      loggedIn: $('meta[name=loggedIn]').attr('content'),
    };
  },

  mounted() {
    if (isNaN(this.count)) this.count = 0;
  },

  methods: {
    share() {
      axios.post(route('posts.shares.store', this.postId), {
        post_id: this.postId,
        user_id: this.userId,
      });

      if (isNaN(this.count)) this.count = 0;

      this.count = this.count + 1;
      this.shared = true;
    },

    unshare() {
      axios.post(route('posts.shares.destroy', this.postId), {
        post_id: this.postId,
        user_id: this.userId,
      });

      if (isNaN(this.count)) this.count = 0;

      this.count = this.count - 1;
      this.shared = false;
    },
  },
};
</script>
