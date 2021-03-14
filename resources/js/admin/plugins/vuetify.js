import '@mdi/font/css/materialdesignicons.css';
import Vue from 'vue';
import Vuetify from 'vuetify/lib';
import ja from 'vuetify/es5/locale/ja.js';
import 'vuetify/dist/vuetify.min.css';

import colors from 'vuetify/lib/util/colors';

Vue.use(Vuetify);

const opts = {
  lang: {
    locales: { ja },
    current: 'ja',
  },
  icons: {
    iconfont: 'mdi',
  },
  theme: {
    themes: {
      light: {
        progress: colors.lightGreen.accent3,
      },
    },
  },
};

export default new Vuetify(opts);
