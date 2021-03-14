<template>
  <v-container v-if="$store.getters['system/update']">
    <v-alert prominent type="info" outlined>
      <div class="title">アプリケーションに更新があります</div>
      <v-row>
        <v-col cols="9">
          アプリケーションの更新を検出しました。<br />
          更新ボタンをクリックするかブラウザをリロードしてください
        </v-col>
        <v-col cols="auto" align-self="end">
          <MainButton @click="reload()">更新</MainButton>
        </v-col>
      </v-row>
    </v-alert>
  </v-container>
</template>

<script>
import MainButton from '../buttons/MainButton';

export default {
  components: {
    MainButton,
  },
  methods: {
    reload() {
      window.location.reload(true);
    },
  },
};
</script>
