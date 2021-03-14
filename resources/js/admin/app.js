import 'core-js/stable';
import 'regenerator-runtime/runtime';

import Vue from 'vue';

import 'dayjs/locale/ja';
import dayjs from 'dayjs';
dayjs.locale('ja');
Vue.prototype.$dayjs = dayjs;

import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);

import vuetify from './plugins/vuetify';

import '../modules/bootstrap';

// root component
import App from './App.vue';

//router
import router from './router/index';

// store
import store from './store/index';

const createApp = async () => {
  await store.dispatch('auth/init');
  // await store.dispatch('system/version');
  await store.dispatch('page/postTypes');

  new Vue({
    el: '#app',
    vuetify,
    router, // ルーティングの定義を読み込む
    store, // ストアの定義を読み込む
    components: {
      App,
    }, // ルートコンポーネントの使用を宣言する
    template: '<App />', // ルートコンポーネントを描画する
  });
};

createApp();
