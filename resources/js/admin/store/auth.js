import axios from 'axios';
import { API_URL } from '../../config/const';
import { OK, UNPROCESSABLE_ENTITY, ERROR_MESSAGES } from '../../modules/util';

const state = {
  user: null,
  apiStatus: null,
  referer: null,
  roles: [],
};

const getters = {
  check: state => (state.user ? true : false),
  userName: state => (state.user ? state.user.name : ''),
  userId: state => (state.user ? state.user.id : ''),
  userRole: state => (state.user ? state.user.role.identifier : ''),
  userRoleControll: state => identifier => {
    if (state.user && state.user.role_controlls[identifier]) {
      return state.user.role_controlls[identifier];
    }
    return false;
  },
  isPermission: state => slug => {
    if (!state.user.permissions) return true;

    return state.user.permissions.length === 0
      ? true
      : state.user.permissions.some(
          permission => permission.permission && permission.post_type.slug === slug
        );
  },
  referer: state => (state.referer ? state.referer : null),
  roles: state => (state.roles ? state.roles : []),
};

const mutations = {
  setUser(state, user) {
    if (user !== null) {
      user.role_controlls = {};
      state.roles.forEach(role => {
        user.role_controlls[role.identifier] = user.role_id <= role.id;
      });
    }
    state.user = user;
  },
  setRoles(state, roles) {
    state.roles = roles;
    if (state.user !== null) {
      state.user.role_controlls = {};
      state.roles.forEach(role => {
        state.user.role_controlls[role.identifier] = state.user.role_id <= role.id;
      });
    }
  },
  setApiStatus(state, status) {
    state.apiStatus = status;
  },
  setReferer(state, route) {
    state.referer = route;
  },
};

const actions = {
  /**
   * @description login
   * @param {*} context
   * @param {*} data
   * @returns
   */
  async login(context, data) {
    context.commit('setApiStatus', null);
    context.commit('error/setValidate', null, { root: true });

    const response = await axios.post(API_URL + '/admin/login', data);

    if (response.status === OK) {
      context.commit('setApiStatus', true);
      context.commit('setUser', response.data);
      return false;
    }

    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('error/setValidate', response.data.errors, { root: true });
    }

    context.commit('setApiStatus', false);
    context.dispatch(
      'system/createLog',
      { response: response, message: ERROR_MESSAGES[response.status] },
      { root: true }
    );
  },
  /**
   * @description logout
   * @param {*} context
   * @returns
   */
  async logout(context) {
    context.commit('setApiStatus', null);

    const response = await axios.get(API_URL + '/admin/logout');

    if (response.status === OK) {
      context.commit('setApiStatus', true);
      context.commit('setUser', null);
      return false;
    }

    context.commit('setApiStatus', false);
    context.dispatch(
      'system/createLog',
      { response: response, message: ERROR_MESSAGES[response.status] },
      { root: true }
    );
  },
  /**
   * @description get current user and user roles
   * @param {*} context
   * @returns
   */
  async init(context) {
    context.commit('setApiStatus', null);

    const response = await axios.get(API_URL + '/admin/store/auth');

    if (response.status === OK) {
      context.commit('setApiStatus', true);

      context.commit('setUser', response.data.user || null);
      context.commit('setRoles', response.data.roles || null);
      return false;
    }

    context.commit('setUser', null);
    context.commit('setApiStatus', false);
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
