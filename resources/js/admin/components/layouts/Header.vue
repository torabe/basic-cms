<template>
  <v-app-bar color="primary" dense dark app clipped-left>
    <v-app-bar-nav-icon @click="$store.dispatch('page/drawer')" />
    <v-toolbar-title>{{ appName }}</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-toolbar-items>
      <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on" :href="appUrl" target="_blank">
            <v-icon v-text="'mdi-application'" />
          </v-btn>
        </template>
        <span>サイトを表示</span>
      </v-tooltip>
      <UserMenu />
    </v-toolbar-items>
  </v-app-bar>
</template>

<script>
import { APP_NAME, APP_URL } from '../../../config/const';

import UserMenu from './UserMenu';

export default {
  components: {
    UserMenu,
  },
  data() {
    return {
      appName: APP_NAME,
      appUrl: APP_URL,
    };
  },
};
</script>
