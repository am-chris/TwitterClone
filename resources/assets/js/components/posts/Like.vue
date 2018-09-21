<template>
  <div>
    <span v-if="loggedIn">
      <span v-if="liked == true">
        <span
          class="text-danger text-no-underline"
          @click.prevent="unlike">
          <i
            class="fa fa-heart text-danger"
            rel="tooltip"
            data-original-title="Unlike"/>
        </span>
      </span>
      <span v-else>
        <span
          class="text-muted text-danger-hover text-no-underline"
          @click.prevent="like">
          <i
            class="fa fa-heart"
            rel="tooltip"
            data-original-title="Like"/>
        </span>
      </span>
    </span>
    <span v-else>
      <span
        class="text-no-underline"
        style="color: #BBB;">
        <i
          class="fa fa-heart"
          rel="tooltip"
          data-original-title="Login to Like"/>
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
    userId: {
      type: Number,
      required: true,
    },
    likedOriginal: {
      type: Boolean,
      required: false,
    }
  },

  data() {
    return {
      liked: false,
      count: this.countOriginal,
      loggedIn: $('meta[name=loggedIn]').attr('content'),
    };
  },

  mounted() {
    if (isNaN(this.count)) this.count = 0;

    if (this.likedOriginal) {
      this.liked = true;
    }
  },

  methods: {
    like() {
      axios.post(route('posts.likes.store', this.postId), {
        user_id: this.userId,
        post_id: this.postId,
      });

      if (isNaN(this.count)) this.count = 0;

      this.count = this.count + 1;
      this.liked = true;
    },

    unlike() {
      axios.delete(route('posts.likes.destroy', this.postId), {
        user_id: this.userId,
        post_id: this.postId,
      });

      if (isNaN(this.count)) this.count = 0;

      this.count = this.count - 1;
      this.liked = false;
    },
  },
};
</script>
