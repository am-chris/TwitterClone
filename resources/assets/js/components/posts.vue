<template>
    <div style="margin-bottom: 50px;">
        <ul class="list-unstyled posts">
            <li class="post" v-for="(post, index) in posts" :index="index">
                <a :href="'/p/' + post.id" style="text-decoration: none;">
                    <div class="media">
                        <a :href="'/' + post.user.username">
                            <img class="d-flex align-self-start mr-3 rounded-circle" :src="post.user.photo_url" style="max-width: 48px; max-height: 48px;" alt="User photo">
                        </a>
                        <div class="media-body truncate">
                            <div v-if="post.post_id > 0">
                                <div class="text-muted">Replying to </div>
                            </div>
                            <h6 class="mt-0">
                                <a class="text-primary-hover" :href="'/' + post.user.username" style="color: #555;">
                                    {{ post.user.name }}
                                </a>
                                <span v-if="post.user.verified > 0">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </span>
                                <ul class="list-inline text-muted d-inline-block" style="font-size: 13px; font-weight: 400;">
                                    <li class="list-inline-item">
                                        @{{ post.user.username }}
                                    </li>
                                </ul>
                                <ul class="list-inline d-inline-block float-right" style="font-size: 19px; font-weight: 400;">
                                    <li class="list-inline-item">
                                        <div class="dropdown">
                                            <a class="text-muted" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <span v-if="currentUserId !== post.user_id">
                                                    <a class="dropdown-item" href="#" @click.prevent="blockUser(post.user_id)">Block @{{ post.user.username }}</a>
                                                </span>
                                                <span v-if="currentUserId == post.user_id">
                                                    <a class="dropdown-item" href="#" @click.prevent="deletePost(post.id)">Delete Post</a>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </h6>
                            <pre v-text="post.content" class="mb-1" style="font-family: 'Roboto', arial; font-size: 14px; white-space: pre-wrap;"></pre>
                            <ul class="list-inline text-dark">
                                <li class="list-inline-item mr-3">
                                    <a class="text-muted text-primary-hover text-no-underline" :href="'/p/' + post.id">
                                        <i class="fa fa-comment"></i> {{ post.comment_count }}
                                    </a>
                                </li>
                                <li class="list-inline-item mr-3">
                                    <post-share :post-id="post.id" :user-id="currentUserId" :count-original="post.share_count"></post-share>
                                </li>
                                <li class="list-inline-item">
                                    <post-like :post-id="post.id" :user-id="currentUserId" :count-original="post.like_count"></post-like>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
        <infinite-loading :on-infinite="onInfinite" :distance="distance" ref="infiniteLoading" spinner="spiral"></infinite-loading>
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';

export default {
    components: {
        InfiniteLoading
    },

    props: ['current-user-id', 'user-id'],

    data() {
        return {
            posts: [],
            distance: 1,
            currentPage: 1,
        }
    },

    created() {
        var self = this;
    },

    methods: {
        blockUser(userId) {
            axios.post('/u/' + userId + '/block', {
                current_user_id: this.currentUserId,
                user_id: userId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });
        },

        deletePost(postId) {
            var self = this;

            axios.delete('/p/' + postId, {
                user_id: this.userId,
                post_id: this.postId,
            })
            .then(function (response) {

            })
            .catch(function (error) {
                console.log(error.response);
            });

            this.content = '';
            this.changesDetected = false;
        },

        onInfinite() {
            var self = this;
            var api = '/api/' + this.currentUserId + '/timeline?page=' + self.currentPage;

            axios.get(api)
            .then(function(response) {
                if (response.data.data) {
                    self.posts = self.posts.concat(response.data.data);
                    self.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
                    if (self.posts.length / 20 === 10) {
                        self.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
                    }
                } else {
                    self.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
                }
            })
            .catch(function (error) {
                console.log(error);
            });

            self.currentPage = self.currentPage + 1;
        },

        abbreviateNumber(value) {
            var newValue = value;
            if (value >= 1000) {
                var suffixes = ["", "k", "m", "b","t"];
                var suffixNum = Math.floor( (""+value).length/3 );
                var shortValue = '';
                for (var precision = 2; precision >= 1; precision--) {
                    shortValue = parseFloat( (suffixNum != 0 ? (value / Math.pow(1000,suffixNum) ) : value).toPrecision(precision));
                    var dotLessShortValue = (shortValue + '').replace(/[^a-zA-Z 0-9]+/g,'');
                    if (dotLessShortValue.length <= 2) { break; }
                }
                if (shortValue % 1 != 0)  shortNum = shortValue.toFixed(1);
                newValue = shortValue+suffixes[suffixNum];
            }
            return newValue;
        }
    }
}
</script>
