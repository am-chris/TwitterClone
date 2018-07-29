<template>
  <input
    type="file"
    accept="image/*"
    @change="onChange"
    required>
</template>

<script>
export default {
  methods: {
    onChange(e) {
      if (!e.target.files.length) return;

      const file = e.target.files[0];

      const reader = new FileReader();

      reader.readAsDataURL(file);

      reader.onload = (event) => {
        const src = event.target.result;

        this.$emit('loaded', { src, file, e });
      };
    },
  },
};
</script>
