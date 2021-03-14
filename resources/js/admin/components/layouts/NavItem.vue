<template>
  <v-list-group
    :key="item.title"
    color="primary"
    no-action
    :prepend-icon="item.icon"
    v-if="item.children && item.role"
  >
    <template v-slot:activator>
      <v-list-item-content>
        <v-list-item-title :title="item.title">{{ item.title }}</v-list-item-title>
      </v-list-item-content>
    </template>
    <template v-for="child in item.children">
      <v-list-item
        :key="child.title"
        color="primary"
        :to="child.route"
        :exact="child.exact"
        v-if="child.role"
      >
        <v-list-item-title>{{ child.title }}</v-list-item-title>
      </v-list-item>
    </template>
  </v-list-group>

  <v-list-item :key="item.title" color="primary" :to="item.route" :exact="item.exact" v-else-if="item.role">
    <v-list-item-icon>
      <v-icon v-text="item.icon" />
    </v-list-item-icon>
    <v-list-item-title>{{ item.title }}</v-list-item-title>
  </v-list-item>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
      default: () => {},
    },
  },
};
</script>
