import Vue from 'vue';
import Vuex from 'vuex';

import auth from './auth';
import error from './error';
import page from './page';
import system from './system';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    page,
    system,
  },
});

export default store;
