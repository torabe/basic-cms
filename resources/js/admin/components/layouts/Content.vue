<template>
  <v-container>
    <PageTitle v-if="title !== ''">
      <v-icon v-text="icon" v-if="icon !== ''" color="primary" />
      {{ title }}
    </PageTitle>
    <slot />
  </v-container>
</template>

<script>
import PageTitle from './PageTitle';

export default {
  components: {
    PageTitle,
  },
  props: {
    title: {
      type: String,
      required: false,
      default: '',
    },
    icon: {
      type: String,
      required: false,
      default: '',
    },
  },
};
</script>

<style lang="scss" scoped>
.v-subheader {
  margin-bottom: 30px;
}
</style>
