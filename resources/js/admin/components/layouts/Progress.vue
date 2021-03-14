<template>
  <v-progress-linear
    :active="$store.getters['page/loading']"
    fixed
    color="progress"
    :value="0"
    indeterminate
  />
</template>

<style lang="scss" scoped>
.v-progress-linear--fixed {
  z-index: 10;
}
</style>
