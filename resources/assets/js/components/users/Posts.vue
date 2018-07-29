<template>
  <div style="margin-bottom: 50px;">
    <ul class="list-unstyled posts">
      <li
        class="post"
        v-for="(post, i) in posts"
        :key="i">
        <a
          :href="'/p/' + post.id"
          style="text-decoration: none;">
          <div class="media">
            <a :href="'/' + post.user.username">
              <img
                class="d-flex align-self-start mr-3 rounded-circle"
                :src="'/storage/' + post.user.photo_url"
                style="max-width: 48px; max-height: 48px;"
                alt="User photo">
            </a>
            <div class="media-body">
              <div v-if="post.post_id > 0">
              <div class="text-muted">Replying to </div>
              </div>
              <h6 class="mt-0">
                <a
                  class="text-primary-hover"
                  :href="'/' + post.user.username"
                  style="color: #555;">
                  {{ post.user.name }}
                </a>
                <span v-if="post.user.private > 0">
                  <i
                    class="fa fa-lock text-dark"
                    rel="tooltip"
                    data-original-title="Private"/>
                </span>
                <span v-if="post.user.verified > 0">
                  <i
                    class="fa fa-check-circle text-primary"
                    rel="tooltip"
                    data-original-title="Verified account"/>
                </span>
                <ul
                  class="list-inline text-muted d-inline-block"
                  style="font-size: 13px; font-weight: 400;">
                  <li class="list-inline-item">
                    @{{ post.user.username }}
                  </li>
                </ul>
                <ul
                  class="list-inline d-inline-block float-right"
                  style="font-size: 19px; font-weight: 400;">
                  <li class="list-inline-item">
                    <div
                      class="dropdown"
                      v-if="loggedIn">
                      <a
                        class="text-muted"
                        href="#"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fa fa-angle-down"/>
                      </a>
                      <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton">
                        <span v-if="currentUserId !== post.user_id">
                          <a
                            class="dropdown-item"
                            href="#"
                            @click.prevent="blockUser(post.user_id)">Block @{{ post.user.username }}</a>
                        </span>
                        <span v-if="currentUserId == post.user_id">
                          <a
                            class="dropdown-item"
                            href="#"
                            @click.prevent="deletePost(post.id)">Delete Post</a>
                        </span>
                      </div>
                    </div>
                  </li>
                </ul>
              </h6>
              <pre
                v-text="post.content"
                class="mb-1"
                style="font-family: 'Roboto', arial; font-size: 14px; white-space: pre-wrap;"/>
              <ul class="list-inline text-dark">
                <li class="list-inline-item mr-3">
                  <a
                    class="text-muted text-primary-hover text-no-underline"
                    :href="'/p/' + post.id">
                    <i class="fa fa-comment"/>
                  </a>
                  {{ post.comment_count }}
                </li>
                <li class="list-inline-item mr-3">
                  <post-share
                    :post-id="post.id"
                    :user-id="currentUserId"
                    :count-original="post.share_count"
                    :private="post.user.private"/>
                </li>
                <li class="list-inline-item">
                  <post-like
                    :post-id="post.id"
                    :user-id="currentUserId"
                    :count-original="post.like_count"/>
                </li>
              </ul>
            </div>
          </div>
        </a>
      </li>
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
    UserId: {
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
      axios.delete('/p/' + postId, {
        user_id: this.userId,
        post_id: this.postId,
      });

      this.content = '';
      this.changesDetected = false;
    },

    onInfinite() {
      const api = '/api/' + this.userId + '/individual_timeline?page=' + this.currentPage;

      axios.get(api)
        .then((response) => {
          if (response.data.data) {
            this.posts = this.posts.concat(response.data.data);
            this.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
            if (this.posts.length / 20 === 10) {
              this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
            }
          } else {
            this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
          }
        });

      this.currentPage = this.currentPage + 1;
    },

    abbreviateNumber(value) {
      let newValue = value;
      var suffixNum = Math.floor(('' + value).length / 3);
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
