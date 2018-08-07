<template>
  <div style="margin-bottom: 50px;">
    <ul class="list-unstyled posts">
      <post
        class="post"
        v-for="(post, i) in posts"
        :key="i"
        :post="post"
        :current-user-id="currentUserId"
        v-if="posts.length"/>
    </ul>
    <infinite-loading
      @infinite="onInfinite"
      :distance="distance"
      ref="infiniteLoading"
      spinner="spiral"/>
  </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';

export default {
  components: { InfiniteLoading },

  props: {
    currentUserId: {
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
      posts: [],
      distance: 1,
      currentPage: 1,
      loggedIn: $('meta[name=loggedIn]').attr('content'),
    };
  },

  methods: {
    blockUser(userId) {
      axios.post(route('users.blocks.store', userId), {
        current_user_id: this.currentUserId,
        user_id: userId,
      });
    },

    deletePost(postId) {
      axios.delete(route('posts.destroy', postId), {
        user_id: this.userId,
        post_id: this.postId,
      });

      this.content = '';
      this.changesDetected = false;
    },

    onInfinite($state) {
      axios.get(route('api.user_timeline', this.userId) + '?page=' + this.currentPage, {
        params: {page: this.posts.length / 20 + 1,},
      }).then(({ data }) => {
        if (data.data.length) {
          this.posts = this.posts.concat(data.data);
          $state.loaded();
          if (this.posts.length / 20 === 10) {
            $state.complete();
          }
        } else {
          $state.complete();
        }
      });
  
      this.currentPage += 1;
    },

    abbreviateNumber(value) {
      let newValue = value;
      const suffixNum = Math.floor(('' + value).length / 3);
      if (value >= 1000) {
        const suffixes = ['', 'k', 'm', 'b', 't'];
        let shortValue = '';
        for (var precision = 2; precision >= 1; precision--) {
          shortValue = parseFloat((suffixNum !== 0 ? (value / (1000, suffixNum)) : value).toPrecision(precision));
          const dotLessShortValue = (shortValue + '').replace(/[^a-zA-Z 0-9]+/g, '');
          if (dotLessShortValue.length <= 2) {
            break;
          }
        }
        if (shortValue % 1 !== 0) shortNum = shortValue.toFixed(1);
        newValue = shortValue + suffixes[suffixNum];
      }
      return newValue;
    },
  },
};
</script>
