<template>
  <li>
    <a
      :href="route('posts.show', post.id)"
      style="text-decoration: none;">
      <div class="media">
        <a :href="route('users.show', post.user.username)">
          <img
            class="d-flex align-self-start mr-3 rounded-circle"
            :src="'/' + post.user.photo_url"
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
              :href="route('users.show', post.user.username)"
              style="color: #555;">
              {{ post.user.name }}
            </a>
            <span v-if="post.user.private">
              <i
                class="fa fa-lock text-dark"
                rel="tooltip"
                data-original-title="Private"/>
            </span>
            <span v-if="post.user.verified">
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
                <div v-if="loggedIn && currentUserId > 0">
                  <div class="dropdown">
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
                          @click="block(post.user_id)">Block @{{ post.user.username }}</a>
                      </span>
                      <span v-if="currentUserId !== post.user_id">
                        <a
                          class="dropdown-item"
                          href="#"
                          v-b-modal.report-post-modal
                          @click="selectedPost = post">Report Post</a>
                      </span>
                      <span v-if="currentUserId == post.user_id">
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="deletePost(post.id)">Delete Post</a>
                      </span>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </h6>
          <pre
            v-html="post.content"
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
                :shared-original="post.shared_by_user"
                :private="post.user.private"/>
            </li>
            <li class="list-inline-item">
              <post-like
                :post-id="post.id"
                :user-id="currentUserId"
                :count-original="post.like_count"
                :liked-original="post.liked_by_user"/>
            </li>
          </ul>
        </div>
      </div>
    </a>
  </li>
</template>

<script>
export default {
  props: {
    post: {
      type: Object,
      required: true,
    },
    currentUserId: {
      type: Number,
      required: false,
    },
  },

  data() {
    return {
      loggedIn: $('meta[name=loggedIn]').attr('content'),
    };
  },

  methods: {
    block(userId) {
      axios.post(route('users.blocks.store', userId), {
        current_user_id: this.currentUserId,
        user_id: userId,
      });
    },
  }
}
</script>
