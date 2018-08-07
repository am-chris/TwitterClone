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

    <b-modal
      id="report-post-modal"
      ref="reportModal"
      title="Report Post">
      <b-form-group label="Reason">
        <b-form-radio-group
          id="radios2"
          v-model="report_reason"
          name="radioSubComponent"
          stacked>
          <b-form-radio value="nsfw">It should be marked NSFW</b-form-radio>
          <b-form-radio value="spam">It's spam</b-form-radio>
          <b-form-radio value="graphic">It's a sensitive/graphic image</b-form-radio>
          <b-form-radio value="abusive">It's abusive or harmful</b-form-radio>
        </b-form-radio-group>
      </b-form-group>
      <div slot="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          @click="reportPost(selectedPost.id)">Submit</button>
      </div>
    </b-modal>
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
  },

  data() {
    return {
      posts: [],
      distance: 1,
      currentPage: 1,
      loggedIn: $('meta[name=loggedIn]').attr('content'),
      selectedPost: {},
      report_reason: null,
    };
  },

  methods: {
    block(userId) {
      axios.post(route('users.blocks.store', userId), {
        current_user_id: this.currentUserId,
        user_id: userId,
      });
    },

    reportPost(postId) {
      axios.post(route('users.reports.store', this.selectedPost.user_id), {
        current_user_id: this.currentUserId,
        user_id: this.selectedPost.user_id,
        post_id: postId,
        type: 'post',
        reason: this.report_reason,
      })
        .finally((response) => {
          this.report_reason = null;
          this.$refs.reportModal.hide();
        });
    },

    deletePost(postId) {
      axios.delete(route('posts.destroy', postId), {
        user_id: this.currentUserId,
        post_id: this.postId,
      });

      this.content = '';
      this.changesDetected = false;
    },

    onInfinite($state) {
      axios.get(route('api.timeline', this.currentUserId) + '?page=' + this.currentPage, {
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
      if (value >= 1000) {
        const suffixes = ['', 'k', 'm', 'b', 't'];
        const suffixNum = Math.floor((`${value}`).length / 3);
        let shortValue = '';
        for (let precision = 2; precision >= 1; precision--) {
          shortValue = parseFloat(
            (suffixNum !== 0 ? (value / Math.pow(1000, suffixNum)) : value).toPrecision(precision)
          );
          const dotLessShortValue = (`${shortValue}`).replace(/[^a-zA-Z 0-9]+/g, '');
          if (dotLessShortValue.length <= 2) { break; }
        }
        if (shortValue % 1 !== 0) shortNum = shortValue.toFixed(1);
        newValue = shortValue + suffixes[suffixNum];
      }
      return newValue;
    },
  },
};
</script>
