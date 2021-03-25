<template>
  <div>
    <NotFound v-if="!postType || postType.is_customize" />
    <router-view v-else />
  </div>
</template>

<script>
import NotFound from '../404/App';

export default {
  components: {
    NotFound,
  },
  data() {
    const postType = this.$store.getters['page/postType'](this.$route.params.slug);
    return {
      postType: postType,
    };
  },
};
</script>
