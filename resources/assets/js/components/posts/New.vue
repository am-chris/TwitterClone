<template>
  <div>
    <div class="form-group mb-0">
      <textarea
        v-autosize="content"
        v-model="content"
        class="form-control maxlength"
        placeholder="What's going on?"
        required
        rows="1"
        maxlength="140"
        @keydown="changesDetected = true"
        @focus="changesDetected = true"/>
    </div>
    <div
      v-if="changesDetected == true"
      class="mt-4">
      <button
        type="submit"
        class="btn btn-sm btn-primary"
        @click="save">Post</button>
      <ul class="list-inline float-right">
        <li class="list-inline-item">
          <i class="fa fa-image text-muted"/>
        </li>
        <li class="list-inline-item">
          <i class="fa fa-play text-muted"/>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      content: '',
      changesDetected: false,
    };
  },

  methods: {
    save() {
      axios.post(route('posts.store'), {
        user_id: this.userId,
        content: this.content,
      });

      this.content = '';
      this.changesDetected = false;
    },
  },
};
</script>
