import axios from 'axios';
import { API_URL } from '../../config/const';
import { OK, ERROR_MESSAGES } from '../../modules/util';

const state = {
  drawer: null,
  postTypes: [],
  loading: false,
};

const getters = {
  loading: state => state.loading,
  postTypes: state => (state.postTypes ? state.postTypes : []),
  postType: state => slug => state.postTypes.filter(postType => postType.slug === slug)[0],
};

const mutations = {
  switchDrawer(state) {
    state.drawer = !state.drawer;
  },
  switchLoading(state, boolean) {
    state.loading = boolean;
  },
  setPostTypes(state, postTypes) {
    state.postTypes = postTypes;
  },
};

const actions = {
  drawer(context) {
    context.commit('switchDrawer');
  },
  async loading(context, functions) {
    await context.commit('switchLoading', true);

    try {
      await functions();
    } catch (e) {
      await context.commit('switchLoading', false);
    }

    await context.commit('switchLoading', false);
  },
  /**
   * @description postTypes
   * @param {*} context
   * @returns
   */
  async postTypes(context) {
    context.commit('auth/setApiStatus', null, { root: true });

    const response = await axios.get(API_URL + '/admin/store/page');

    if (response.status === OK) {
      context.commit('auth/setApiStatus', true, { root: true });

      const postTypes = response.data.postTypes || null;
      context.commit('setPostTypes', postTypes);
      return false;
    }

    context.commit('setPostTypes', []);
    context.commit('auth/setApiStatus', false, { root: true });
    context.dispatch(
      'system/createLog',
      { response: response, message: ERROR_MESSAGES[response.status] },
      { root: true }
    );
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
