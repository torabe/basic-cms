const state = {
  code: null,
  forbidden: false,
  validate: {},
};

const getters = {
  validate: state => name => (state.validate[name] ? state.validate[name] : []),
  forbidden: state => state.forbidden,
};

const mutations = {
  setCode(state, code) {
    state.code = code;
  },
  setForbidden(state, boolean) {
    state.forbidden = boolean;
  },
  setValidate(state, errors) {
    if (errors === null) {
      state.validate = {};
      return;
    }
    state.validate = errors ? errors : {};
  },
};

const actions = {};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
